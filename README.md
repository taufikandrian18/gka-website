# GKA demo deploy

Target: `https://website.taufikandrian.my.id/gka/` (admin: `/gka/wp-admin/`) on the Ubuntu VPS, behind the existing `n8n-caddy-1`.
The old `https://gka-demo.taufikandrian.my.id` redirects there (301, path kept).

How the subdirectory works: Caddy forwards `/gka/*` unchanged to `gka-wordpress`, Apache aliases `/gka`
onto the WordPress root (`docker/apache-gka.conf`), and `WP_HOME` is `https://$DOMAIN$BASE_PATH` from `.env`.
The theme prefixes its root-relative links (`/karir/`, `/wp-content/...`) with the base path at render time.

## Build the deploy zip (on your Mac, inside the repo)
```bash
git checkout main && git pull
git archive --format=zip -o ~/Downloads/gka-deploy.zip HEAD
```
The zip has the repo layout (`setup.sh`, `docker-compose.yml`, `wp-content/`, `bin/`) that `setup.sh` expects.

## First deploy

On your Mac:
```bash
scp ~/Downloads/gka-deploy.zip ubuntu@43.133.130.148:~
ssh ubuntu@43.133.130.148
```
On the VPS:
```bash
sudo apt-get install -y unzip >/dev/null
sudo mkdir -p /opt/gka && sudo unzip -o ~/gka-deploy.zip -d /opt/gka
cd /opt/gka && sudo bash setup.sh
```
The script stops before touching Caddy and asks `y/N`. It prints the WordPress admin password once.

## Move from gka-demo.taufikandrian.my.id to website.taufikandrian.my.id/gka (one time)
Deploy the new code first (zip or pipeline), then on the VPS:
```bash
cd /opt/gka && sudo bash setup.sh
```
It updates `DOMAIN`/`BASE_PATH` in `.env` (passwords untouched), recreates WordPress, backs up the database to
`/opt/gka/gka-before-move-*.sql`, rewrites stored URLs with `wp search-replace`, then shows the Caddyfile diff and
asks before reloading Caddy. If `website.taufikandrian.my.id` already has its own block in the Caddyfile, the script
stops and prints `docker/caddy-gka-handle.caddy`: paste it inside that block (above any catch-all
`handle`/`reverse_proxy`/`file_server`) and run `setup.sh` again.

Undo: `sudo docker compose run --rm -T cli wp db import - < gka-before-move-*.sql`, set `DOMAIN` back in `.env`,
remove `BASE_PATH`, restore the newest `Caddyfile.bak-*`, and `sudo docker compose up -d`.

## Update theme/plugin later

### Automatic (GitHub Actions)
Every push to `main` that touches `wp-content/`, `bin/` or `docker-compose.yml` runs `.github/workflows/deploy.yml`:
PHP/JSON/JS checks, then rsync to `/opt/gka` over SSH, `docker compose restart wordpress`, and a smoke test.
It never touches `.env`, the database or uploads. Run it by hand from the Actions tab (**Deploy demo → Run workflow**).

One-time setup:
```bash
# On your Mac: a key used only by GitHub Actions
ssh-keygen -t ed25519 -f ~/.ssh/gka_deploy -N "" -C "github-actions-gka"
ssh-copy-id -i ~/.ssh/gka_deploy.pub ubuntu@43.133.130.148
ssh -i ~/.ssh/gka_deploy ubuntu@43.133.130.148 'sudo -n true && echo sudo-ok'   # must print sudo-ok
ssh-keyscan -H 43.133.130.148                                                   # copy the output
```
Then in GitHub → **Settings → Secrets and variables → Actions → New repository secret**:

| Secret | Value |
| --- | --- |
| `VPS_HOST` | `43.133.130.148` |
| `VPS_USER` | `ubuntu` |
| `VPS_SSH_KEY` | contents of `~/.ssh/gka_deploy` (the private key, including the BEGIN/END lines) |
| `VPS_KNOWN_HOSTS` | the `ssh-keyscan` output |

Optional repository variables: `VPS_PORT` (default 22), `DEMO_URL` (default the demo domain).

### Manual (zip)
Build the zip as above, then:
```bash
scp ~/Downloads/gka-deploy.zip ubuntu@43.133.130.148:~
ssh ubuntu@43.133.130.148
sudo unzip -o ~/gka-deploy.zip -d /opt/gka && cd /opt/gka && sudo docker compose restart wordpress
```
## After any update that changes URLs (e.g. the book preview)
```bash
cd /opt/gka && sudo docker compose run --rm -T cli wp rewrite flush --hard
```

## Pull real photos from pt-gka.com (re-runnable)
```bash
cd /opt/gka && sudo docker compose run --rm -T cli wp eval-file /opt/gka-bin/fetch-assets.php
sudo docker compose run --rm -T cli wp eval-file /opt/gka-bin/apply-photos.php
```

`.env` (database passwords) and the database volume are never overwritten.

## Useful
- Logs: `sudo docker compose -f /opt/gka/docker-compose.yml logs --tail 50 wordpress`
- WP-CLI: `cd /opt/gka && sudo docker compose run --rm cli wp plugin list`
- Backup: `sudo docker exec gka-db sh -c 'mariadb-dump -ugka -p"$MARIADB_PASSWORD" gka' | gzip > ~/gka-$(date +%F).sql.gz`

## Partner / certification logos
Drop files into `wp-content/themes/gka/assets/logos/` (SVG preferred). The "Mitra & sertifikasi" strip under the homepage hero appears automatically once the folder has logos. Bump `Version:` in `style.css` whenever you add a new pattern file, or WordPress keeps serving its cached pattern list.

## Motion layer
GSAP 3 + ScrollTrigger and Lenis are vendored in `assets/vendor/` (no CDN). Everything degrades to a static page with `prefers-reduced-motion` or without JS.

## Production (later, on pt-gka.com)
Set `GKA_NOINDEX` to `false`, change `DOMAIN`, and point DNS. Replace placeholder images in `wp-content/themes/gka/assets/images/` and the media library.
