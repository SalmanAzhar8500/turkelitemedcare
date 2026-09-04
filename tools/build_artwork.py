"""Generate the site's entire illustration system as self-hosted SVG.

ONE SYSTEM
----------
Every image on the site is built from the same four layers, in the same order,
with the same stroke language. Nothing is styled ad hoc:

    1. Ground       deep two-stop gradient in the family's colour
    2. Tessellation a Seljuk eight-fold girih field, hairlines only
    3. Light        one soft radial, always from the focal point
    4. Subject      a focal ring, the subject in white line, and exactly one
                    brass arc as the warm counterpoint

PALETTE     Brand navy and teal, plus a single brass accent. The brass hairline
            is what stops these reading as another all-blue clinical gradient.
            Every family - the ten specialties, services, guides, stories,
            clinics, people - takes a *chosen* colour from one table, held at
            similar depth and saturation, so 151 images read as one set.

SIGNATURE   The girih tessellation is authentic to Turkey without tipping into
            folkloric decoration, and what it communicates - precision, order,
            everything in its place - is the brand promise. Filled Iznik-style
            colour tiles were considered and rejected as too decorative for a
            medical context.

SUBJECTS    Specialty artwork uses the glyphs already in the site navigation, so
            the artwork and the menu speak one visual language. Service artwork
            uses composed scenes rather than a lone icon: coordination is drawn
            as linked nodes over a case card, travel as a flight path over a
            boarding pass. A single centred glyph left those large panels empty.

CROPPING    Each composition is authored for how the stylesheet actually crops
            and overlays that slot - tiles are centre-cropped under a bottom
            scrim, heroes sit at 62% under a left-to-right scrim, avatars are
            masked to a circle. No composition adds a scrim the CSS already draws.

Filenames match the slots already referenced in the HTML, so regenerating
upgrades all 259 pages without touching a single page.

Run from the site root:  python3 tools/build_artwork.py
"""
import os
import re
import sys
import math
import hashlib

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

OUT_SYN = os.path.join(cfg.ROOT, "assets", "img", "synthetic")
OUT_V5 = os.path.join(cfg.ROOT, "assets", "img", "v5")
OUT_VIDEO = os.path.join(cfg.ROOT, "assets", "img", "video")

WHITE = "#ffffff"
PAPER = "#eef7fa"
MINT = "#7be0ce"
BRASS = "#d9a05b"

# One table. Every family draws from here - nothing is randomised.
PALETTE = {
    "dental":            ("#0a4f48", "#0c5a52", "#1aa08a"),
    "hair-restoration":  ("#0c3f55", "#0f4a63", "#1c86ae"),
    "cosmetic-surgery":  ("#2b2a52", "#33325f", "#6a5c9e"),
    "bariatric-surgery": ("#09443a", "#0b4f43", "#17977a"),
    "eye-care":          ("#0d3059", "#103c6b", "#2470b8"),
    "orthopedics":       ("#183744", "#1d4152", "#35798e"),
    "ent":               ("#0a464c", "#0d5259", "#17939a"),
    "urology":           ("#1c2850", "#23305e", "#45579c"),
    "fertility":         ("#3d2443", "#4a2c50", "#96578c"),
    "general-surgery":   ("#123642", "#16414f", "#2b7a86"),
    "brand":   ("#0a2f42", "#11455c", "#17897f"),
    "service": ("#093a44", "#0e4d55", "#188b84"),
    "guide":   ("#10344b", "#15455f", "#2b7796"),
    "clinic":  ("#0a3346", "#0f4459", "#188089"),
    "people":  ("#0d3346", "#12455a", "#1a7d84"),
}

STROKE_MAIN = 3.0
STROKE_SUPPORT = 1.7
STROKE_HAIR = 1.2


def seed(name):
    return int(hashlib.md5(name.encode()).hexdigest()[:8], 16)


def svg(w, h, body, label):
    return (f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" '
            f'width="{w}" height="{h}" role="img" aria-label="{label}" fill="none">{body}</svg>')


def write(folder, name, content):
    with open(os.path.join(folder, name), "w", encoding="utf-8") as fh:
        fh.write(content)


# --------------------------------------------------------------- layers 1-3
def girih(pid, cell=64, op=0.13, w=STROKE_HAIR):
    """Seljuk eight-fold star tessellation. Vertices sit on the cell edge
    midpoints, so the linework runs unbroken across tile boundaries."""
    h, q = cell / 2.0, cell / 4.0
    return (f'<pattern id="{pid}" width="{cell}" height="{cell}" patternUnits="userSpaceOnUse">'
            f'<g fill="none" stroke="{WHITE}" stroke-opacity="{op}" stroke-width="{w}" '
            f'stroke-linejoin="round">'
            f'<path d="M{h} 0 L{cell} {h} L{h} {cell} L0 {h} Z"/>'
            f'<path d="M{q} {q} L{cell-q} {q} L{cell-q} {cell-q} L{q} {cell-q} Z"/>'
            f'<path d="M{h} 0 L{h} {q} M{cell} {h} L{cell-q} {h} M{h} {cell} L{h} {cell-q} M0 {h} L{q} {h}"/>'
            f'<path d="M0 0 L{q} {q} M{cell} 0 L{cell-q} {q} M{cell} {cell} L{cell-q} {cell-q} '
            f'M0 {cell} L{q} {cell-q}"/></g></pattern>')


