"""Add canonical URLs, social cards, icons and JSON-LD structured data to every page.

Idempotent: re-running replaces the previously injected block rather than duplicating it.
Run from the site root:  python3 tools/patch_head.py
"""
import os
import re
import html
import json
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

START = "<!--v7:head-->"
END = "<!--/v7:head-->"

STATUS_PATH = os.path.join(cfg.ROOT, "tools", "content-status.json")
CONTENT_STATUS = {}
if os.path.exists(STATUS_PATH):
    with open(STATUS_PATH, encoding="utf-8") as fh:
        CONTENT_STATUS = json.load(fh)


# ---------------------------------------------------------------- extractors
def extract_faq(src):
    """Pull the on-page FAQ accordion into schema-ready pairs."""
    block = re.search(r'<div class="procedure-faq-v6">(.*?)</div>', src, re.S)
    if not block:
        return []
    out = []
    for m in re.finditer(r"<details[^>]*><summary>(.*?)</summary>(.*?)</details>", block.group(1), re.S):
        q = html.unescape(re.sub(r"<[^>]+>", " ", m.group(1))).strip()
        a = html.unescape(re.sub(r"<[^>]+>", " ", m.group(2))).strip()
        a = re.sub(r"\s+", " ", a)
        if q and a:
            out.append((q, a))
    return out


def hero_image(src, rel):
    """First real image on the page, resolved to an absolute production URL."""
    # Image URLs live in custom properties and are written relative to the
    # stylesheet ("../img/..."), so resolve that form back to a site-absolute URL.
    m = re.search(r"url\('(?:\.\./)*(?:assets/)?img/([^']+)'\)", src)
    if m and not m.group(1).lower().endswith(".svg"):
        return cfg.BASE_URL + "/assets/img/" + m.group(1)
    # Facebook, LinkedIn, WhatsApp and X all decline to render SVG for og:image.
    # The site artwork is SVG, so social previews use the raster default until
    # per-page 1200x630 PNGs are exported. See assets/img/ICONS-TODO.md.
    return cfg.BASE_URL + cfg.DEFAULT_OG_IMAGE


def org_node():
    return {
        "@type": ["Organization", "MedicalBusiness"],
        "@id": cfg.BASE_URL + "/#organization",
        "name": cfg.ORG_NAME,
        "legalName": cfg.ORG_LEGAL_NAME,
        "url": cfg.BASE_URL + "/",
        "logo": cfg.BASE_URL + cfg.ORG_LOGO,
        "email": cfg.ORG_EMAIL,
        "telephone": cfg.ORG_PHONE,
        "description": (
            "Medical travel coordination for international patients treated by independent "
            "partner clinics in Turkey. Turkelitemedcare coordinates the journey; it does not "
            "provide medical care."
        ),
        "areaServed": [
            {"@type": "Country", "name": "Germany"},
            {"@type": "Country", "name": "Austria"},
            {"@type": "Country", "name": "Switzerland"},
            {"@type": "Country", "name": "United Kingdom"},
            {"@type": "Country", "name": "Netherlands"},
        ],
        "knowsLanguage": ["de", "en"],
    }


