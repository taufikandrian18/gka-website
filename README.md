# GKA demo deploy

Target: `https://gka-demo.taufikandrian.my.id` on the Ubuntu VPS, behind the existing `n8n-caddy-1`.

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

## Update theme/plugin later
```bash
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

## Production (later, on pt-gka.com)
Set `GKA_NOINDEX` to `false`, change `DOMAIN`, and point DNS. Replace placeholder images in `wp-content/themes/gka/assets/images/` and the media library.
