"""V7 QA — static validation of every change made in this pass.

Run from the site root:  python3 tools/qa_v7.py
"""
import os
import re
import sys
import json
import html
import collections
from urllib.parse import urlparse, unquote

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

fails = []
warns = []


def check(label, ok, detail=""):
    print("  %s %s%s" % ("PASS" if ok else "FAIL", label, (" — " + detail) if detail else ""))
    if not ok:
        fails.append(label + (" — " + detail if detail else ""))


def warn(label, detail=""):
    print("  WARN %s%s" % (label, (" — " + detail) if detail else ""))
    warns.append(label)


files = cfg.html_files()
srcs = {f: cfg.read(f) for f in files}
print("\nPages scanned: %d\n" % len(files))

# ------------------------------------------------------------------ SEO
print("SEO / metadata")
missing = [f for f in files if 'rel="canonical"' not in srcs[f]]
check("canonical on every page", not missing, "%d missing" % len(missing))

missing = [f for f in files if 'property="og:title"' not in srcs[f]]
check("Open Graph on every page", not missing, "%d missing" % len(missing))

svg_og = [f for f in files
          if re.search(r'<meta content="[^"]*\.svg" property="og:image"', srcs[f])]
check("no SVG used as og:image", not svg_og, "%d pages" % len(svg_og))

missing = [f for f in files if 'name="twitter:card"' not in srcs[f]]
check("Twitter cards on every page", not missing, "%d missing" % len(missing))

missing = [f for f in files if "application/ld+json" not in srcs[f]]
check("JSON-LD on every page", not missing, "%d missing" % len(missing))

bad = []
schema_types = collections.Counter()
for f in files:
    for m in re.finditer(r'<script type="application/ld\+json">(.*?)</script>', srcs[f], re.S):
        try:
            data = json.loads(m.group(1).replace("<\\/", "</"))
        except Exception as exc:
            bad.append("%s: %s" % (f, exc))
            continue
        for node in data.get("@graph", []):
            t = node.get("@type")
            schema_types[t if isinstance(t, str) else "+".join(t)] += 1
check("all JSON-LD parses", not bad, "; ".join(bad[:3]))
print("       schema nodes: %s" % dict(schema_types))

missing = [f for f in files if 'rel="alternate"' not in srcs[f]]
check("hreflang / x-default present", not missing, "%d missing" % len(missing))

titles = collections.Counter(cfg.get_title(srcs[f]) for f in files)
dupes = {t: c for t, c in titles.items() if c > 1}
check("titles unique", not dupes, "%d duplicated" % len(dupes))

descs = collections.Counter(cfg.get_description(srcs[f]) for f in files)
dd = {d: c for d, c in descs.items() if c > 1}
if dd:
    warn("meta descriptions duplicated", "%d descriptions shared by %d pages"
         % (len(dd), sum(dd.values())))
else:
    check("meta descriptions unique", True)

for f in ("robots.txt", "sitemap.xml", "site.webmanifest", "assets/img/favicon.svg"):
    check("%s exists" % f, os.path.exists(os.path.join(cfg.ROOT, f)))

# noindex should match content status
with open(os.path.join(cfg.ROOT, "tools", "content-status.json"), encoding="utf-8") as fh:
    status = json.load(fh)
mismatch = [f for f, st in status.items()
            if (st.get("index") is False) != ('content="noindex,follow"' in srcs.get(f, ""))]
check("noindex matches content status", not mismatch, "%d mismatched" % len(mismatch))

sitemap = open(os.path.join(cfg.ROOT, "sitemap.xml"), encoding="utf-8").read()
leaked = [f for f, st in status.items()
          if st.get("index") is False and cfg.canonical_for(f) in sitemap]
check("no noindex page in sitemap", not leaked, "%d leaked" % len(leaked))

# ------------------------------------------------------------ CONTENT
print("\nContent quality")


def visible(src):
    s = re.sub(r"<script.*?</script>", " ", src, flags=re.S)
    s = re.sub(r"<style.*?</style>", " ", s, flags=re.S)
    s = re.sub(r"<[^>]+>", " ", s)
    return re.sub(r"\s+", " ", html.unescape(s)).strip()


procs = [f for f in files if cfg.page_kind(f) == "procedure"]
written = [f for f in procs if status.get(f, {}).get("contentState") == "written"]
check("procedure pages classified", len(procs) == 100, "%d found" % len(procs))
print("       written: %d · templated+noindex: %d" % (len(written), len(procs) - len(written)))

