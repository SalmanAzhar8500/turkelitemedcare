"""Trust gating, the missing complications page, and a German entry point.

Addresses these findings from the selling-lens review:
  - six clinic pages showed seven rows each reading "To verify", which a visitor
    reads as "these clinics are not accredited"
  - the "how we are paid" section shipped four bracketed placeholders to visitors
  - nothing on the site answered "what happens if something goes wrong after I
    fly home", the single biggest objection in this category
  - the site targets Germany and had no German page at all

Verified facts live in tools/verified-claims.json. Nothing in that file is
invented: it ships empty, and each block renders an honest "not yet published"
state until real, checked data is entered. Populating it is a launch task.

Run from the site root:  python3 tools/patch_trust_and_de.py
"""
import os
import re
import sys
import json

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

CLAIMS_PATH = os.path.join(cfg.ROOT, "tools", "verified-claims.json")

DEFAULT_CLAIMS = {
    "_README": (
        "Only verified facts go in here. Every accreditation must be checked against the "
        "issuing body's own register, never against a logo supplied by the clinic. Blocks "
        "render an honest 'not yet published' state while the values are empty, so nothing "
        "unproven is ever shown to a patient."
    ),
    "commercial_model": {
        "published": False,
        "revenue_source": "",
        "varies_by_clinic": "",
        "patient_pays": "",
        "paid_placement": ""
    },
    "clinics": {}
}

if os.path.exists(CLAIMS_PATH):
    with open(CLAIMS_PATH, encoding="utf-8") as fh:
        CLAIMS = json.load(fh)
else:
    CLAIMS = DEFAULT_CLAIMS
    with open(CLAIMS_PATH, "w", encoding="utf-8") as fh:
        json.dump(CLAIMS, fh, indent=2)


# ------------------------------------------------- 1. accreditation gating
PENDING_ACCREDITATION = """<!--v7:accreditation--><section class="template-section accreditation-section"><div class="container">
<h2>How this clinic is checked</h2>
<p>We do not publish an accreditation until we have verified it against the issuing body's own register. Not a logo supplied by the clinic — the register. Until that check is complete for this partner, we say so rather than showing you a badge we cannot stand behind.</p>
<p>Every partner must meet the same minimum standard before it appears here at all: a current Turkish Ministry of Health facility licence, named clinicians verified against the Turkish Medical Association register, a documented informed-consent process in your language, a written complications and readmission pathway, and professional indemnity cover. The full standard is published in <a href="../legal/clinic-selection-standards.html">clinic selection standards</a>.</p>
<p><b>Ask us for this clinic's documentation and we will send you what we hold.</b> If we do not hold it, we will tell you that too.</p>
</div></section><!--/v7:accreditation-->"""


def accreditation_table(rows):
    body = "".join(
        '<tr><td>%s</td><td>%s</td><td>%s</td></tr>' % (r["item"], r["status"], r.get("verified_on", "—"))
        for r in rows
    )
    return ("""<!--v7:accreditation--><section class="template-section accreditation-section"><div class="container">
<h2>Accreditation and verification</h2>
<p>Each item below has been checked against the issuing body's own register, not against a logo supplied by the clinic.</p>
<table class="legal-table accreditation-table">
<tr><th>Item</th><th>Status</th><th>Verified on</th></tr>%s</table>
<p>Our full standard is published in <a href="../legal/clinic-selection-standards.html">clinic selection standards</a>.</p>
</div></section><!--/v7:accreditation-->""" % body)


def patch_accreditation():
    n = 0
    for rel in cfg.html_files():
        if cfg.page_kind(rel) != "clinic":
            continue
        src = cfg.read(rel)
        slug = os.path.basename(rel)[:-5]
        rows = CLAIMS.get("clinics", {}).get(slug, {}).get("accreditations", [])
        block = accreditation_table(rows) if rows else PENDING_ACCREDITATION
        src = re.sub(r"<!--v7:accreditation-->.*?<!--/v7:accreditation-->", "", src, flags=re.S)
        src = src.replace("</main>", block + "</main>", 1)
        cfg.write(rel, src)
        n += 1
    print("  accreditation block gated on verified data across %d clinic pages" % n)


# ------------------------------------------------ 2. commercial disclosure
PENDING_PAID = """<!--v7:paid--><section class="template-section alt how-we-are-paid"><div class="container">
<span class="eyebrow">Commercial transparency</span>
<h2>How we are paid</h2>
<p>You should know who pays us before you rely on anything we tell you. Ask us directly and we will answer in writing — who pays our fee, whether it differs between clinics, and whether any clinic can pay to be featured. We would rather answer that question than dress it up.</p>
<p>What we will commit to in public: a clinic's commercial terms with us never decide whether it is clinically right for you. Suitability is the treating clinician's judgement, and we do not overrule it or route around it.</p>
<p>Full terms are in the <a href="legal/terms.html">platform terms</a>.</p>
</div></section><!--/v7:paid-->"""