def ground(w, h, key, family, fx, fy, cell=64, op=0.13, rot=-16, scrim=0.0):
    """Layers 1 to 3. `scrim` is used only where the stylesheet does not already
    lay one down - two stacked scrims is what washed the first attempt out."""
    a, b, c = PALETTE.get(family, PALETTE["brand"])
    out = ['<defs>',
           f'<linearGradient id="bg{key}" x1="0" y1="0" x2="1" y2="1">'
           f'<stop offset="0" stop-color="{a}"/><stop offset=".52" stop-color="{b}"/>'
           f'<stop offset="1" stop-color="{c}"/></linearGradient>',
           f'<radialGradient id="lg{key}" cx="{fx/w:.2f}" cy="{fy/h:.2f}" r=".70">'
           f'<stop offset="0" stop-color="{WHITE}" stop-opacity=".17"/>'
           f'<stop offset="1" stop-color="{WHITE}" stop-opacity="0"/></radialGradient>']
    if scrim:
        out.append(f'<linearGradient id="sc{key}" x1="0" y1="0" x2="0" y2="1">'
                   f'<stop offset="0" stop-color="{a}" stop-opacity="0"/>'
                   f'<stop offset="1" stop-color="{a}" stop-opacity=".80"/></linearGradient>')
    out.append(girih("gr" + key, cell=cell, op=op))
    out.append('</defs>')
    out.append(f'<rect width="{w}" height="{h}" fill="url(#bg{key})"/>')
    out.append(f'<g transform="rotate({rot} {w/2} {h/2})">'
               f'<rect x="{-w*0.4}" y="{-h*0.4}" width="{w*1.8}" height="{h*1.8}" '
               f'fill="url(#gr{key})"/></g>')
    out.append(f'<rect width="{w}" height="{h}" fill="url(#lg{key})"/>')
    tail = (f'<rect y="{h*(1-scrim)}" width="{w}" height="{h*scrim}" fill="url(#sc{key})"/>'
            if scrim else "")
    return "".join(out), tail


# ----------------------------------------------------------------- layer 4
def focal(cx, cy, r, inner=True):
    out = [f'<circle cx="{cx:.0f}" cy="{cy:.0f}" r="{r:.0f}" fill="{WHITE}" fill-opacity=".055"/>',
           f'<circle cx="{cx:.0f}" cy="{cy:.0f}" r="{r:.0f}" stroke="{WHITE}" '
           f'stroke-opacity=".18" stroke-width="1.6"/>']
    if inner:
        out.append(f'<circle cx="{cx:.0f}" cy="{cy:.0f}" r="{r*0.70:.0f}" stroke="{MINT}" '
                   f'stroke-opacity=".22" stroke-width="1.3"/>')
    return "".join(out)


def brass_arc(cx, cy, r, start=195, end=340, w=2.6, op=0.85):
    """The single warm counterpoint. Exactly one per composition."""
    x1, y1 = cx + r * math.cos(math.radians(start)), cy + r * math.sin(math.radians(start))
    x2, y2 = cx + r * math.cos(math.radians(end)), cy + r * math.sin(math.radians(end))
    large = 1 if (end - start) % 360 > 180 else 0
    return (f'<path d="M{x1:.1f} {y1:.1f} A{r:.0f} {r:.0f} 0 {large} 1 {x2:.1f} {y2:.1f}" '
            f'fill="none" stroke="{BRASS}" stroke-opacity="{op}" stroke-width="{w}" '
            f'stroke-linecap="round"/>')


def lines(content, stroke=WHITE, op=0.92, w=STROKE_MAIN, transform=""):
    t = f' transform="{transform}"' if transform else ""
    return (f'<g{t} fill="none" stroke="{stroke}" stroke-opacity="{op}" stroke-width="{w}" '
            f'stroke-linecap="round" stroke-linejoin="round">{content}</g>')


def rosette(cx, cy, r, points=8, op=0.60, w=1.9):
    """The generative seed of the girih ground, drawn large. Used for site-wide
    heroes, where one specialty glyph would wrongly imply the page is about that
    specialty."""
    out = []
    for k, rr in enumerate((r, r * 0.74, r * 0.48)):
        pts = []
        for i in range(points * 2):
            ang = math.pi * i / points - math.pi / 2
            rad = rr if i % 2 == 0 else rr * 0.62
            pts.append(f"{cx + rad*math.cos(ang):.1f} {cy + rad*math.sin(ang):.1f}")
        out.append(f'<path d="M{" L".join(pts)} Z" stroke="{WHITE}" '
                   f'stroke-opacity="{op*(1-k*0.22):.2f}" stroke-width="{w}" stroke-linejoin="round"/>')
    for i in range(points):
        ang = math.pi * 2 * i / points
        out.append(f'<path d="M{cx + r*0.48*math.cos(ang):.1f} {cy + r*0.48*math.sin(ang):.1f} '
                   f'L{cx + r*math.cos(ang):.1f} {cy + r*math.sin(ang):.1f}" stroke="{WHITE}" '
                   f'stroke-opacity="{op*0.5:.2f}" stroke-width="{w*0.7}"/>')
    return "".join(out)