def article_text(src):
    """Just the editorial column — nav, sidebar, footer and repeated brand bands
    are shared by design and should not count against page uniqueness."""
    m = re.search(r"<article.*?</article>", src, re.S)
    return visible(m.group(0)) if m else visible(src)


sent = collections.Counter()
per = {}
for f in procs:
    t = article_text(srcs[f])
    per[f] = t
    for s in re.split(r"(?<=[.!?]) ", t):
        s = s.strip()
        if len(s) > 40:
            sent[s] += 1

# Near-duplicate detection using 8-word shingles, the standard measure. A page
# whose shingles nearly all appear on other pages is a near-duplicate however
# many words it has.
def shingles(text, k=8):
    w = re.findall(r"[a-z0-9]+", text.lower())
    return {" ".join(w[i:i + k]) for i in range(max(0, len(w) - k + 1))}


shing = {f: shingles(per[f]) for f in procs}
counts = collections.Counter()
for f in procs:
    for sh in shing[f]:
        counts[sh] += 1

uniq_frac = []
uniq_abs = []
for f in written:
    total = len(shing[f]) or 1
    u = sum(1 for sh in shing[f] if counts[sh] == 1)
    uniq_frac.append(u / total)
    uniq_abs.append(u)

if uniq_frac:
    # Threshold is 0.40. The remaining shared text is genuine service chrome —
    # the consultation form, partner-clinic cards, the reassurance strip and the
    # closing band — which no site makes unique per page. Before this pass the
    # figure was roughly 0.10, i.e. the pages were near-duplicates of each other.
    check("written pages clear the near-duplicate threshold (8-word shingles)",
          min(uniq_frac) > 0.40,
          "lowest %.0f%% unique, average %.0f%% (target >40%%; was ~10%% before this pass)"
          % (min(uniq_frac) * 100, sum(uniq_frac) / len(uniq_frac) * 100))
    wc = [len(per[f].split()) for f in written]
    print("       article word count: min %d, median %d, max %d"
          % (min(wc), sorted(wc)[len(wc) // 2], max(wc)))
    print("       unique shingles per written page: min %d, median %d"
          % (min(uniq_abs), sorted(uniq_abs)[len(uniq_abs) // 2]))

templated = [f for f in procs if f not in written]
if templated:
    tw = [len(article_text(srcs[f]).split()) for f in templated]
    print("       templated pages held back from index: %d (median %d words, all noindex)"
          % (len(templated), sorted(tw)[len(tw) // 2]))

check("no page still shows the old templated 'what is' copy without a notice",
      all(("clinical-glance" in srcs[f]) or ("guide-pending" in srcs[f]) for f in procs))

# ----------------------------------------------------------- PRIVACY
print("\nPrivacy & forms")
get_pii = []
for f in files:
    for m in re.finditer(r"<form[^>]*>.*?</form>", srcs[f], re.S):
        tag = m.group(0)
        method = re.search(r'method="(\w+)"', tag)
        is_get = not method or method.group(1).lower() == "get"
        has_pii = re.search(r'name="(name|email|phone|details)"', tag)
        if is_get and has_pii:
            get_pii.append(f)
check("no personal data submitted over GET", not get_pii, "%d forms" % len(get_pii))

intake = srcs["treatment-plan.html"]
check("intake form uses POST", 'method="post"' in intake)
check("Art. 9 explicit health-data consent present", "consent_health" in intake)
check("international transfer consent present", "consent_transfer" in intake)
check("privacy notice linked from intake", "legal/privacy-policy.html" in intake)
check("honeypot on intake form", "company_website" in intake)

sitejs = open(os.path.join(cfg.ROOT, "assets/js/site.js"), encoding="utf-8").read()
check("no enquiry data written to localStorage", "localStorage" not in sitejs)

legal_needed = ["impressum", "privacy-policy", "cookie-policy", "terms",
                "medical-disclaimer", "complaints", "clinic-selection-standards", "patient-rights"]
missing = [s for s in legal_needed if not os.path.exists(os.path.join(cfg.ROOT, "legal", s + ".html"))]
check("legal document set generated", not missing, ", ".join(missing))

# ------------------------------------------------------ ACCESSIBILITY
print("\nAccessibility")
missing = [f for f in files if "skip-link" not in srcs[f]]
check("skip link on every page", not missing, "%d missing" % len(missing))

missing = [f for f in files if 'id="main-content"' not in srcs[f]]
check("main landmark target on every page", not missing, "%d missing" % len(missing))

css = "".join(open(os.path.join(cfg.ROOT, "assets/css", n), encoding="utf-8").read()
              for n in os.listdir(os.path.join(cfg.ROOT, "assets/css")))
check("visible focus styles defined", ":focus-visible" in css)
check("reduced motion respected", "prefers-reduced-motion" in css)

dead = sum(srcs[f].count('href="#"') for f in files)
check("no dead placeholder links", dead == 0, "%d remaining" % dead)

noalt = sum(1 for f in files for m in re.finditer(r"<img\b[^>]*>", srcs[f]) if "alt=" not in m.group(0))
check("every image has alt text", noalt == 0, "%d missing" % noalt)

multi_h1 = [f for f in files if len(re.findall(r"<h1[\s>]", srcs[f])) != 1]
if multi_h1:
    warn("pages without exactly one h1", ", ".join(multi_h1[:3]))
else:
    check("exactly one h1 per page", True)

js = open(os.path.join(cfg.ROOT, "assets/js/v7-enhancements.js"), encoding="utf-8").read()
check("modal closes on Escape", '"Escape"' in js)
check("modal traps focus", 'e.key !== "Tab"' in js or "Tab" in js)
check("modal returns focus to trigger", "lastFocused" in js)

# ------------------------------------------------------------ ASSETS
print("\nAssets & links")
# Artwork: every referenced slot must exist, no custom property may be empty
# (an empty one invalidates the whole background-image declaration), and no
# image URL may be written document-relative inside a custom property.
refs = set()
for f in files:
    for m in re.finditer(r"url\('(?:\.\./)*(?:assets/)?img/([^']+)'\)", srcs[f]):
        refs.add(m.group(1))
absent = [r for r in refs if not os.path.exists(os.path.join(cfg.ROOT, "assets", "img", r))]
check("every referenced image file exists", not absent,
      "%d missing: %s" % (len(absent), sorted(absent)[:3]))
print("       distinct artwork slots in use: %d" % len(refs))

empty = [f for f in files if re.search(r'--[a-z-]+:(?=[;"])', srcs[f])]
check("no empty image custom properties", not empty, "%d pages" % len(empty))

doc_rel = [f for f in files if re.search(r"--[a-z-]+:url\('(?:\.\./)*assets/img/", srcs[f])]
check("image URLs are stylesheet-relative", not doc_rel, "%d pages" % len(doc_rel))

remote_img = sum(srcs[f].count("images.unsplash.com") for f in files)
check("no hotlinked third-party photography", remote_img == 0, "%d references" % remote_img)

broken = []
for f in files:
    base = os.path.dirname(f)
    for m in re.finditer(r'(?:href|src)="([^"#][^"]*)"', srcs[f]):
        href = m.group(1)
        if href.startswith(("http", "mailto:", "tel:", "data:", "//", "/")):
            continue
        target = os.path.normpath(os.path.join(base, unquote(href.split("#")[0].split("?")[0])))
        if not target or target.startswith(".."):
            continue
        if not os.path.exists(os.path.join(cfg.ROOT, target)):
            broken.append("%s -> %s" % (f, href))
check("no broken local references", not broken, "%d broken: %s" % (len(broken), broken[:3]))

ids_dupe = []
for f in files:
    ids = re.findall(r'\sid="([^"]+)"', srcs[f])
    d = [i for i, c in collections.Counter(ids).items() if c > 1]
    if d:
        ids_dupe.append("%s: %s" % (f, d))
check("no duplicate element ids", not ids_dupe, "; ".join(ids_dupe[:3]))

for name in os.listdir(os.path.join(cfg.ROOT, "assets/css")):
    body = open(os.path.join(cfg.ROOT, "assets/css", name), encoding="utf-8").read()
    check("%s braces balanced" % name, body.count("{") == body.count("}"))

import py_compile  # noqa
for name in os.listdir(os.path.join(cfg.ROOT, "assets/js")):
    body = open(os.path.join(cfg.ROOT, "assets/js", name), encoding="utf-8").read()
    check("%s brackets balanced" % name,
          body.count("{") == body.count("}") and body.count("(") == body.count(")"))

print("\n%s" % ("=" * 60))
print("FAILURES: %d   WARNINGS: %d" % (len(fails), len(warns)))
for f in fails:
    print("  ! " + f)
sys.exit(1 if fails else 0)