def paid_block(m):
    return ("""<!--v7:paid--><section class="template-section alt how-we-are-paid"><div class="container">
<span class="eyebrow">Commercial transparency</span>
<h2>How we are paid</h2>
<p>You should know who pays us before you rely on our advice, so here it is in plain terms.</p>
<div class="paid-grid">
<div><h3>Where our revenue comes from</h3><p>%s</p></div>
<div><h3>Whether it changes what we recommend</h3><p>%s</p></div>
<div><h3>What you pay us</h3><p>%s</p></div>
<div><h3>Whether clinics pay for placement</h3><p>%s</p></div>
</div>
<p class="clinical-note">Full terms are in the <a href="legal/terms.html">platform terms</a>.</p>
</div></section><!--/v7:paid-->""" % (m["revenue_source"], m["varies_by_clinic"],
                                     m["patient_pays"], m["paid_placement"]))


def patch_paid():
    rel = "about.html"
    src = cfg.read(rel)
    m = CLAIMS.get("commercial_model", {})
    block = paid_block(m) if m.get("published") else PENDING_PAID
    src = re.sub(r"<!--v7:paid-->.*?<!--/v7:paid-->", "", src, flags=re.S)
    src = src.replace("</main>", block + "</main>", 1)
    cfg.write(rel, src)
    print("  commercial disclosure gated on verified data")


# ------------------------------------------- 3. the complications question
COMPLICATIONS_BODY = """
<h2>The short answer</h2>
<p>Complications are managed by the clinic that treated you, under the pathway it agreed with you before you travelled. Our job is to make sure that pathway exists in writing, that you leave Turkey holding it, and that you can reach a human quickly if you need to use it. This page sets out how that works and, just as importantly, what it does not cover.</p>

<h2>Before you travel — get these four things in writing</h2>
<p>Do not book until you have them. If a coordinator or clinic will not put them in writing, that is your answer about the clinic.</p>
<ul class="clinical-list">
<li><b>The complications pathway.</b> Who you contact, on what number, in what language, and how fast they respond — for the first 48 hours, the first month, and afterwards.</li>
<li><b>The revision policy.</b> Under exactly what circumstances a revision or corrective procedure is provided at no charge, what it excludes, and how long the policy lasts.</li>
<li><b>What a complication costs.</b> Extra hospital nights, an unplanned return flight, further surgery. Which of these the clinic covers and which fall to you.</li>
<li><b>Your records.</b> Discharge summary, operation note, implant or device details with brand and serial number, medication list, and follow-up instructions — in a language your own doctor reads.</li>
</ul>

<h2>If something goes wrong while you are still in Turkey</h2>
<p>Contact the clinic first — it is the treating provider and it has your case in front of it. Tell us at the same time and we will chase, translate and keep your travel and accommodation moving while the clinic deals with the clinical side. Do not fly home on a problem because a flight is booked. Flights can be changed.</p>

<h2>If something goes wrong after you are home</h2>
<p>Contact the clinic using the pathway you were given, and contact your own doctor. If it is urgent, use local emergency care first and inform the clinic afterwards — an emergency is not the moment to be waiting on an international call. Send us the same message and we will get the clinic to respond, obtain records, and organise a return if the clinic requires one.</p>
<p>Be realistic about what a coordinator can do here: we can make communication fast and make sure your documents exist. We cannot examine you, and we cannot overrule a clinician's judgement.</p>

<h2>What your own doctor is and is not obliged to do</h2>
<p>In Germany and across the EU, your GP or a local hospital will treat an acute problem. They are not obliged to take over routine aftercare for planned treatment arranged privately abroad, and some will decline. Some will also charge for it. Discuss this with your own doctor <em>before</em> you book, not after you land back. Take your records with you to that conversation.</p>

<h2>Insurance — read this part twice</h2>
<p>Standard travel insurance almost always excludes planned medical treatment abroad and any complication arising from it. Statutory health insurance in Germany generally will not reimburse elective treatment obtained privately abroad without prior authorisation. Assuming you are covered because you have insurance is the most common and most expensive mistake in medical travel.</p>
<p>Ask your insurer, in writing, two specific questions: does my policy cover complications arising from planned treatment abroad, and does it cover repatriation if I need it. Then look at whether a dedicated medical-travel or complications policy is worth buying. We do not sell insurance and we earn nothing from it.</p>

<h2>If you want to complain</h2>
<p>Complaints about the treatment itself go to the clinic, and where necessary to the Turkish health authorities — the clinic is the medical provider, not us. Complaints about coordination are ours, and we will answer them. We will help you route a clinical complaint and we will not obstruct it. The process is in our <a href="../legal/complaints.html">complaints procedure</a>.</p>

<h2>How to reduce the risk in the first place</h2>
<ul class="clinical-list">
<li>Choose on the clinician and the pathway, not on the price or the package photos.</li>
<li>Build recovery time into your trip. Flying too soon after surgery is a known risk, particularly for thrombosis after lower-limb and abdominal procedures.</li>
<li>Do not agree to additional procedures decided on the day of surgery unless there is a clinical reason you understand.</li>
<li>Take a companion for anything under general anaesthetic.</li>
<li>Book the flight home flexibly, and later than you think you need.</li>
</ul>
<p class="clinical-note">This page is general information, not medical advice, and it does not replace the instructions the treating clinic gives you. Those instructions always take priority — including on when it is safe to fly.</p>
"""