# ------------------------------------------------------- specialty glyphs
def load_icons():
    src = cfg.read("index.html")
    mega = re.search(r'<div class="mega-grid">(.*?)</div></div>', src, re.S).group(1)
    out = {}
    for a in re.finditer(r'<a href="treatments/([a-z\-]+)/index\.html">(.*?)</a>', mega, re.S):
        inner = re.search(r"<svg[^>]*>(.*?)</svg>", a.group(2), re.S)
        if inner:
            out[a.group(1)] = inner.group(1)
    return out


ICONS = load_icons()


def glyph(slug, cx, cy, size, op=0.92, w=1.05):
    """Nav glyphs are drawn on a 24x24 grid; centre one at a target size."""
    inner = ICONS.get(slug, '<path d="M12 3v18M3 12h18"/>')
    return lines(inner, op=op, w=w,
                 transform=f"translate({cx - size/2:.1f} {cy - size/2:.1f}) scale({size/24.0:.3f})")


# ------------------------------------------------------- scene primitives
def card(x, y, w, h, rows=3, op=0.68):
    """A case-file card. Gives service and guide panels something to sit on."""
    out = [f'<rect x="{x:.0f}" y="{y:.0f}" width="{w:.0f}" height="{h:.0f}" rx="10" '
           f'fill="{WHITE}" fill-opacity=".05" stroke="{WHITE}" stroke-opacity="{op}" '
           f'stroke-width="{STROKE_SUPPORT}"/>']
    for i in range(rows):
        ry = y + h * (0.28 + i * 0.19)
        rw = w * (0.62 if i % 2 == 0 else 0.44)
        out.append(f'<path d="M{x + w*0.14:.0f} {ry:.0f} h{rw:.0f}" stroke="{WHITE}" '
                   f'stroke-opacity="{op*0.75:.2f}" stroke-width="{STROKE_SUPPORT}" '
                   f'stroke-linecap="round"/>')
    return "".join(out)


def node(cx, cy, r, op=0.92):
    return (f'<circle cx="{cx:.0f}" cy="{cy:.0f}" r="{r:.0f}" fill="{WHITE}" fill-opacity=".08" '
            f'stroke="{WHITE}" stroke-opacity="{op}" stroke-width="{STROKE_MAIN}"/>')


def rail(x1, x2, y, stops=5, done=2):
    out = [f'<path d="M{x1:.0f} {y:.0f} H{x2:.0f}" stroke="{WHITE}" stroke-opacity=".38" '
           f'stroke-width="{STROKE_SUPPORT}" stroke-linecap="round"/>']
    for i in range(stops):
        cx = x1 + (x2 - x1) * i / (stops - 1)
        filled = i <= done
        out.append(f'<circle cx="{cx:.0f}" cy="{y:.0f}" r="{11 if filled else 8}" '
                   f'fill="{MINT}" fill-opacity="{0.85 if filled else 0}" '
                   f'stroke="{WHITE}" stroke-opacity="{0.95 if filled else 0.5}" '
                   f'stroke-width="{STROKE_SUPPORT}"/>')
    return "".join(out)


def check_rows(x, y, w, n=4, gap=32):
    out = []
    for i in range(n):
        ry = y + i * gap
        out.append(f'<path d="M{x:.0f} {ry:.0f} l7 7 l13 -14" stroke="{MINT}" stroke-opacity=".95" '
                   f'stroke-width="{STROKE_SUPPORT+0.4}" fill="none" stroke-linecap="round" '
                   f'stroke-linejoin="round"/>')
        out.append(f'<path d="M{x+34:.0f} {ry+3:.0f} h{w*(0.9 if i%2==0 else 0.6):.0f}" '
                   f'stroke="{WHITE}" stroke-opacity=".42" stroke-width="{STROKE_SUPPORT}" '
                   f'stroke-linecap="round"/>')
    return "".join(out)


