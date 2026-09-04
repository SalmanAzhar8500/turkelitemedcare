"""Accessibility fixes, image-licensing cleanup and trust modules.

Accessibility
  - skip link on every page
  - aria-current="page" on the active nav item
  - the dead DE/EN switcher replaced with honest markup
  - loading/decoding hints on images below the fold

Images
  - hotlinked Unsplash URLs removed from every background stack, leaving the local
    illustration. Removing them resolves the licensing and single-point-of-failure
    problem; tools/PHOTO-MANIFEST.md lists every slot and the original photo id so
    licensed photography can be dropped back in.

Trust
  - cost-transparency module on procedure pages
  - accreditation block on clinic pages
  - "how we are paid" disclosure on the About page

Run from the site root:  python3 tools/patch_a11y_trust.py
"""
import os
import re
import sys
import collections

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

SKIP = ('<a class="skip-link" href="#main-content">Skip to main content</a>')


# ---------------------------------------------------------------- 1. a11y
def patch_a11y():
    photo_slots = collections.defaultdict(list)
    n = 0
    for rel in cfg.html_files():
        src = cfg.read(rel)
        orig = src
        p = cfg.prefix_for(rel)

        # skip link ---------------------------------------------------
        if "skip-link" not in src:
            src = src.replace("<body>", "<body>" + SKIP, 1)
        # give <main> a target and a landmark id (idempotent: skip if already present)
        if 'id="main-content"' not in src:
            src = re.sub(r"<main(?![a-z-])", '<main id="main-content" tabindex="-1"', src, count=1)

        # active nav item --------------------------------------------
        page_file = rel.split("/")[-1]
        top = rel.split("/")[0] if "/" in rel else rel
        def mark(m):
            href = m.group(1)
            target = os.path.normpath(os.path.join(os.path.dirname(rel), href)).replace("\\", "/")
            if target == rel:
                return '<a aria-current="page" href="%s"' % href
            # section-level match, e.g. any clinics/* page marks the Clinics link
            if "/" in rel and target.startswith(top + "/"):
                return '<a aria-current="true" href="%s"' % href
            return m.group(0)
        nav_m = re.search(r'<nav aria-label="Main navigation".*?</nav>', src, re.S)
        if nav_m:
            navsrc = re.sub(r'<a href="([^"]+)"', mark, nav_m.group(0))
            src = src[: nav_m.start()] + navsrc + src[nav_m.end():]

        # language switcher ------------------------------------------
        src = src.replace(
            '<a class="lang" href="#">DE</a><span class="sep">/</span><a class="lang active" href="#">EN</a>',
            '<span class="lang-switch"><span aria-current="true" class="lang active">EN</span>'
            '<span class="sep">/</span><a class="lang is-pending" href="%scontact.html" '
            'title="The German site is in preparation — our team answers in German today">DE</a></span>' % p,
        )

        # image loading hints ----------------------------------------
        def img(m):
            tag = m.group(0)
            if "brand-logo" in tag or "loading=" in tag:
                return tag
            return tag[:-2] + ' decoding="async" loading="lazy"/>' if tag.endswith("/>") else tag
        src = re.sub(r"<img\b[^>]*/>", img, src)

        if src != orig:
            cfg.write(rel, src)
            n += 1
    print("  accessibility pass: %d pages" % n)


# ------------------------------------------------------------- 2. images
UNSPLASH_RE = re.compile(r"url\('(https://images\.unsplash\.com/photo-[^']+)'\),?")