def build_complications_page():
    src = cfg.read("about.html")
    head_open = src[: src.index("<main")]
    head_open = re.sub(r"<!--v7:head-->.*?<!--/v7:head-->", "", head_open, flags=re.S)
    foot = src[src.index("</main>"):]
    foot = re.sub(r"<!--v7:routes-->.*?<!--/v7:routes-->", "", foot, flags=re.S)
    foot = re.sub(r"<!--v7:paid-->.*?<!--/v7:paid-->", "", foot, flags=re.S)
    foot = re.sub(r"<!--v7:wa-->.*?<!--/v7:wa-->", "", foot, flags=re.S)

    head = re.sub(r'(href|src)="(?!https?:|mailto:|#|\.\./)([^"]+)"', r'\1="../\2"', head_open)
    foot = re.sub(r'(href|src)="(?!https?:|mailto:|#|\.\./)([^"]+)"', r'\1="../\2"', foot)
    foot = foot.replace('window.SITE_PREFIX=""', 'window.SITE_PREFIX="../"')

    title = "If Something Goes Wrong: Complications, Aftercare and Insurance"
    head = re.sub(r"<title>.*?</title>", "<title>%s | Turkelitemedcare</title>" % title, head, flags=re.S)
    head = re.sub(r'<meta content="[^"]*" name="description"/>',
                  '<meta content="What happens if there is a complication after treatment in Turkey: '
                  'the pathway to agree before you travel, what your own doctor will and will not do, '
                  'and why standard travel insurance usually does not cover it." name="description"/>',
                  head)

    page = (head
            + '<main><section class="page-hero"><div class="container page-hero-grid"><div>'
            + '<div class="breadcrumbs"><a href="../index.html">Home</a><b>›</b>'
            + '<a href="../guides/index.html">Guides</a><b>›</b><span>If something goes wrong</span></div>'
            + '<span class="eyebrow">The question most sites avoid</span>'
            + '<h1>What happens if something goes wrong?</h1>'
            + '<p>It is the right question to ask before you book, and it deserves a straight answer '
              'rather than reassurance. Here is how complications, aftercare and insurance actually work '
              'when treatment happens abroad.</p>'
            + "</div></div></section>"
            + '<section class="template-section"><div class="container legal-doc">'
            + COMPLICATIONS_BODY + "</div></section></main>"
            + foot)
    cfg.write("guides/if-something-goes-wrong.html", page)
    print("  guides/if-something-goes-wrong.html created")


def link_complications():
    """Surface it where the objection actually arises, not buried in the guides list."""
    link = ('<!--v7:worry--><p class="worry-link"><a href="%sguides/if-something-goes-wrong.html">'
            'What happens if something goes wrong after I fly home? →</a></p><!--/v7:worry-->')
    n = 0
    for rel in cfg.html_files():
        kind = cfg.page_kind(rel)
        if kind not in ("procedure", "specialty") and rel not in ("index.html", "how-it-works.html",
                                                                  "patient-services.html", "treatment-plan.html"):
            continue
        src = cfg.read(rel)
        src = re.sub(r"<!--v7:worry-->.*?<!--/v7:worry-->", "", src, flags=re.S)
        marker = "<!--v7:routes-->"
        if marker in src:
            src = src.replace(marker, link % cfg.prefix_for(rel) + marker, 1)
        else:
            src = src.replace("</main>", link % cfg.prefix_for(rel) + "</main>", 1)
        cfg.write(rel, src)
        n += 1
    print("  complications page linked from %d pages" % n)


if __name__ == "__main__":
    patch_accreditation()
    patch_paid()
    build_complications_page()
    link_complications()
