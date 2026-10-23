# Kirby 5 migration

Branch `jprconsultingllc.com`. Target: Kirby 5.6.x on PHP 8.4, following
the playbook proven on blackletter (blt-main-app/KIRBY5_MIGRATION.md).
Trigger: PHP 8.1 is EOL.

## Status

- [x] Staging Panel restored: ENABLE_PANEL gate + clear_env=no deployed,
      stg and prod recreated from image b6ab9f5c; stg /panel answers
      behind basic auth, prod /panel is 404; stg/latest/20261023 pushed
      to Docker Hub
- [x] .gitignore `content` anchored to `/content/` (Kirby 3.9.5 has no
      src/Content, so nothing was missing yet; Kirby 4+ ships one)
- [x] devops retooled from docker-machine to the blt-pub02 context
      (sync-stg / push-stg / verify-stg subcommands, atomic content
      push with backup and rollback, uid-82 chowns)
- [x] migrate-content.py and verify-vs-prod.py ported from blackletter;
      dry run finds 46 transformable field values (apply at rung 3)
- [x] Rung 1: image rebased on php:8.2-fpm-alpine (PHP 8.2.35; gd,
      imagick, intl, zip, exif, opcache; clear_env=no; gzip w/o
      gzip_vary; absolute_redirect off; site.css generated at boot);
      verify-vs-prod.py: 12 pages, 0 differences vs production
- [ ] Rung 2: Kirby 3.9.5 -> 4.9.6 + plugin audit
- [ ] Rung 3: content transforms applied (local now, volumes at cutover)
- [ ] Rung 4: Kirby 4 -> 5.6.x
- [ ] Rung 5: PHP 8.4 bump, final battery
- [ ] Panel walkthrough on Kirby 5
- [ ] Kirby license purchase (needed at launch, not for dev; flag, don't buy)

## Local sandbox

```
docker run -d --name jpr-rung1 -p 8082:80 \
  -e ENABLE_PANEL=false -e ENABLE_PW=false -e SERVER_NAME=localhost \
  -v "$PWD/content:/app/content" -v "$PWD/.env:/app/.env" \
  -v jpr-rung1-media:/app/public/media \
  -v jpr-rung1-cache:/app/storage/cache \
  -v jpr-rung1-sessions:/app/storage/sessions \
  wagnerpaul/jprconsultingllc-main-app:rung1
python3 verify-vs-prod.py --local http://localhost:8082
```

Local storage/accounts was synced from the staging accounts volume
(content references user l0Knepo0; the stale local copy only had
xzhtlEoI, which 500'd the article controller). Sandbox containers get
the accounts docker-cp'd in after creation.

## Cutover checklist (staging, then production)

1. Fresh `./devops sync-stg`, then apply content transforms:
   `python3 migrate-content.py content --write` (dry-run first).
2. First Kirby 5 boot migrates content (UUIDs written). Take a manual
   B2-independent backup before it.
3. Push transformed content only at the cutover moment; never push-stg
   onto the Kirby 3 staging site before that.
4. Verify with `./devops verify-stg`, then
   `python3 verify-vs-prod.py --local https://stg.jprconsultingllc.com --auth user:pass`
   against production.
5. Re-inject .env and .htpasswd after every container recreation.

## Notes

- Build locally only: `docker buildx build --platform linux/amd64 --load`,
  then `docker save IMAGE | ssh blt-pub02 docker load`. Never build on
  the host (2GB RAM). Always `unset DOCKER_DEFAULT_PLATFORM` first.
- The media route rejects HEAD; test with GET.
- Old droplet (DO-01) is a cold standby. Never start anything on it,
  especially its volumerize containers (two writers corrupt B2 chains).