def patch_images():
    slots = []
    n = 0
    for rel in cfg.html_files():
        src = cfg.read(rel)
        found = UNSPLASH_RE.findall(src)
        if not found:
            continue
        for url in found:
            pid = re.search(r"(photo-[0-9a-f]+-[0-9a-f]+)", url)
            slots.append((rel, pid.group(1) if pid else url))
        src = UNSPLASH_RE.sub("", src)
        # tidy any stack that is now empty or has a dangling comma
        src = re.sub(r"(--[a-z-]+:)\s*,", r"\1", src)
        cfg.write(rel, src)
        n += 1

    lines = [
        "# Photography manifest",
        "",
        "Hotlinking `images.unsplash.com` was removed from %d pages. Unsplash's licence does not "
        "cover hotlinking their CDN, and it made every hero and tile depend on a third party staying up." % n,
        "",
        "Each slot below now falls back to the local illustration in `assets/img/synthetic/` or "
        "`assets/img/v5/`. To restore photography:",
        "",
        "1. Licence or commission an image for the slot (clinic photography of your own partners is "
        "stronger than stock for this sector, and removes the licensing question entirely).",
        "2. Save it to `assets/img/photos/<slot-name>.webp`, plus a 2x variant.",
        "3. Add it in front of the local illustration in the same CSS stack: "
        "`--tile-image:url('.../photos/x.webp'),url('.../synthetic/x.svg')` — the illustration stays as the fallback.",
        "",
        "Consent note: never publish a photograph of a real patient, a real clinic interior or an "
        "identifiable clinician without written, documented consent for commercial use.",
        "",
        "## Slots that previously used a remote photo",
        "",
        "| Page | Original Unsplash photo id |",
        "| --- | --- |",
    ]
    seen = set()
    for rel, pid in slots:
        key = (rel, pid)
        if key in seen:
            continue
        seen.add(key)
        lines.append("| `%s` | `%s` |" % (rel, pid))
    with open(os.path.join(cfg.ROOT, "tools", "PHOTO-MANIFEST.md"), "w", encoding="utf-8") as fh:
        fh.write("\n".join(lines))
    print("  remote image hotlinks removed from %d pages (%d slots logged)" % (n, len(seen)))


# -------------------------------------------------------------- 3. trust
COST_BLOCK = """<!--v7:cost--><h2 id="cost">What it costs, and what "included" actually means</h2>
<p>We do not publish a headline price for this procedure, because a number without its inclusions is the single most misleading figure in medical travel. Two quotes that look identical routinely cover different implants, different anaesthesia, different lengths of stay and completely different aftercare. What we do instead is make the quote comparable.</p>
<p>Every clinic proposal we pass on is broken down against the same checklist, so you can see what each price does and does not buy:</p>
<div class="cost-grid">
<div class="cost-col is-in"><h3>Usually included</h3><ul>
<li>Surgeon, anaesthetist and theatre fees</li>
<li>Implants, prostheses, grafts or lenses as specified — by brand and model</li>
<li>Named hospital or clinic nights</li>
<li>Standard pre-operative tests</li>
<li>Scheduled post-operative reviews taken before you fly home</li>
<li>Airport and clinic transfers, where the clinic package includes them</li>
</ul></div>
<div class="cost-col is-out"><h3>Frequently excluded — ask directly</h3><ul>
<li>Treating a complication, and any extra nights it requires</li>
<li>Revision surgery, and the conditions under which it would be free</li>
<li>Additional procedures found necessary after assessment</li>
<li>Medicines, garments, splints or supplements taken home</li>
<li>Flights, and accommodation beyond the nights specified</li>
<li>Aftercare once you return, including anything your own doctor charges for</li>
<li>Currency conversion and card fees</li>
</ul></div>
</div>
<p class="clinical-note">A firm price can only follow a clinical assessment. Any figure quoted before a clinician has reviewed your case is an estimate, and it can change. We will tell you when a quotation is firm and when it is not.</p><!--/v7:cost-->"""

