"""Fix image paths that never resolved.

The bug
-------
Image URLs are passed from inline `style` attributes into CSS custom properties
(`--tile-image`, `--bg`, `--doctor-image` and friends), then consumed by rules in
`assets/css/*.css`. A relative `url()` inside a custom property is resolved against
the stylesheet that *uses* it, not the document that declares it. So

    style="--tile-image:url('assets/img/synthetic/tile-dental.svg')"

was resolving to `assets/css/assets/img/synthetic/tile-dental.svg` and 404ing on
every page. This predates the V7 work: the remote Unsplash URLs were absolute so
they loaded fine, which masked the fact that the local fallback layer behind them
had never rendered once.

The fix
-------
Rewrite every image URL inside a custom property to be relative to the stylesheet:
`../img/...`. Because all three stylesheets live in `assets/css/`, one form is
correct for every page regardless of directory depth — which also makes it immune
to the depth-prefix mistakes that relative paths invite.

Inline styles that set `background-image` directly are document-relative and are
left alone; they were already correct.

Run from the site root:  python3 tools/patch_image_paths.py
"""
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

# --name:url('<any ../ prefix>assets/img/rest')   ->   --name:url('../img/rest')
PATTERN = re.compile(r"(--[a-z-]+\s*:\s*url\(')(?:\.\./)*assets/img/([^']+)('\))")


# A second defect, introduced when the hotlinked photography was stripped out.
#
# Procedure and specialty heroes declare two slots:
#     --procedure-photo:url('https://images.unsplash.com/...');
#     --procedure-fallback:url('../img/v5/....svg')
# and the stylesheet composes them as
#     background-image: linear-gradient(...), var(--procedure-photo), var(--procedure-fallback)
#
# Removing the remote URL left `--procedure-photo:` empty. An empty custom
# property makes the *entire* background-image declaration invalid at computed-value
# time — so the gradient and the fallback both vanish too, and 110 hero panels
# rendered white with white headline text on them.
#
# The photo slot is filled with the illustration instead of being left empty.
# Dropping licensed photography in later is then a single value swap, and the
# layer order still means the photograph wins when one is present.
EMPTY_SLOT = re.compile(
    r'(--(?P<kind>[a-z-]+)-photo\s*:\s*)'
    r'(?=[;"])'
    r'(?P<rest>[^"]*?--(?P=kind)-fallback\s*:\s*(?P<url>url\(\'[^\']+\'\)))'
)


def fill_empty_photo_slots():
    filled = 0
    pages = 0
    for rel in cfg.html_files():
        src = cfg.read(rel)
        new, n = EMPTY_SLOT.subn(lambda m: m.group(1) + m.group("url") + m.group("rest"), src)
        if n:
            cfg.write(rel, new)
            filled += n
            pages += 1
    print("patch_image_paths: %d empty photo slots filled across %d pages" % (filled, pages))


def main():
    changed = 0
    fixed = 0
    for rel in cfg.html_files():
        src = cfg.read(rel)
        new, n = PATTERN.subn(lambda m: m.group(1) + "../img/" + m.group(2) + m.group(3), src)
        if n:
            cfg.write(rel, new)
            changed += 1
            fixed += n
    print("patch_image_paths: %d URLs corrected across %d pages" % (fixed, changed))
    fill_empty_photo_slots()


if __name__ == "__main__":
    main()
