#!/usr/bin/env bash
# PT Gemilang Karya Agri – demo deploy. Run from /opt/gka:  sudo bash setup.sh
# Safe to re-run: it never deletes data and asks before touching Caddy.
set -euo pipefail
cd "$(dirname "$0")"

DOMAIN="gka-demo.taufikandrian.my.id"
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
[[ -d wp-content/themes/gka && -d wp-content/plugins/gka-core && -f bin/seed.php ]] || die "theme/plugin/bin missing next to setup.sh."

say "2/7 Secrets (.env, created once, never overwritten)"
if [[ ! -f .env ]]; then
  umask 077
  cat > .env <<ENV
DOMAIN=$DOMAIN
DB_PASSWORD=$(openssl rand -hex 24)
DB_ROOT_PASSWORD=$(openssl rand -hex 24)
ENV
  echo "created .env (mode 600)"
else
  echo ".env already exists, keeping it"
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
  wp core install --url="https://$DOMAIN" --title="PT Gemilang Karya Agri" \
     --admin_user=gka-admin --admin_password="$ADMIN_PASS" --admin_email="$ADMIN_EMAIL" --skip-email
  printf '\n\033[1;33mWordPress admin (shown ONCE, save it in your password manager):\n  URL:  https://%s/wp-admin\n  user: gka-admin\n  pass: %s\033[0m\n' "$DOMAIN" "$ADMIN_PASS"
else
  echo "already installed"
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
wp rewrite structure '/%postname%/' --hard
wp rewrite flush --hard

say "6/7 Caddy route for $DOMAIN"
src=$(docker inspect "$CADDY" --format '{{range .Mounts}}{{if eq .Destination "/etc/caddy/Caddyfile"}}{{.Source}}{{end}}{{end}}')
[[ -n "$src" && -f "$src" ]] || die "Caddyfile is not a bind mount on the host; add the block from Caddyfile.gka manually."
if grep -q "$DOMAIN" "$src"; then
  echo "route already present in $src"
else
  echo "Caddyfile on host: $src"
  if ask "Append the GKA block to this Caddyfile and reload Caddy (no downtime for other sites)?"; then
    cp "$src" "$src.bak-$(date +%Y%m%d%H%M%S)"
    cat Caddyfile.gka >> "$src"
    if docker exec "$CADDY" caddy validate --config /etc/caddy/Caddyfile --adapter caddyfile >/dev/null 2>&1; then
      docker exec "$CADDY" caddy reload --config /etc/caddy/Caddyfile --adapter caddyfile
      echo "Caddy reloaded"
    else
      cp "$(ls -t "$src".bak-* | head -1)" "$src"
      die "Caddy rejected the new config; original restored. Nothing changed."
    fi
  else
    echo "skipped. Add Caddyfile.gka manually later."
  fi
fi

say "7/7 Smoke test"
sleep 5
for p in / /tentang-kami/ /bisnis-kami/ /esg/ /karir/ /hubungi-kami/; do
  printf '%-18s %s\n' "$p" "$(curl -s -o /dev/null -w '%{http_code}' "https://$DOMAIN$p")"
done
docker ps --filter name=gka- --format 'table {{.Names}}\t{{.Status}}'
echo
echo "Done. Open https://$DOMAIN"
