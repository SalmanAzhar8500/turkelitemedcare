# Icon exports still needed

`favicon.svg` is generated and referenced site-wide. Two raster exports are still
required — generate them from the SVG with any icon tool and drop them in this folder:

- `favicon.ico`      — 16, 32 and 48 px, for older browsers
- `apple-touch-icon.png` — 180x180 px, no transparency, for iOS home screens

Also export `assets/img/og/og-default.png` at 1200x630 for social sharing previews;
the head tags already point at it.
