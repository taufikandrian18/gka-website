#!/usr/bin/env bash
# PT Gemilang Karya Agri – demo deploy. Run from /opt/gka:  sudo bash setup.sh
# Safe to re-run: it never deletes data and asks before touching Caddy.
set -euo pipefail
cd "$(dirname "$0")"

DOMAIN="website.taufikandrian.my.id"
BASE_PATH="/gka"                              # WordPress lives at https://$DOMAIN$BASE_PATH/
LEGACY_DOMAIN="gka-demo.taufikandrian.my.id"  # old address, redirected to the new one
SITE_URL="https://$DOMAIN$BASE_PATH"
CADDY="n8n-caddy-1"
ADMIN_EMAIL="${ADMIN_EMAIL:-yanti@pt-gka.com}"
say() { printf '\n\033[1;32m== %s\033[0m\n' "$*"; }
die() { printf '\n\033[1;31mSTOP: %s\033[0m\n' "$*"; exit 1; }
ask() { read -r -p "$1 [y/N] " a </dev/tty; [[ "$a" =~ ^[Yy]$ ]]; }

say "1/7 Pre-flight checks"
free_gb=$(df -BG --output=avail / | tail -1 | tr -dc 0-9)
(( free_gb >= 5 )) || die "only ${free_gb} GB free on /. Need at least 5 GB."
echo "disk free: ${free_gb} GB"
docker network inspect n8n_default >/dev/null 2>&1 || die "docker network n8n_default not found (Caddy network)."
docker ps --format '{{.Names}}' | grep -qx "$CADDY" || die "container $CADDY is not running."
resolved=$(getent hosts "$DOMAIN" | awk '{print $1}' | head -1)
[[ "$resolved" == "43.133.130.148" ]] || die "$DOMAIN resolves to '${resolved:-nothing}', expected 43.133.130.148."
echo "DNS ok: $DOMAIN -> $resolved"
[[ -d wp-content/themes/gka && -d wp-content/plugins/gka-core && -f bin/seed.php && -f docker/apache-gka.conf ]] || die "theme/plugin/bin/docker missing next to setup.sh."

say "2/7 Secrets (.env, created once, never overwritten)"
if [[ ! -f .env ]]; then
  umask 077
  cat > .env <<ENV
DOMAIN=$DOMAIN
BASE_PATH=$BASE_PATH
DB_PASSWORD=$(openssl rand -hex 24)
DB_ROOT_PASSWORD=$(openssl rand -hex 24)
ENV
  echo "created .env (mode 600)"
else
  # Keep the passwords; only the address settings follow this script.
  set_env() { if grep -q "^$1=" .env; then sed -i "s#^$1=.*#$1=$2#" .env; else echo "$1=$2" >> .env; fi; }
  set_env DOMAIN "$DOMAIN"
  set_env BASE_PATH "$BASE_PATH"
  echo ".env kept (passwords unchanged), address set to $SITE_URL"
fi
chown -R 33:33 wp-content bin 2>/dev/null || true

say "3/7 Start database + WordPress"
docker compose pull db wordpress cli
docker compose up -d db wordpress
echo "waiting for WordPress files..."
for i in $(seq 1 40); do
  docker compose exec -T wordpress test -f /var/www/html/wp-config.php && break
  sleep 3
done
wp() { docker compose run --rm -T cli wp "$@"; }

say "4/7 Install WordPress (first run only)"
if ! wp core is-installed >/dev/null 2>&1; then
  ADMIN_PASS=$(openssl rand -base64 18 | tr -d '/+=')
  wp core install --url="$SITE_URL" --title="PT Gemilang Karya Agri" \
     --admin_user=gka-admin --admin_password="$ADMIN_PASS" --admin_email="$ADMIN_EMAIL" --skip-email
  printf '\n\033[1;33mWordPress admin (shown ONCE, save it in your password manager):\n  URL:  %s/wp-admin/\n  user: gka-admin\n  pass: %s\033[0m\n' "$SITE_URL" "$ADMIN_PASS"