def scene(key, w, h):
    """Composed service scenes. A lone icon left these large panels looking empty,
    so each service is drawn as a small diagram of what it actually does."""
    cx, cy = w * 0.40, h * 0.48

    if key == "coordination":
        return (card(w * 0.56, h * 0.24, w * 0.30, h * 0.48, rows=4)
                + node(cx, cy - h * 0.17, 30) + node(cx - w * 0.14, cy + h * 0.15, 24)
                + node(cx + w * 0.12, cy + h * 0.15, 24)
                + lines(f'<path d="M{cx-21:.0f} {cy-h*0.17+21:.0f} L{cx-w*0.14+15:.0f} {cy+h*0.15-15:.0f}"/>'
                        f'<path d="M{cx+21:.0f} {cy-h*0.17+21:.0f} L{cx+w*0.12-15:.0f} {cy+h*0.15-15:.0f}"/>'
                        f'<path d="M{cx-w*0.14+25:.0f} {cy+h*0.15:.0f} H{cx+w*0.12-25:.0f}"/>',
                        op=0.55, w=STROKE_SUPPORT))

    if key == "journey":
        return (card(w * 0.58, h * 0.20, w * 0.28, h * 0.40, rows=3)
                + rail(w * 0.10, w * 0.70, h * 0.74, stops=5, done=2)
                + lines('<path d="M0 0 v-28"/>', op=0.5, w=STROKE_SUPPORT,
                        transform=f"translate({w*0.40:.0f} {h*0.74-14:.0f})")
                + glyph("general-surgery", w * 0.30, h * 0.38, h * 0.30, op=0.60, w=1.0))

    if key == "travel":
        return (card(w * 0.54, h * 0.48, w * 0.32, h * 0.28, rows=2)
                + lines(f'<path d="M{w*0.09:.0f} {h*0.62:.0f} q{w*0.18:.0f} -{h*0.42:.0f} '
                        f'{w*0.42:.0f} -{h*0.22:.0f}" stroke-dasharray="9 9"/>',
                        op=0.45, w=STROKE_SUPPORT)
                + lines('<path d="M-30 9 L30 -9 M5 0 L16 -21 M-9 5 L-16 -12 M-18 16 L-7 7"/>',
                        op=0.95, w=STROKE_MAIN,
                        transform=f"translate({w*0.51:.0f} {h*0.38:.0f}) scale(1.6)")
                + node(w * 0.09, h * 0.62, 13))

    if key == "transfers":
        return (lines(f'<path d="M{w*0.09:.0f} {h*0.76:.0f} q{w*0.22:.0f} -{h*0.30:.0f} '
                      f'{w*0.46:.0f} -{h*0.12:.0f}" stroke-dasharray="8 10"/>',
                      op=0.42, w=STROKE_SUPPORT)
                + lines('<rect x="-52" y="-20" width="104" height="34" rx="9"/>'
                        '<path d="M-36 -20 l10 -16 h52 l10 16"/>'
                        '<circle cx="-28" cy="18" r="11"/><circle cx="28" cy="18" r="11"/>',
                        op=0.95, w=STROKE_MAIN,
                        transform=f"translate({w*0.38:.0f} {h*0.46:.0f}) scale(1.4)")
                + lines('<path d="M0 0 c-16 -20 -12 -34 0 -34 c12 0 16 14 0 34z"/>'
                        '<circle cx="0" cy="-20" r="6"/>', op=0.80, w=STROKE_SUPPORT,
                        transform=f"translate({w*0.72:.0f} {h*0.32:.0f}) scale(1.6)"))

    if key == "accommodation":
        return (card(w * 0.60, h * 0.28, w * 0.28, h * 0.34, rows=2)
                + lines('<path d="M-70 -6 L0 -56 L70 -6"/><path d="M-56 -6 V56 H56 V-6"/>'
                        '<rect x="-34" y="6" width="26" height="26" rx="4"/>'
                        '<rect x="10" y="6" width="26" height="26" rx="4"/>'
                        '<path d="M-12 56 V32 h24 v24"/>', op=0.95, w=STROKE_MAIN,
                        transform=f"translate({w*0.32:.0f} {h*0.50:.0f}) scale(1.45)"))

    if key == "language":
        return (lines('<rect x="-80" y="-54" width="126" height="84" rx="16"/>'
                      '<path d="M-48 30 l-7 28 l32 -28"/>', op=0.90, w=STROKE_MAIN,
                      transform=f"translate({w*0.33:.0f} {h*0.38:.0f}) scale(1.05)")
                + lines('<rect x="-42" y="-36" width="118" height="80" rx="16"/>'
                        '<path d="M48 44 l9 26 l-32 -26"/>', op=0.55, w=STROKE_SUPPORT,
                        transform=f"translate({w*0.56:.0f} {h*0.62:.0f}) scale(1.05)")
                + f'<text x="{w*0.29:.0f}" y="{h*0.42:.0f}" fill="{WHITE}" fill-opacity=".88" '
                  f'font-family="Manrope,Segoe UI,sans-serif" font-size="34" font-weight="800">DE</text>'
                + f'<text x="{w*0.575:.0f}" y="{h*0.66:.0f}" fill="{MINT}" fill-opacity=".88" '
                  f'font-family="Manrope,Segoe UI,sans-serif" font-size="30" font-weight="800">EN</text>')

    if key == "aftercare":
        return (lines('<rect x="-62" y="-76" width="124" height="154" rx="12"/>'
                      '<rect x="-25" y="-90" width="50" height="27" rx="7"/>',
                      op=0.90, w=STROKE_MAIN,
                      transform=f"translate({w*0.31:.0f} {h*0.50:.0f}) scale(1.1)")
                + check_rows(w * 0.31 - 48, h * 0.50 - 44, w * 0.085, n=4, gap=32)
                + lines('<path d="M0 34 s-30 -18 -30 -40 a15 15 0 0 1 30 -6 a15 15 0 0 1 30 6 '
                        'c0 22 -30 40 -30 40z"/><path d="M-18 -6 h10 l6 -12 l8 24 l6 -12 h10"/>',
                        op=0.85, w=STROKE_SUPPORT + 0.3,
                        transform=f"translate({w*0.68:.0f} {h*0.44:.0f}) scale(1.25)"))

    return card(w * 0.34, h * 0.28, w * 0.32, h * 0.44, rows=4)