# ------------------------------------------------------------------- schemas
def build_schema(rel, src, kind, title, desc, h1, image):
    url = cfg.canonical_for(rel)
    graph = []

    # Breadcrumbs -------------------------------------------------------
    trail = cfg.breadcrumbs(src)
    if trail:
        items = []
        for i, (label, href) in enumerate(trail, start=1):
            entry = {"@type": "ListItem", "position": i, "name": label}
            if href:
                target = os.path.normpath(os.path.join(os.path.dirname(rel), href)).replace("\\", "/")
                entry["item"] = cfg.canonical_for(target)
            items.append(entry)
        graph.append({
            "@type": "BreadcrumbList",
            "@id": url + "#breadcrumb",
            "itemListElement": items,
        })

    # Page node ---------------------------------------------------------
    page_type = "MedicalWebPage" if kind in ("procedure", "condition", "specialty", "treatments-hub") else "WebPage"
    page = {
        "@type": page_type,
        "@id": url + "#webpage",
        "url": url,
        "name": title,
        "description": desc,
        "inLanguage": cfg.SITE_LANG,
        "isPartOf": {"@id": cfg.BASE_URL + "/#website"},
        "publisher": {"@id": cfg.BASE_URL + "/#organization"},
        "primaryImageOfPage": image,
    }
    if trail:
        page["breadcrumb"] = {"@id": url + "#breadcrumb"}
    if page_type == "MedicalWebPage":
        page["audience"] = {"@type": "MedicalAudience", "audienceType": "Patient"}
        page["lastReviewed"] = CONTENT_STATUS.get(rel, {}).get("lastReviewed", "")
        page["reviewedBy"] = {"@id": cfg.BASE_URL + "/#organization"}
        if not page["lastReviewed"]:
            page.pop("lastReviewed")
            page.pop("reviewedBy")

    # Procedure / condition subject -------------------------------------
    if kind == "procedure" and h1:
        page["about"] = {
            "@type": "MedicalProcedure",
            "@id": url + "#procedure",
            "name": h1,
            "url": url,
            "procedureType": "https://schema.org/SurgicalProcedure",
            "relevantSpecialty": cfg.SPECIALTY_LABELS.get(cfg.specialty_of(rel), ""),
            "performedBy": {
                "@type": "MedicalOrganization",
                "name": "Independent partner clinic in Turkey",
            },
        }
    elif kind == "condition" and h1:
        page["about"] = {
            "@type": "MedicalCondition",
            "@id": url + "#condition",
            "name": h1,
            "url": url,
            "relevantSpecialty": cfg.SPECIALTY_LABELS.get(cfg.specialty_of(rel), ""),
        }
    graph.append(page)

    # FAQ ---------------------------------------------------------------
    # FAQPage markup is only emitted where the answers are specific to the page.
    # Identical FAQs repeated across 86 templated pages would be duplicate
    # structured data, so those pages get none until they are written.
    faq = extract_faq(src) if CONTENT_STATUS.get(rel, {}).get("index") is not False else []
    if faq:
        graph.append({
            "@type": "FAQPage",
            "@id": url + "#faq",
            "mainEntity": [
                {
                    "@type": "Question",
                    "name": q,
                    "acceptedAnswer": {"@type": "Answer", "text": a},
                }
                for q, a in faq
            ],
        })

    # Entity pages ------------------------------------------------------
    if kind == "doctor":
        spec = ""
        m = re.search(r'<p class="doctor-specialty"[^>]*>(.*?)</p>', src, re.S)
        if not m:
            m = re.search(r"<h1[^>]*>.*?</h1>\s*<p[^>]*>(.*?)</p>", src, re.S)
        if m:
            spec = html.unescape(re.sub(r"<[^>]+>", " ", m.group(1))).strip()
        graph.append({
            "@type": "Physician",
            "@id": url + "#physician",
            "name": h1,
            "url": url,
            "image": image,
            "medicalSpecialty": spec,
            "affiliation": {"@id": cfg.BASE_URL + "/#organization"},
            "disambiguatingDescription": (
                "Independent clinician at a partner clinic. Credentials are verified before public launch."
            ),
        })

    if kind == "clinic":
        city = ""
        m = re.search(r"(Istanbul|Antalya|Izmir|İzmir)", src)
        if m:
            city = m.group(1).replace("İ", "I")
        graph.append({
            "@type": "MedicalClinic",
            "@id": url + "#clinic",
            "name": h1,
            "url": url,
            "image": image,
            "address": {
                "@type": "PostalAddress",
                "addressLocality": city,
                "addressCountry": "TR",
            },
            "isAcceptingNewPatients": True,
            "disambiguatingDescription": (
                "Independent healthcare provider. Turkelitemedcare coordinates the patient journey "
                "and does not deliver clinical care."
            ),
        })

    if kind in ("guide", "story"):
        graph.append({
            "@type": "Article",
            "@id": url + "#article",
            "headline": h1 or title,
            "description": desc,
            "image": image,
            "mainEntityOfPage": {"@id": url + "#webpage"},
            "publisher": {"@id": cfg.BASE_URL + "/#organization"},
            "author": {"@id": cfg.BASE_URL + "/#organization"},
        })

    # Home --------------------------------------------------------------
    if kind == "home":
        graph.append(org_node())
        graph.append({
            "@type": "WebSite",
            "@id": cfg.BASE_URL + "/#website",
            "url": cfg.BASE_URL + "/",
            "name": cfg.ORG_NAME,
            "inLanguage": cfg.SITE_LANG,
            "publisher": {"@id": cfg.BASE_URL + "/#organization"},
            "potentialAction": {
                "@type": "SearchAction",
                "target": {
                    "@type": "EntryPoint",
                    "urlTemplate": cfg.BASE_URL + "/search.html?q={search_term_string}",
                },
                "query-input": "required name=search_term_string",
            },
        })
    else:
        graph.append({
            "@type": "WebSite",
            "@id": cfg.BASE_URL + "/#website",
            "url": cfg.BASE_URL + "/",
            "name": cfg.ORG_NAME,
            "publisher": {"@id": cfg.BASE_URL + "/#organization"},
        })
        graph.append(org_node())

    return {"@context": "https://schema.org", "@graph": graph}


