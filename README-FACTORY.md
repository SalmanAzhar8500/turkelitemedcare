# Turkelite Medcare FINAL — Reproducible Website Factory

FINAL is the presentation-candidate build of the V105 Clinical Navigator architecture. The database is **generated from structured content**, not manually populated page by page.

## Local identity
- Build: `FINAL`
- Default local URL: `http://127.0.0.1:8135`
- Local database: `turenewtheme_final_mgmt`
- Languages: English, German, Arabic (RTL)
- Visible local/staging badge: `FINAL-MGMT · LOCAL · :8135`
- Diagnostic: `http://127.0.0.1:8135/__build`

## Windows + MAMP first install
Start MAMP PRO/MySQL, open PowerShell in the project root, then run:

```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
.\INSTALL-WINDOWS.ps1
```

The installer creates/uses `turenewtheme_final_mgmt`, runs migrations, imports the canonical content pack, verifies relational integrity, generates the sitemap and prepares assets. **Do not present the site if the database verification step fails.**

## Present / run locally

```powershell
.\PRESENT-FINAL.ps1
```

or:

```powershell
.\START-WINDOWS.ps1
```

Presentation URLs:
- EN: `http://127.0.0.1:8135/`
- DE: `http://127.0.0.1:8135/de`
- AR: `http://127.0.0.1:8135/ar`
- Admin: `http://127.0.0.1:8135/admin`
- Build diagnostic: `http://127.0.0.1:8135/__build`

The build diagnostic must report **10 specialties, 100 procedures and 20 priority procedures**.

## Content factory
Canonical website content lives under `content/en/` (the folder name is historical; the schema contains EN/DE/AR translations where required).

Useful commands:

```powershell
php artisan site:factory:validate --content=content/en --strict
php artisan site:factory:audit
php artisan site:factory:verify-db
php artisan site:factory:import --content=content/en --prune
php artisan site:factory:export --content=content/en
```

The admin CMS is for post-import editing and operational work. It is **not** the preferred way to populate a new website from scratch.

## SEO/content policy
- 100 treatment topics remain available to users in English.
- 20 high-intent treatment guides have deep EN/DE/AR editorial content and are indexable.
- The remaining 80 treatment pages are `noindex,follow` until they receive equivalent editorial depth.
- Provider and illustrative patient-story records are `noindex` until real evidence, permissions and consent replace presentation data.

## Production
Production deployment is separate from MAMP/local development. Use the production environment template and deployment script; never run `migrate:fresh` against a live database containing enquiries or other operational records.

Before public launch:

```bash
php artisan site:factory:audit --production
```

Known launch gates are deliberately enforced: final legal review and real provider verification/permissions.

## Read next
- `00-START-HERE.txt`
- `README-FINAL-DESIGN.md`
- `docs/PAGE-BY-PAGE-RELEASE-AUDIT.md`
- `docs/BABAR-PRESENTATION-RUNBOOK.md`
- `docs/FINAL-PROOFREAD-AND-QA.md`
