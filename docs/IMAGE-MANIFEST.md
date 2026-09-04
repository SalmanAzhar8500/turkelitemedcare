# FINAL — Image Manifest & Art Direction

## Art direction

- Bright daylight, premium but calm healthcare/hospitality environment.
- Warm whites, pale stone/wood and restrained Turkelite teal.
- Human images must show useful interaction: consultation, coordination, arrival, planning or recovery context.
- Technical treatment images use one clear object on a quiet white/pale-teal background.
- No dark blue hospital-stock aesthetic, generic crossed-arm doctors, aggressive saturation or repeated irrelevant imagery.

## FINAL local assets

- `public/assets/img/final/home-hero.webp` — international patient + coordinator / Istanbul context.
- `public/assets/img/final/coordination.webp` — journey/coordinator context.
- `public/assets/img/final/specialty-dental.webp` — dental consultation.
- `public/assets/img/final/specialty-hair-restoration.webp` — hair-restoration consultation.
- `public/assets/img/final/specialty-eye-care.webp` — ophthalmology consultation.
- `public/assets/img/final/specialty-*.webp` — all 10 specialty overviews have local FINAL visuals.
- `public/assets/img/final/procedure-*.webp` — all 20 priority procedures have local FINAL visuals.
- Dental library additionally uses crisp minimal SVG object illustrations where a dedicated FINAL bitmap is not required.
- `public/assets/img/final/clinic-1.webp` … `clinic-4.webp` — presentation clinic environments; replace with approved partner photography before public launch.

## Performance rules

- Hero images are eager-loaded / fetchpriority high.
- Below-fold imagery is lazy-loaded.
- FINAL WebP assets are locally packaged; no Unsplash dependency.
- Card images use fixed aspect ratios to prevent layout shift.
- Production should add AVIF variants and `srcset` once real partner media is approved.