else
  echo "already installed"
  # Moving address (e.g. gka-demo.* -> website.*/gka): rewrite stored URLs once. Idempotent.
  stored=$(wp eval 'global $wpdb; echo $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name = \"home\"");' | tr -d '[:space:]')
  if [[ -n "$stored" && "$stored" != "$SITE_URL" ]]; then
    echo "moving site URLs: $stored -> $SITE_URL"
    backup="gka-before-move-$(date +%Y%m%d%H%M%S).sql"
    wp db export - > "$backup" && [[ -s "$backup" ]] || die "database backup failed; nothing was changed."
    chmod 600 "$backup"
    echo "database backup: /opt/gka/$backup"
    wp search-replace "$stored" "$SITE_URL" --all-tables --skip-columns=guid --precise --report-changed-only
    wp option update home "$SITE_URL"
    wp option update siteurl "$SITE_URL"
  fi
fi

say "5/7 Language, plugins, theme, demo content"
wp language core install id_ID --activate || echo "(language pack skipped)"
wp plugin install contact-form-7 --activate || echo "(Contact Form 7 install failed; contact page falls back to WhatsApp/email)"
wp plugin activate gka-core
wp theme activate gka
wp plugin delete hello akismet >/dev/null 2>&1 || true
wp theme delete twentytwentythree twentytwentyfour >/dev/null 2>&1 || true
wp option update blog_public 0
wp eval-file /opt/gka-bin/seed.php
wp eval-file /opt/gka-bin/fetch-assets.php || echo "(photo import failed; placeholders stay)"
wp eval-file /opt/gka-bin/apply-photos.php || echo "(curated photos not applied)"
wp rewrite structure '/%postname%/' --hard
wp rewrite flush --hard

say "6/7 Caddy routes: $SITE_URL/ (+ redirect from $LEGACY_DOMAIN)"
src=$(docker inspect "$CADDY" --format '{{range .Mounts}}{{if eq .Destination "/etc/caddy/Caddyfile"}}{{.Source}}{{end}}{{end}}')
[[ -n "$src" && -f "$src" ]] || die "Caddyfile is not a bind mount on the host; add Caddyfile.gka (or docker/caddy-gka-handle.caddy) manually."
# Start from the current Caddyfile minus every block this script added before (old demo domain included).
tmp=$(mktemp)
awk '/^# --- GKA (demo|legacy domain|\(added)/ {skip=1} !skip {print} /^# --- end GKA( demo| legacy domain)? ---/ {skip=0}' "$src" > "$tmp"
if grep -q "gka-wordpress" "$tmp"; then
  echo "found your own GKA handle inside the $DOMAIN block, keeping it"
elif grep -qE "(^|[[:space:],])$DOMAIN([[:space:],{:]|$)" "$tmp"; then
  rm -f "$tmp"
  printf '\n%s already has a site block in %s.\nPaste this inside it, above any catch-all handle/reverse_proxy/file_server, then re-run setup.sh:\n\n' "$DOMAIN" "$src"
  cat docker/caddy-gka-handle.caddy
  die "Caddy not changed. WordPress is already switched to $SITE_URL."
else
  cat Caddyfile.gka >> "$tmp"
fi
cat docker/caddy-gka-legacy.caddy >> "$tmp"
if cmp -s "$tmp" "$src"; then
  echo "routes already up to date"
else
  diff -u "$src" "$tmp" || true
  if ask "Apply this change to $src and reload Caddy (no downtime for other sites)?"; then
    bak="$src.bak-$(date +%Y%m%d%H%M%S)"
    cp "$src" "$bak"
    cat "$tmp" > "$src"   # write in place: the file is bind-mounted, so its inode must not change
    if docker exec "$CADDY" caddy validate --config /etc/caddy/Caddyfile --adapter caddyfile >/dev/null 2>&1; then
      docker exec "$CADDY" caddy reload --config /etc/caddy/Caddyfile --adapter caddyfile
      echo "Caddy reloaded (backup: $bak)"
    else
      cat "$bak" > "$src"
      die "Caddy rejected the new config; original restored. Nothing changed."
    fi
  else
    echo "skipped. Apply the diff above manually later."
  fi
fi
rm -f "$tmp"

say "7/7 Smoke test"
sleep 5
for p in / /tentang-kami/ /bisnis-kami/ /esg/ /karir/ /hubungi-kami/; do
  printf '%-18s %s\n' "$p" "$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL$p")"
done
docker ps --filter name=gka- --format 'table {{.Names}}\t{{.Status}}'
echo
printf '%-18s %s\n' "old domain" "$(curl -s -o /dev/null -w '%{http_code} -> %{redirect_url}' "https://$LEGACY_DOMAIN/karir/")"
echo "Done. Site: $SITE_URL/   Admin: $SITE_URL/wp-admin/"