# --------------------------------------------------------------------- build
def head_block(rel, src):
    kind = cfg.page_kind(rel)
    p = cfg.prefix_for(rel)
    title = cfg.get_title(src)
    desc = cfg.get_description(src)
    h1 = cfg.get_h1(src)
    url = cfg.canonical_for(rel)
    image = hero_image(src, rel)

    parts = [START]
    parts.append('<link href="%s" rel="canonical"/>' % cfg.esc(url))
    # One published language today. Add the de/ alternates here when the German tree ships.
    parts.append('<link href="%s" hreflang="en" rel="alternate"/>' % cfg.esc(url))
    parts.append('<link href="%s" hreflang="x-default" rel="alternate"/>' % cfg.esc(url))

    # Icons and manifest
    parts.append('<link href="%sassets/img/favicon.svg" rel="icon" type="image/svg+xml"/>' % p)
    parts.append('<link href="%sassets/img/favicon.ico" rel="alternate icon" sizes="any"/>' % p)
    parts.append('<link href="%sassets/img/apple-touch-icon.png" rel="apple-touch-icon"/>' % p)
    parts.append('<link href="%ssite.webmanifest" rel="manifest"/>' % p)

    # Open Graph
    og_title = title
    parts.append('<meta content="%s" property="og:title"/>' % cfg.esc(og_title))
    parts.append('<meta content="%s" property="og:description"/>' % cfg.esc(desc))
    parts.append('<meta content="%s" property="og:url"/>' % cfg.esc(url))
    parts.append('<meta content="%s" property="og:image"/>' % cfg.esc(image))
    parts.append('<meta content="%s" property="og:image:alt"/>' % cfg.esc(h1 or title))
    parts.append('<meta content="%s" property="og:site_name"/>' % cfg.esc(cfg.ORG_NAME))
    parts.append('<meta content="%s" property="og:locale"/>' % "en_GB")
    parts.append('<meta content="%s" property="og:type"/>' % ("article" if kind in ("guide", "story") else "website"))

    # Twitter / X
    parts.append('<meta content="summary_large_image" name="twitter:card"/>')
    parts.append('<meta content="%s" name="twitter:title"/>' % cfg.esc(og_title))
    parts.append('<meta content="%s" name="twitter:description"/>' % cfg.esc(desc))
    parts.append('<meta content="%s" name="twitter:image"/>' % cfg.esc(image))

    # Indexing control for pages still carrying templated medical copy
    status = CONTENT_STATUS.get(rel, {})
    if status.get("index") is False:
        parts.append('<meta content="noindex,follow" name="robots"/>')
    else:
        parts.append('<meta content="index,follow,max-image-preview:large,max-snippet:-1" name="robots"/>')

    # Performance + supporting stylesheet
    parts.append('<link crossorigin="" href="https://images.unsplash.com" rel="preconnect"/>'
                 if "images.unsplash.com" in src else "")
    parts.append('<link href="%sassets/css/v7-additions.css" rel="stylesheet"/>' % p)
    # The calm theme retunes tokens set by every earlier sheet, so it loads last.
    parts.append('<link href="%sassets/css/v7-calm.css" rel="stylesheet"/>' % p)

    parts.append(cfg.jsonld(build_schema(rel, src, kind, title, desc, h1, image)))
    parts.append(END)
    return "".join(x for x in parts if x)


def main():
    files = cfg.html_files()
    changed = 0
    for rel in files:
        src = cfg.read(rel)
        # remove any previous injection so the script is safe to re-run
        src = re.sub(re.escape(START) + ".*?" + re.escape(END), "", src, flags=re.S)
        if "</head>" not in src:
            print("  ! no </head>:", rel)
            continue
        src = src.replace("</head>", head_block(rel, src) + "</head>", 1)
        cfg.write(rel, src)
        changed += 1
    print("patch_head: updated %d pages" % changed)


if __name__ == "__main__":
    main()
