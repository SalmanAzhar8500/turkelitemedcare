"""Wire in the V7 script, stop storing enquiry data in localStorage, and generate
robots.txt, sitemap.xml, the web manifest and the icon set.

Run from the site root:  python3 tools/patch_infra.py
"""
import os
import re
import sys
import datetime

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg


# ------------------------------------------------------------- 1. scripts
def wire_scripts():
    n = 0
    for rel in cfg.html_files():
        src = cfg.read(rel)
        if "v7-enhancements.js" in src:
            continue
        p = cfg.prefix_for(rel)
        m = re.search(r'<script src="([^"]*)assets/js/premium\.js"></script>', src)
        if not m:
            continue
        tag = '<script defer src="%sassets/js/v7-enhancements.js"></script>' % p
        src = src[: m.end()] + tag + src[m.end():]
        cfg.write(rel, src)
        n += 1
    print("  v7-enhancements.js wired into %d pages" % n)


def harden_site_js():
    """Remove the localStorage writes. Enquiry details — including free-text health
    information — should never be persisted to the visitor's device."""
    rel = "assets/js/site.js"
    path = os.path.join(cfg.ROOT, rel)
    with open(path, encoding="utf-8") as fh:
        src = fh.read()

    src = src.replace(
        "localStorage.setItem('turkelitemedcare-demo-enquiry',new Date().toISOString());"
        "alert('Demo only: this enquiry was not sent. Connect the form to your backend before launch.');",
        "form.querySelectorAll('input,select,textarea').forEach(function(f){"
        "if(f.type!=='hidden'&&f.type!=='checkbox'){f.value='';}});"
        "alert('This enquiry has not been sent — the form is not yet connected to the "
        "coordination system. No details were stored.');",
    )

    src = src.replace(
        "const data=Object.fromEntries(new FormData(intake).entries());"
        "localStorage.setItem('turkelitemedcare-demo-treatment-request',"
        "JSON.stringify({...data,savedAt:new Date().toISOString()}));",
        "",
    )

    # the video mount is now handled by the consent gate in v7-enhancements.js
    src = src.replace(
        "s.onload=()=>{qsa('.video-module').forEach(m=>{const id=(window.VIDEO_MAP||{})[m.dataset.videoKey];"
        "if(id){m.innerHTML=`<iframe src=\"https://www.youtube-nocookie.com/embed/${encodeURIComponent(id)}\" "
        "title=\"Procedure video\" loading=\"lazy\" allow=\"accelerometer; autoplay; clipboard-write; "
        "encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>`;}})};",
        "s.onload=()=>{/* iframes are mounted on click by v7-enhancements.js */};",
    )

    # search results are built with innerHTML; escape the index values
    if "const escHtml" in src:
        pass  # already applied
    else:
        src = src.replace(
        "results.innerHTML=hits.length?hits.map(x=>",
        "const escHtml=t=>String(t).replace(/[&<>\"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"
        "'\"':'&quot;',\"'\":'&#39;'}[c]));\n    results.innerHTML=hits.length?hits.map(x=>",
        )
    src = src.replace(
        '<b>${x.title}</b><small>${x.type}</small>',
        "<b>${escHtml(x.title)}</b><small>${escHtml(x.type)}</small>",
    )

    with open(path, "w", encoding="utf-8") as fh:
        fh.write(src)
    print("  site.js: localStorage PII writes removed, search output escaped")


# --------------------------------------------------------- 2. robots/sitemap
def build_robots():
    body = """# Turkelitemedcare
User-agent: *
Allow: /

# Nothing behind these should reach an index
Disallow: /api/
Disallow: /tools/
Disallow: /legal/*?
Disallow: /treatment-plan.html?

# Procedure pages still carrying templated copy are excluded per-page with a
# robots meta tag rather than here, so they can still be crawled and followed.

Sitemap: %s/sitemap.xml
""" % cfg.BASE_URL
    with open(os.path.join(cfg.ROOT, "robots.txt"), "w", encoding="utf-8") as fh:
        fh.write(body)
    print("  robots.txt")


