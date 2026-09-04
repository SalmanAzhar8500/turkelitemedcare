"""Shared configuration and helpers for the V7 patch scripts.

Edit the constants below once; every patch script reads from here.
"""
import os
import re
import html
import json

# ---------------------------------------------------------------- site config
BASE_URL = "https://www.turkelitemedcare.com"      # <-- set the real production domain
ORG_NAME = "Turkelitemedcare"
ORG_LEGAL_NAME = "Turkelitemedcare Medical Travel Coordination"
ORG_EMAIL = "info@turkelitemedcare.com"
# Bundesnetzagentur reserves 030 23125 xxx for fiction and demonstration, so a
# demo build cannot ring a real subscriber by accident. Replace before launch.
ORG_PHONE = "+49 30 23125 400"
ORG_LOGO = "/assets/img/turkelitemedcare-logo.svg"
DEFAULT_OG_IMAGE = "/assets/img/og/og-default.png"
CONTENT_REVIEW_STATUS = "Awaiting clinical review"   # shown in the review byline
SITE_LANG = "en"

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

SPECIALTY_LABELS = {
    "dental": "Dental",
    "hair-restoration": "Hair Restoration",
    "cosmetic-surgery": "Plastic & Cosmetic Surgery",
    "bariatric-surgery": "Bariatric & Weight Loss",
    "eye-care": "Eye Care",
    "orthopedics": "Orthopedics",
    "ent": "ENT",
    "urology": "Urology",
    "fertility": "Fertility & Reproductive Medicine",
    "general-surgery": "General Surgery",
}


# ------------------------------------------------------------------- helpers
def html_files(root=None):
    """Every .html file in the site, as paths relative to the site root."""
    root = root or ROOT
    out = []
    for dirpath, dirnames, filenames in os.walk(root):
        dirnames[:] = [d for d in dirnames if d not in ("tools", "assets")]
        for fn in sorted(filenames):
            if fn.endswith(".html"):
                out.append(os.path.relpath(os.path.join(dirpath, fn), root).replace("\\", "/"))
    return sorted(out)


def read(rel):
    with open(os.path.join(ROOT, rel), encoding="utf-8") as fh:
        return fh.read()


def write(rel, content):
    path = os.path.join(ROOT, rel)
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "w", encoding="utf-8") as fh:
        fh.write(content)


def prefix_for(rel):
    """Relative path back to the site root, e.g. '../../../' """
    depth = rel.count("/")
    return "../" * depth


def canonical_for(rel):
    """Production URL for a page. index.html collapses to a trailing slash."""
    path = rel
    if path == "index.html":
        return BASE_URL + "/"
    if path.endswith("/index.html"):
        return BASE_URL + "/" + path[: -len("index.html")]
    return BASE_URL + "/" + path


def get_title(src):
    m = re.search(r"<title>(.*?)</title>", src, re.S)
    return html.unescape(m.group(1)).strip() if m else ""


def get_description(src):
    m = re.search(r'<meta content="([^"]*)"\s+name="description"\s*/?>', src)
    return html.unescape(m.group(1)).strip() if m else ""


def get_h1(src):
    m = re.search(r"<h1[^>]*>(.*?)</h1>", src, re.S)
    if not m:
        return ""
    return html.unescape(re.sub(r"<[^>]+>", " ", m.group(1))).strip()


def esc(text):
    """Escape for use inside an HTML attribute."""
    return html.escape(str(text), quote=True)


def jsonld(obj):
    """Render a JSON-LD block. </script> in data is neutralised."""
    payload = json.dumps(obj, ensure_ascii=False, separators=(",", ":"))
    payload = payload.replace("</", "<\\/")
    return '<script type="application/ld+json">' + payload + "</script>"


def page_kind(rel):
    """Classify a page so the right schema and modules get applied."""
    if rel == "index.html":
        return "home"
    if rel.startswith("treatments/") and "/procedures/" in rel:
        return "procedure"
    if rel.startswith("treatments/") and "/conditions/" in rel:
        return "condition"
    if re.fullmatch(r"treatments/[a-z\-]+/index\.html", rel):
        return "specialty"
    if rel == "treatments/index.html":
        return "treatments-hub"
    if rel.startswith("doctors/") and not rel.endswith("/index.html"):
        return "doctor"
    if rel.startswith("clinics/") and not rel.endswith("/index.html"):
        return "clinic"
    if rel.startswith("guides/") and not rel.endswith("/index.html"):
        return "guide"
    if rel.startswith("patient-stories/") and not rel.endswith("/index.html"):
        return "story"
    if rel.startswith("legal/") or rel == "legal.html":
        return "legal"
    return "page"


def specialty_of(rel):
    m = re.match(r"treatments/([a-z\-]+)/", rel)
    return m.group(1) if m else None


def breadcrumbs(src):
    """Pull the on-page breadcrumb trail as [(label, href_or_None), ...]."""
    m = re.search(r'<(nav|div)[^>]*class="[^"]*breadcrumb[^"]*"[^>]*>(.*?)</\1>', src, re.S)
    if not m:
        return []
    trail = []
    for item in re.finditer(r'<a[^>]+href="([^"]+)"[^>]*>(.*?)</a>|<span[^>]*>(.*?)</span>', m.group(2), re.S):
        href, label, plain = item.group(1), item.group(2), item.group(3)
        text = html.unescape(re.sub(r"<[^>]+>", "", label if label is not None else (plain or ""))).strip()
        if not text or text in ("›", "/", "»", ">"):
            continue
        trail.append((text, href))
    return trail
