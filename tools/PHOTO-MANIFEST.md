# Photography manifest

Hotlinking `images.unsplash.com` was removed from 0 pages. Unsplash's licence does not cover hotlinking their CDN, and it made every hero and tile depend on a third party staying up.

Each slot below now falls back to the local illustration in `assets/img/synthetic/` or `assets/img/v5/`. To restore photography:

1. Licence or commission an image for the slot (clinic photography of your own partners is stronger than stock for this sector, and removes the licensing question entirely).
2. Save it to `assets/img/photos/<slot-name>.webp`, plus a 2x variant.
3. Add it in front of the local illustration in the same CSS stack: `--tile-image:url('.../photos/x.webp'),url('.../synthetic/x.svg')` — the illustration stays as the fallback.

Consent note: never publish a photograph of a real patient, a real clinic interior or an identifiable clinician without written, documented consent for commercial use.

## Slots that previously used a remote photo

| Page | Original Unsplash photo id |
| --- | --- |