CITY_SKYLINE = {
    "istanbul": ('<path d="M60 300 L60 210 M120 300 L120 190 M90 300 L90 150"/>'
                 '<path d="M40 300 q50 -78 100 -78 q50 0 100 78"/>'
                 '<path d="M240 300 q60 -120 130 0"/><path d="M240 300 L370 300"/>'
                 '<path d="M262 300 L262 258 M292 300 L292 240 M322 300 L322 240 M352 300 L352 258"/>'
                 '<path d="M370 300 q70 -96 150 -30"/>'),
    "antalya": ('<circle cx="150" cy="150" r="46"/>'
                '<path d="M150 74 L150 56 M150 244 L150 262 M74 150 L56 150 M244 150 L262 150"/>'
                '<path d="M40 300 q60 -46 130 -30 q70 16 140 -22 q60 -32 120 -6"/>'
                '<path d="M300 300 L300 232 q0 -34 34 -34"/>'
                '<path d="M334 198 q26 -8 40 12 M334 198 q-2 -28 22 -38"/>'),
    "izmir": ('<path d="M40 300 q80 -30 160 0 q80 30 160 0 q60 -22 120 0"/>'
              '<path d="M110 300 L110 176 L180 130 L250 176 L250 300"/>'
              '<path d="M140 300 L140 236 L220 236 L220 300"/>'
              '<path d="M300 300 L300 200 M340 300 L340 168 M380 300 L380 214"/>'
              '<circle cx="340" cy="148" r="10"/>'),
}


# ---------------------------------------------------------------- builders
def build_tile(slug, label, name, w=800, h=560):
    """Tiles render ~296x235, centre-cropped, under a bottom scrim drawn by
    .treatment-tile:before - so this artwork adds no scrim and centres its motif."""
    k = "t" + re.sub(r"[^a-z0-9]", "", slug)
    s = seed(name)
    cx, cy = w * 0.5, h * 0.46
    g, _ = ground(w, h, k, slug, w * 0.68, h * 0.26, cell=54 + (s % 3) * 10,
                  op=0.15, rot=-16 + (s % 5) * 8)
    return svg(w, h, g + focal(cx, cy, h * 0.40, inner=False)
               + glyph(slug, cx, cy, h * 0.52, op=0.95)
               + brass_arc(cx, cy, h * 0.40, 195 + (s % 40), 340 + (s % 30), w=2.4), label)


def build_hero(name, label, w=1900, h=900):
    """Hero slides sit under a left-to-right scrim and are positioned at 62%
    across, so the focal weight sits right of centre and no scrim is added."""
    k = "h" + re.sub(r"[^a-z0-9]", "", name)
    s = seed(name)
    fx, fy = w * 0.63, h * 0.46
    g, _ = ground(w, h, k, "brand", fx, fy, cell=88, op=0.16, rot=-12 + (s % 3) * 9)
    return svg(w, h, g + focal(fx, fy, h * 0.44)
               + rosette(fx, fy, h * 0.37)
               + f'<circle cx="{fx:.0f}" cy="{fy:.0f}" r="{h*0.13:.0f}" stroke="{PAPER}" '
                 f'stroke-opacity=".38" stroke-width="1.4"/>'
               + brass_arc(fx, fy, h * 0.44, 188, 352, w=3.4, op=0.9), label)


def build_clinic(name, city, label, w=900, h=470):
    """Clinic cards crop to a short landscape band, so the silhouette sits on the
    vertical centre line rather than low in the frame."""
    k = "c" + re.sub(r"[^a-z0-9]", "", name)
    s = seed(name)
    g, _ = ground(w, h, k, "clinic", w * 0.76, h * 0.40, cell=60, op=0.13, rot=-14 + (s % 4) * 8)
    art = CITY_SKYLINE.get(city, CITY_SKYLINE["istanbul"])
    return svg(w, h, g
               + f'<circle cx="{w*0.76:.0f}" cy="{h*0.40:.0f}" r="{h*0.44:.0f}" fill="{MINT}" '
                 f'fill-opacity=".07"/>'
               + lines(art, stroke=PAPER, op=0.80, w=2.4,
                       transform=f"translate({w*0.11:.0f} {h*0.50-186:.0f}) scale(1.24)")
               + brass_arc(w * 0.76, h * 0.40, h * 0.44, 168, 320, w=2.4), label)


def build_portrait(name, initials, label, w=800, h=800):
    """Masked to a circle in the avatar context, so the composition is square and
    centred and the initials sit well inside the inscribed circle.

    Deliberately abstract: a photograph needs the clinician's documented consent,
    and an invented face would misrepresent a person who does not exist."""
    k = "p" + re.sub(r"[^a-z0-9]", "", name)
    s = seed(name)
    cx, cy = w * 0.5, h * 0.44
    g, _ = ground(w, h, k, "people", cx, cy, cell=44, op=0.14, rot=-20 + (s % 4) * 10)
    return svg(w, h, g
               + f'<circle cx="{cx:.0f}" cy="{cy:.0f}" r="{w*0.30:.0f}" fill="{MINT}" fill-opacity=".08"/>'
               + lines(f'<circle cx="{cx:.0f}" cy="{cy - h*0.075:.0f}" r="{w*0.125:.0f}"/>'
                       f'<path d="M{cx - w*0.225:.0f} {h*0.665:.0f} q0 -{h*0.155:.0f} '
                       f'{w*0.225:.0f} -{h*0.155:.0f} q{w*0.225:.0f} 0 {w*0.225:.0f} {h*0.155:.0f}"/>',
                       stroke=PAPER, op=0.80, w=3.2)
               + brass_arc(cx, cy, w * 0.30, 200, 340, w=2.4, op=0.70)
               + f'<text x="{cx:.0f}" y="{h*0.795:.0f}" text-anchor="middle" fill="{PAPER}" '
                 f'fill-opacity=".62" font-family="Manrope,Segoe UI,Helvetica,sans-serif" '
                 f'font-size="{w*0.085:.0f}" font-weight="800" letter-spacing="4">{initials}</text>', label)


