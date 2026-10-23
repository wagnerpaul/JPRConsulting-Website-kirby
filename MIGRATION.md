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
- [x] Rung 2: Kirby 4.9.6. Removed: commentions (unused), sanitizer
      (disabled), k3-image-clip (no clip boxes in content), kirby-colors
      and table-field (fields now core). bettersearch, link-field,
      code-editor moved to composer. Theme: clip() -> resize(),
      toImage/toImages alias toFile/toFiles, colors -> color + alpha,
      stale pages-section queries dropped, menu builder mobile column,
      sitemapper contentFile() calls patched (output byte-identical to
      blackletter's). Battery: 12 pages 0 diffs, blueprints clean,
      sitemap identical (12 locs, 26 image entries), Panel login renders
- [x] Rung 3: content transforms applied to local content (46 values);
      staging/prod volumes get the same transform at cutover
- [x] Rung 4: Kirby 5.6.1. bnomei plugins to 5.x, similar to composer
      3.0.1, link-field vendored at 6.0.2, crop:'true' -> boolean in 43
      thumb calls, both duplicate contact controllers emptied (Kirby 5
      merges site.php, which holds the same handler; would double-send).
      Battery: 12 pages, one known cosmetic diff (Kirby 5 excerpt keeps
      the straddling word; one extra word on /blog), blueprints clean,
      sitemap identical, Panel login renders
- [x] Rung 5: image on php:8.4-fpm-alpine (PHP 8.4.27). Final battery
      identical to rung 4: 12 pages with only the known excerpt diff,
      blueprints clean, sitemap identical, Panel login renders, no
      errors in container logs. Local image tag: k5local
- [x] Staging cutover (2026-10-23): volumes backed up locally
      (backups/stg-content-pre-k5-*.tgz, accounts) and via volumerize;
      transformed content pushed atomically; stg recreated on the
      Kirby 5 / PHP 8.4 image with .env/.htpasswd re-injected. Battery
      on stg: 12 pages vs production with only the known excerpt diff,
      blueprints clean, sitemap identical, Panel login renders, no
      errors in logs. :stg pushed to Docker Hub (7dd26a55)
- [x] Backup chain retention live: blackletter's compose postexecute
      hook ported, stg backup containers recreated, validated (manual
      backup pruned the Aug 22 chains, Errors 0)
- [x] Panel walkthrough on Kirby 5 staging (Paul, 2026-10-23)
- [x] Production promotion (2026-10-23): prod content volume backed up
      locally, staging content promoted (chown 82:82), prod recreated
      on the Kirby 5 image with .env/.htpasswd re-injected, cache
      cleared. Battery: stg vs prod 12 pages 0 differences, blueprints
      clean, sitemaps identical, /panel 404, logs clean. latest +
      20261023 on Docker Hub point at the Kirby 5 image (e93fc775);
      pre-K5 rollback image reachable by digest b6ab9f5c
- [x] Contact forms tested live (2026-10-23); transport is the
      office365 SMTP in .env. Postmark is now unused and cancelable
- [ ] Kirby license purchase: site is live on Kirby 5, purchase due now

## Final parity audit vs blackletter (2026-10-23)

Dockerfile, entrypoint, opcache, nginx conf, generate-site-css and
composer requires are identical; core is 5.6.1 in both. Plugin sets
match except kirby-image-url, which is jpr-specific and kept. No
leftover migration hazards (colors/clip/crop-string/commentions/
z-table/stale queries/contentFile all zero). jpr's devops is ahead of
blackletter's: promote re-injects .env/.htpasswd and unsets
DOCKER_DEFAULT_PLATFORM; consider porting those back.

## Local sandbox

```
docker run -d --name jpr-k5 -p 8082:80 \
  -e ENABLE_PANEL=true -e ENABLE_PW=false -e SERVER_NAME=localhost \
  -v "$PWD/content:/app/content" -v "$PWD/.env:/app/.env" \
  -v jpr-k5-media:/app/public/media \
  -v jpr-k5-cache:/app/storage/cache \
  -v jpr-k5-sessions:/app/storage/sessions \
  wagnerpaul/jprconsultingllc-main-app:k5local
docker cp storage/accounts/. jpr-k5:/app/storage/accounts/
python3 verify-vs-prod.py --local http://localhost:8082
docker exec jpr-k5 php /app/check-blueprints.php
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