def build_sitemap():
    import json
    status = {}
    sp = os.path.join(cfg.ROOT, "tools", "content-status.json")
    if os.path.exists(sp):
        with open(sp, encoding="utf-8") as fh:
            status = json.load(fh)

    today = datetime.date.today().isoformat()
    priority = {
        "home": "1.0", "treatments-hub": "0.9", "specialty": "0.9",
        "procedure": "0.8", "condition": "0.7", "clinic": "0.7",
        "doctor": "0.6", "guide": "0.6", "story": "0.5", "legal": "0.3",
    }
    freq = {"home": "weekly", "specialty": "monthly", "procedure": "monthly"}

    urls = []
    skipped = 0
    for rel in cfg.html_files():
        if status.get(rel, {}).get("index") is False:
            skipped += 1
            continue
        kind = cfg.page_kind(rel)
        urls.append(
            "  <url>\n    <loc>%s</loc>\n    <lastmod>%s</lastmod>\n"
            "    <changefreq>%s</changefreq>\n    <priority>%s</priority>\n  </url>"
            % (cfg.canonical_for(rel), today, freq.get(kind, "yearly"), priority.get(kind, "0.5"))
        )

    xml = (
        '<?xml version="1.0" encoding="UTF-8"?>\n'
        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n'
        + "\n".join(urls)
        + "\n</urlset>\n"
    )
    with open(os.path.join(cfg.ROOT, "sitemap.xml"), "w", encoding="utf-8") as fh:
        fh.write(xml)
    print("  sitemap.xml: %d URLs (%d noindex pages excluded)" % (len(urls), skipped))


def build_manifest():
    body = """{
  "name": "Turkelitemedcare",
  "short_name": "Turkelite",
  "description": "Coordinated medical travel to partner clinics in Turkey.",
  "start_url": "/",
  "display": "standalone",
  "background_color": "#ffffff",
  "theme_color": "#0f5273",
  "icons": [
    { "src": "/assets/img/favicon.svg", "sizes": "any", "type": "image/svg+xml", "purpose": "any" },
    { "src": "/assets/img/apple-touch-icon.png", "sizes": "180x180", "type": "image/png" }
  ]
}
"""
    with open(os.path.join(cfg.ROOT, "site.webmanifest"), "w", encoding="utf-8") as fh:
        fh.write(body)
    print("  site.webmanifest")


def build_favicon():
    svg = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" role="img" aria-label="Turkelitemedcare">
  <rect width="64" height="64" rx="14" fill="#113d56"/>
  <path d="M32 15c-8.5 0-15 6-15 14.2 0 9.2 8.3 16.4 13.6 20.1a2.4 2.4 0 0 0 2.8 0C38.7 45.6 47 38.4 47 29.2 47 21 40.5 15 32 15Z" fill="#1fae96"/>
  <path d="M32 23v14M25 30h14" stroke="#fff" stroke-width="4" stroke-linecap="round"/>
</svg>
"""
    with open(os.path.join(cfg.ROOT, "assets", "img", "favicon.svg"), "w", encoding="utf-8") as fh:
        fh.write(svg)
    # Placeholder note for the raster formats, which need a real export step.
    note = """# Icon exports still needed

`favicon.svg` is generated and referenced site-wide. Two raster exports are still
required — generate them from the SVG with any icon tool and drop them in this folder:

- `favicon.ico`      — 16, 32 and 48 px, for older browsers
- `apple-touch-icon.png` — 180x180 px, no transparency, for iOS home screens

Also export `assets/img/og/og-default.png` at 1200x630 for social sharing previews;
the head tags already point at it.
"""
    with open(os.path.join(cfg.ROOT, "assets", "img", "ICONS-TODO.md"), "w", encoding="utf-8") as fh:
        fh.write(note)
    os.makedirs(os.path.join(cfg.ROOT, "assets", "img", "og"), exist_ok=True)
    print("  favicon.svg + icon export notes")


if __name__ == "__main__":
    wire_scripts()
    harden_site_js()
    build_robots()
    build_manifest()
    build_favicon()
    build_sitemap()