def build_service(name, key, label, w=820, h=660):
    """Service panels render at roughly 585x475 with no crop, so they carry a
    composed scene rather than a single centred glyph.

    The canvas is authored close to display size on purpose. At 1200x900 the art
    was downscaled to about half, which thinned every 3px stroke to 1.5px and made
    the panels look faint and empty."""
    k = "s" + re.sub(r"[^a-z0-9]", "", name)
    s = seed(name)
    g, _ = ground(w, h, k, "service", w * 0.62, h * 0.30, cell=46, op=0.15, rot=-15 + (s % 4) * 9)
    return svg(w, h, g + focal(w * 0.48, h * 0.48, h * 0.43, inner=False)
               + scene(key, w, h)
               + brass_arc(w * 0.48, h * 0.48, h * 0.43, 185 + (s % 30), 335 + (s % 25), w=2.6), label)


def build_procedure(specialty, slug, label, w=1400, h=560):
    """Procedure heroes are full-bleed behind left-aligned text under a 90deg
    scrim that clears around 76% across, so the motif sits right of centre."""
    k = "pr" + re.sub(r"[^a-z0-9]", "", specialty + slug)[:24]
    s = seed(specialty + slug)
    fx, fy = w * (0.74 + (s % 3) * 0.03), h * (0.46 + (s % 3) * 0.03)
    g, _ = ground(w, h, k, specialty, fx, fy, cell=64 + (s % 4) * 10, op=0.13, rot=-22 + (s % 7) * 8)
    return svg(w, h, g + focal(fx, fy, h * 0.40, inner=False)
               + glyph(specialty, fx, fy, h * 0.47, op=0.90)
               + brass_arc(fx, fy, h * 0.40, 190 + (s % 50), 335 + (s % 25), w=2.6), label)



def build_video_thumb(specialty, slug, label, w=1280, h=720):
    """A dedicated 16:9 poster frame for the procedure explainer modules.

    These slots previously reused the procedure hero image, so the video block
    looked like a second copy of the banner directly above it and nothing about
    it said "video". This adds the three cues a poster frame needs — a play
    control, a scrubber and a duration chip — over the same girih ground, so it
    reads as a video still without pretending to be footage that does not exist.
    """
    k = "vt" + re.sub(r"[^a-z0-9]", "", specialty + slug)[:22]
    s = seed("video" + specialty + slug)
    # the caption band occupies the bottom third, so the play control sits above it
    cx, cy = w * 0.50, h * 0.38
    g, _ = ground(w, h, k, specialty, cx, cy * 0.8, cell=58 + (s % 3) * 8,
                  op=0.12, rot=-18 + (s % 6) * 7)

    body = g
    # the specialty glyph sits back, so it reads as subject matter not as the subject
    body += glyph(specialty, w * 0.18, cy, h * 0.42, op=0.18, w=1.6)
    body += glyph(specialty, w * 0.83, cy * 1.15, h * 0.30, op=0.13, w=1.6)

    # play control
    r = h * 0.135
    body += f'<circle cx="{cx:.0f}" cy="{cy:.0f}" r="{r*1.42:.0f}" fill="{WHITE}" fill-opacity=".08"/>'
    body += (f'<circle cx="{cx:.0f}" cy="{cy:.0f}" r="{r:.0f}" fill="{WHITE}" fill-opacity=".93"/>')
    tri = r * 0.46
    body += (f'<path d="M{cx - tri*0.62:.0f} {cy - tri:.0f} L{cx + tri:.0f} {cy:.0f} '
             f'L{cx - tri*0.62:.0f} {cy + tri:.0f} Z" fill="{PALETTE.get(specialty, PALETTE["brand"])[0]}"/>')
    body += brass_arc(cx, cy, r * 1.42, 200, 340, w=3.0, op=0.9)

    # scrubber and duration chip - the cues that make a still read as a video
    bar_y = h * 0.855
    body += (f'<path d="M{w*0.07:.0f} {bar_y:.0f} H{w*0.93:.0f}" stroke="{WHITE}" '
             f'stroke-opacity=".28" stroke-width="5" stroke-linecap="round"/>')
    body += (f'<path d="M{w*0.07:.0f} {bar_y:.0f} H{w*0.07 + (w*0.86)*0.28:.0f}" stroke="{MINT}" '
             f'stroke-opacity=".95" stroke-width="5" stroke-linecap="round"/>')
    body += (f'<circle cx="{w*0.07 + (w*0.86)*0.28:.0f}" cy="{bar_y:.0f}" r="8" fill="{WHITE}"/>')
    body += (f'<rect x="{w*0.855:.0f}" y="{h*0.075:.0f}" width="{w*0.088:.0f}" height="{h*0.062:.0f}" '
             f'rx="{h*0.031:.0f}" fill="#000000" fill-opacity=".38"/>')
    body += (f'<text x="{w*0.899:.0f}" y="{h*0.119:.0f}" text-anchor="middle" fill="{WHITE}" '
             f'fill-opacity=".92" font-family="Manrope,Segoe UI,sans-serif" font-size="{h*0.036:.0f}" '
             f'font-weight="750">3:00</text>')
    body += (f'<text x="{w*0.07:.0f}" y="{h*0.115:.0f}" fill="{MINT}" fill-opacity=".88" '
             f'font-family="Manrope,Segoe UI,sans-serif" font-size="{h*0.032:.0f}" '
             f'font-weight="800" letter-spacing="3">PROCEDURE EXPLAINER</text>')
    return svg(w, h, body, "Video explainer: " + label)


