#!/usr/bin/env bash
# Full V7 pipeline. Order matters — see the notes against each step.
set -e
cd "$(dirname "$0")/.."

python3 tools/patch_procedure_content.py   # writes content-status.json (patch_head reads it)
python3 tools/patch_dedupe.py              # repeated blocks -> how-it-works
python3 tools/patch_forms_and_legal.py     # forms, consent, legal document set
python3 tools/patch_contact_and_cta.py     # contact details, WhatsApp, callback, overflow fix
python3 tools/patch_trust_and_de.py        # trust gating + complications page
python3 tools/patch_german.py              # German entry point (creates de/index.html)
python3 tools/patch_a11y_trust.py          # a11y pass — must run AFTER all pages exist
python3 tools/patch_image_paths.py         # stylesheet-relative URLs, empty photo slots
python3 tools/build_artwork.py             # regenerate the illustration system
python3 tools/patch_video_thumbs.py        # wire the 16:9 poster frames (after artwork)
python3 tools/patch_infra.py               # scripts, robots, manifest, icons, sitemap
python3 tools/patch_head.py                # canonical, social, schema — always last
python3 tools/patch_infra.py               # sitemap again, now that noindex flags are final
python3 tools/patch_german.py --wire-only  # re-assert switcher + hreflang; must NOT rebuild the page
python3 tools/qa_v7.py