ACCREDITATION_BLOCK = """<!--v7:accreditation--><section class="template-section accreditation-section"><div class="container">
<h2>Accreditation and verification</h2>
<p class="verify-flag">Verification pending. Nothing in this table is published until it has been checked against the issuing body's own register — never against a logo supplied by the clinic.</p>
<table class="legal-table accreditation-table">
<tr><th>Item</th><th>Status</th><th>Verified on</th></tr>
<tr><td>Turkish Ministry of Health facility licence</td><td>To verify</td><td>—</td></tr>
<tr><td>Health tourism authorisation</td><td>To verify</td><td>—</td></tr>
<tr><td>JCI accreditation</td><td>To verify</td><td>—</td></tr>
<tr><td>ISO 9001 quality management</td><td>To verify</td><td>—</td></tr>
<tr><td>Professional indemnity cover (scope and limits)</td><td>To verify</td><td>—</td></tr>
<tr><td>Named clinicians checked against the Turkish Medical Association register</td><td>To verify</td><td>—</td></tr>
<tr><td>Written complications and readmission pathway</td><td>To verify</td><td>—</td></tr>
</table>
<p>Our full standard is published in <a href="../legal/clinic-selection-standards.html">clinic selection standards</a>.</p>
</div></section><!--/v7:accreditation-->"""

PAID_BLOCK = """<!--v7:paid--><section class="template-section alt how-we-are-paid"><div class="container">
<span class="eyebrow">Commercial transparency</span>
<h2>How we are paid</h2>
<p>You should know who pays us before you rely on our advice, so here it is in plain terms.</p>
<div class="paid-grid">
<div><h3>Where our revenue comes from</h3><p>[State it exactly: a coordination fee paid by the patient, a commission paid by the partner clinic, or both. If it is a commission, say how it is calculated — a fixed fee per journey, or a percentage of the treatment price.]</p></div>
<div><h3>Whether it changes what we recommend</h3><p>[State whether the fee differs between clinics or between procedures, and what stops that difference from influencing which clinic a patient is matched with.]</p></div>
<div><h3>What you pay us</h3><p>[State whether the patient pays anything to Turkelitemedcare, and whether the clinic price is the same as it would be booking direct.]</p></div>
<div><h3>Whether clinics pay for placement</h3><p>[State whether any clinic can pay to appear, to rank higher, or to be featured — and if not, say so plainly.]</p></div>
</div>
<p class="clinical-note">A coordinator that will not answer these four questions is asking you to take the most important part of the decision on trust. Full terms are in the <a href="legal/terms.html">platform terms</a>.</p>
</div></section><!--/v7:paid-->"""


def add_cost_module():
    n = 0
    for rel in cfg.html_files():
        if cfg.page_kind(rel) != "procedure":
            continue
        src = cfg.read(rel)
        src = re.sub(r"<!--v7:cost-->.*?<!--/v7:cost-->", "", src, flags=re.S)
        anchor = src.find("<h2>Frequently asked questions</h2>")
        if anchor == -1:
            continue
        src = src[:anchor] + COST_BLOCK + src[anchor:]
        cfg.write(rel, src)
        n += 1
    print("  cost-transparency module added to %d procedure pages" % n)


def add_accreditation():
    n = 0
    for rel in cfg.html_files():
        if cfg.page_kind(rel) != "clinic":
            continue
        src = cfg.read(rel)
        src = re.sub(r"<!--v7:accreditation-->.*?<!--/v7:accreditation-->", "", src, flags=re.S)
        src = src.replace("</main>", ACCREDITATION_BLOCK + "</main>", 1)
        cfg.write(rel, src)
        n += 1
    print("  accreditation block added to %d clinic pages" % n)


def add_paid_disclosure():
    rel = "about.html"
    src = cfg.read(rel)
    src = re.sub(r"<!--v7:paid-->.*?<!--/v7:paid-->", "", src, flags=re.S)
    src = src.replace("</main>", PAID_BLOCK + "</main>", 1)
    cfg.write(rel, src)
    print("  'how we are paid' disclosure added to about.html")


if __name__ == "__main__":
    patch_a11y()
    patch_images()
    # The cost module and the trust blocks are owned by patch_dedupe and
    # patch_trust_and_de respectively. Re-adding them here appended a second
    # copy — and a second id="cost" — on every full pipeline run.