def build_editorial(name, family, label, w=1100, h=680, subject=None, scrim=0.38):
    """Guides, facilities and the business-model diagram, on the same four layers."""
    k = "e" + re.sub(r"[^a-z0-9]", "", name)
    s = seed(name)
    g, sc = ground(w, h, k, family, w * 0.66, h * 0.30, cell=58, op=0.13,
                   rot=-14 + (s % 5) * 8, scrim=scrim)
    body = g + focal(w * 0.46, h * 0.46, h * 0.42, inner=False)
    body += subject if subject else card(w * 0.34, h * 0.26, w * 0.32, h * 0.44, rows=4)
    body += brass_arc(w * 0.46, h * 0.46, h * 0.42, 180 + (s % 40), 330 + (s % 25), w=2.5)
    return svg(w, h, body + sc, label)


def guide_subject(kind, w, h):
    """Guide cards crop to roughly 370x105 - a wide, shallow band. Subjects are
    laid out horizontally across the centre line; the first attempt used a tall
    portrait composition and the card sliced the top off every one of them."""
    cy = h * 0.50
    if kind == "clinic":
        return (lines('<path d="M-56 34 L0 -14 L56 34"/><path d="M-42 34 V0"/>'
                      '<path d="M42 34 V0"/><path d="M-13 34 V10 h26 v24"/>',
                      op=0.92, w=STROKE_MAIN,
                      transform=f"translate({w*0.26:.0f} {cy:.0f}) scale(1.05)")
                + card(w * 0.44, cy - h * 0.30, w * 0.16, h * 0.60, rows=3)
                + check_rows(w * 0.66, cy - h * 0.22, w * 0.14, n=3, gap=h * 0.22))
    if kind == "consent":
        return (lines('<rect x="-46" y="-42" width="92" height="84" rx="9"/>',
                      op=0.90, w=STROKE_MAIN,
                      transform=f"translate({w*0.26:.0f} {cy:.0f})")
                + check_rows(w * 0.22, cy - h * 0.22, w * 0.03, n=3, gap=h * 0.22)
                + card(w * 0.42, cy - h * 0.30, w * 0.18, h * 0.60, rows=3)
                + lines('<circle cx="0" cy="0" r="26"/><path d="M0 12 v-3 q0 -7 6 -10 q6 -4 6 -11 '
                        'q0 -9 -12 -9 q-10 0 -12 9"/><circle cx="0" cy="22" r="2.4"/>',
                        op=0.80, w=STROKE_SUPPORT,
                        transform=f"translate({w*0.72:.0f} {cy:.0f}) scale(1.05)"))
    return (lines('<rect x="-62" y="-40" width="124" height="80" rx="12"/>'
                  '<path d="M-14 -18 L26 0 L-14 18z"/>', op=0.92, w=STROKE_MAIN,
                  transform=f"translate({w*0.27:.0f} {cy:.0f})")
            + card(w * 0.45, cy - h * 0.30, w * 0.17, h * 0.60, rows=3)
            + card(w * 0.68, cy - h * 0.30, w * 0.17, h * 0.60, rows=3))


def facility_subject(kind, w, h):
    art = {
        "reception": '<path d="M-96 62 H96"/><path d="M-72 62 V-16 H72 V62"/>'
                     '<path d="M-34 62 V22 h26 v40"/><path d="M18 22 h40"/><path d="M18 44 h40"/>',
        "consultation": '<circle cx="-46" cy="-26" r="24"/>'
                        '<path d="M-90 58 q0 -42 44 -42 q44 0 44 42"/>'
                        '<rect x="24" y="-40" width="66" height="98" rx="10"/>'
                        '<path d="M40 -12 h34 M40 10 h34 M40 32 h20"/>',
        "procedure": '<path d="M-92 34 H92 v22 H-92z"/><path d="M-60 34 V-4 h120 v38"/>'
                     '<circle cx="0" cy="-56" r="22"/><path d="M0 -34 v22"/>',
    }[kind]
    return lines(art, op=0.92, w=STROKE_MAIN,
                 transform=f"translate({w*0.44:.0f} {h*0.46:.0f}) scale(1.2)")


# -------------------------------------------------------------------- maps
TILES = {
    "tile-dental.svg": ("dental", "Dental care"),
    "tile-hair.svg": ("hair-restoration", "Hair restoration"),
    "tile-cosmetic.svg": ("cosmetic-surgery", "Plastic and cosmetic surgery"),
    "tile-bariatric.svg": ("bariatric-surgery", "Bariatric and weight-loss surgery"),
    "tile-eye.svg": ("eye-care", "Eye care"),
    "tile-orthopedics.svg": ("orthopedics", "Orthopedics"),
    "tile-ent.svg": ("ent", "Ear, nose and throat care"),
    "tile-urology.svg": ("urology", "Urology"),
    "tile-fertility.svg": ("fertility", "Fertility and reproductive medicine"),
    "tile-surgery.svg": ("general-surgery", "General surgery"),
}

HEROES = {
    "hero-understand.svg": "Understand and compare your care",
    "hero-compare.svg": "Start with the condition",
    "hero-coordinate.svg": "Clinic, doctor, travel and follow-up in one place",
    "page-hero-bg.svg": "Turkelitemedcare",
    "testimonial-bg.svg": "Patient journey examples",
}

CLINICS = {
    "clinic-marmara.svg": ("istanbul", "Marmara Dental and Aesthetic Institute"),
    "clinic-bosporus.svg": ("istanbul", "Bosporus Medical Center"),
    "clinic-anatolia.svg": ("antalya", "Anatolia Specialist Hospital"),
    "clinic-mediterranean.svg": ("antalya", "Mediterranean Surgical Center"),
    "clinic-aegean.svg": ("izmir", "Aegean Care Institute"),
    "clinic-izmir.svg": ("izmir", "Izmir Vision and Reproductive Institute"),
}

DOCTORS = {
    "doctor-emre-kaya.svg": "EK", "doctor-selin-arslan.svg": "SA",
    "doctor-deniz-yilmaz.svg": "DY", "doctor-mert-tekin.svg": "MT",
    "doctor-ayse-demir.svg": "AD", "doctor-baris-acar.svg": "BA",
    "doctor-ceren-ozkan.svg": "CO", "doctor-elif-koc.svg": "EK",
    "doctor-eren-savas.svg": "ES", "doctor-selin-arslan-2.svg": "SA",
}

SERVICES = {
    "service-coordination.svg": ("coordination", "Treatment coordination"),
    "service-journey.svg": ("journey", "Your treatment journey"),
    "service-travel.svg": ("travel", "Travel planning"),
    "service-transfers.svg": ("transfers", "Airport and clinic transfers"),
    "service-accommodation.svg": ("accommodation", "Accommodation"),
    "service-language.svg": ("language", "German and English language support"),
    "service-aftercare.svg": ("aftercare", "Aftercare and follow-up"),
}

STORIES = {
    "story-dental.svg": ("dental", "Dental journey"),
    "story-hair.svg": ("hair-restoration", "Hair restoration journey"),
    "story-cosmetic.svg": ("cosmetic-surgery", "Cosmetic surgery journey"),
    "story-bariatric.svg": ("bariatric-surgery", "Bariatric journey"),
    "story-eye.svg": ("eye-care", "Eye care journey"),
    "story-orthopedics.svg": ("orthopedics", "Orthopedic journey"),
}

GUIDES = {
    "guide-clinic.svg": ("clinic", "Choosing a clinic"),
    "guide-consent.svg": ("consent", "Questions before consent"),
    "guide-videos.svg": ("videos", "Procedure video library"),
}

FACILITIES = {
    "facility-reception.svg": ("reception", "Clinic reception"),
    "facility-consultation.svg": ("consultation", "Consultation room"),
    "facility-procedure.svg": ("procedure", "Procedure room"),
}


def main():
    os.makedirs(OUT_SYN, exist_ok=True)
    os.makedirs(OUT_V5, exist_ok=True)
    os.makedirs(OUT_VIDEO, exist_ok=True)
    n = 0

    for fn, (slug, label) in TILES.items():
        write(OUT_SYN, fn, build_tile(slug, label, fn)); n += 1
    for fn, label in HEROES.items():
        write(OUT_SYN, fn, build_hero(fn, label)); n += 1
    for fn, (city, label) in CLINICS.items():
        write(OUT_SYN, fn, build_clinic(fn, city, label)); n += 1
    for fn, initials in DOCTORS.items():
        label = fn.replace("doctor-", "").replace(".svg", "").replace("-", " ").title()
        write(OUT_SYN, fn, build_portrait(fn, initials, label)); n += 1
    for fn, (key, label) in SERVICES.items():
        write(OUT_SYN, fn, build_service(fn, key, label)); n += 1
    for fn, (slug, label) in STORIES.items():
        write(OUT_SYN, fn, build_procedure(slug, fn, label, 1100, 620)); n += 1
    for fn, (kind, label) in GUIDES.items():
        write(OUT_SYN, fn, build_editorial(fn, "guide", label, 1120, 340,
                                           subject=guide_subject(kind, 1120, 340),
                                           scrim=0.0)); n += 1
    for fn, (kind, label) in FACILITIES.items():
        write(OUT_SYN, fn, build_editorial(fn, "clinic", label, 1100, 720,
                                           subject=facility_subject(kind, 1100, 720))); n += 1

    write(OUT_SYN, "business-model-flow.svg", build_editorial(
        "business-model-flow", "brand", "Patient, Turkelitemedcare and partner clinic",
        1200, 640, subject=scene("coordination", 1200, 640), scrim=0.20)); n += 1

    for rel in cfg.html_files():
        if cfg.page_kind(rel) != "procedure":
            continue
        spec = cfg.specialty_of(rel)
        slug = os.path.basename(rel)[:-5]
        label = cfg.get_h1(cfg.read(rel)) or slug.replace("-", " ")
        write(OUT_V5, "procedure-%s-%s.svg" % (spec, slug),
              build_procedure(spec, slug, label)); n += 1
        write(OUT_VIDEO, "thumb-%s-%s.svg" % (spec, slug),
              build_video_thumb(spec, slug, label)); n += 1

    print("build_artwork: %d SVGs written across one unified system" % n)


if __name__ == "__main__":
    main()
