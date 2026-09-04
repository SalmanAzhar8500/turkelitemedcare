"""Fix form data handling and generate the legal document set.

What it changes
---------------
1. Procedure sidebar forms stop collecting name/email/phone over GET. They become a
   context handoff (procedure + specialty + country) so no personal identifier ever
   reaches a URL, a server log, a referrer header or browser history.
2. The main intake form POSTs to a configurable endpoint instead of staying in the
   browser, and gains GDPR Art. 6 / Art. 9 consent, a privacy-notice link and an
   international-transfer acknowledgement.
3. All forms gain a honeypot field and an accessible required-field convention.
4. Generates /legal/*.html drafts and rewires legal.html to link them.

Run from the site root:  python3 tools/patch_forms_and_legal.py
"""
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

FORM_ENDPOINT = "/api/consultation-request"   # <-- point at the secured CRM endpoint

HONEYPOT = ('<div class="hp-field" aria-hidden="true">'
            '<label>Leave this field empty<input autocomplete="off" name="company_website" '
            'tabindex="-1" type="text"/></label></div>')


# --------------------------------------------------------- 1. procedure forms
def patch_procedure_forms():
    n = 0
    for rel in cfg.html_files():
        if cfg.page_kind(rel) != "procedure":
            continue
        src = cfg.read(rel)
        m = re.search(r'<form action="[^"]*" class="consultation-mini-form-v6" method="get">.*?</form>', src, re.S)
        if not m:
            continue
        p = cfg.prefix_for(rel)
        proc = re.search(r'<input name="procedure" type="hidden" value="([^"]*)"', m.group(0))
        spec = re.search(r'<input name="specialty" type="hidden" value="([^"]*)"', m.group(0))
        new = (
            '<form action="%streatment-plan.html" class="consultation-mini-form-v6" method="get">'
            '<input name="procedure" type="hidden" value="%s"/>'
            '<input name="specialty" type="hidden" value="%s"/>'
            '<label for="mini-country">Your country</label>'
            '<select id="mini-country" name="country">'
            "<option>Germany</option><option>United Kingdom</option><option>Netherlands</option>"
            "<option>Switzerland</option><option>Austria</option><option>Other</option></select>"
            '<button class="btn btn-primary" type="submit">Continue to consultation request</button>'
            '<p class="form-privacy-note">We ask for your contact details on the next step, over a secure '
            "connection. Nothing you type here is sent yet.</p>"
            "</form>"
            % (p, proc.group(1) if proc else "", spec.group(1) if spec else "")
        )
        src = src[: m.start()] + new + src[m.end():]
        cfg.write(rel, src)
        n += 1
    print("  procedure forms rewritten: %d" % n)


# ------------------------------------------------------------ 2. intake form
CONSENT_BLOCK = """<fieldset class="consent-block"><legend>Before you send this</legend>
<label class="check"><input name="consent_processing" required type="checkbox"/><span>I agree that Turkelitemedcare may process the details above to prepare my enquiry and contact me about it. <a href="legal/privacy-policy.html">Privacy notice</a>.</span></label>
<label class="check"><input name="consent_health" required type="checkbox"/><span><b>Explicit consent for health information.</b> I understand the box above may contain information about my health, and I explicitly consent to Turkelitemedcare processing it and sharing it with the partner clinic I am matched with, so that clinic can assess my case. (GDPR Art. 9(2)(a).)</span></label>
<label class="check"><input name="consent_transfer" required type="checkbox"/><span>I understand that partner clinics are located in Turkey, which is outside the EU/EEA and is not covered by an EU adequacy decision, and that my information will be transferred there under the safeguards described in the <a href="legal/privacy-policy.html#transfers">privacy notice</a>.</span></label>
<label class="check"><input name="ack_not_advice" required type="checkbox"/><span>I understand this enquiry does not provide medical advice, does not create a doctor–patient relationship and is not for emergencies.</span></label>
<p class="form-privacy-note">You can withdraw consent at any time by emailing <a href="mailto:{email}">{email}</a>. Withdrawal does not affect processing already carried out. We keep enquiry records for the period set out in the privacy notice, then delete them.</p>
</fieldset>"""


def patch_intake_form():
    rel = "treatment-plan.html"
    src = cfg.read(rel)

    src = src.replace(
        '<form class="intake-form" id="treatment-form">',
        '<form accept-charset="utf-8" action="%s" class="intake-form" id="treatment-form" '
        'method="post" novalidate>' % FORM_ENDPOINT,
        1,
    )

    # replace the single thin acknowledgement with the full consent set
    old = re.search(r'<label class="check"><input required=""? type="checkbox"/><span>.*?</span></label>', src, re.S)
    if old:
        src = src[: old.start()] + HONEYPOT + CONSENT_BLOCK.format(email=cfg.ORG_EMAIL) + src[old.end():]

    # be explicit about what the free-text box should and should not contain
    src = src.replace(
        'placeholder="Previous treatment, relevant diagnosis, timing, questions..."',
        'placeholder="Previous treatment, relevant diagnosis, timing, questions..." '
        'aria-describedby="details-help"',
        1,
    )
    src = src.replace(
        "</textarea></label>",
        '</textarea><small id="details-help">Optional. Share only what helps a clinic understand your '
        "enquiry — you do not need to upload records or scans at this stage. If a clinic needs them, we "
        "will send you a secure upload link.</small></label>",
        1,
    )

    src = src.replace(
        "<p>This presentation build keeps the request in the browser. Production deployment should "
        "connect the form to the secure patient-coordination system.</p>",
        "<p>Your request has been sent. A coordinator will reply within one working day. You will receive "
        "a copy by email, including how to withdraw consent or ask us to delete the enquiry.</p>",
        1,
    )
    cfg.write(rel, src)
    print("  intake form: POST + Art. 6/Art. 9 consent + transfer notice")


def patch_other_forms():
    """Home and contact quick forms: no personal data without a consent tick."""
    short_consent = (
        '<label class="check"><input name="consent_processing" required type="checkbox"/><span>'
        "I agree to Turkelitemedcare contacting me about this enquiry. "
        '<a href="%slegal/privacy-policy.html">Privacy notice</a>.</span></label>'
    )
    n = 0
    for rel in ("index.html", "contact.html", "for-clinics.html", "about.html"):
        if not os.path.exists(os.path.join(cfg.ROOT, rel)):
            continue
        src = cfg.read(rel)
        if "consent_processing" in src:
            continue
        changed = False
        for cls in ("home-route-form", "demo-form", "quick-question"):
            pat = re.compile(r'(<form[^>]*class="[^"]*%s[^"]*"[^>]*>)(.*?)(<button[^>]*type="submit")' % cls, re.S)
            def repl(m):
                # A form can carry more than one of the matched classes; without
                # this guard it collects a consent block per class it matches.
                if "consent_processing" in m.group(2):
                    return m.group(0)
                return m.group(1) + m.group(2) + HONEYPOT + (short_consent % cfg.prefix_for(rel)) + m.group(3)
            src, k = pat.subn(repl, src)
            changed = changed or bool(k)
        if changed:
            cfg.write(rel, src)
            n += 1
    print("  secondary forms given consent checkbox: %d pages" % n)


# --------------------------------------------------------------- 3. legal set
def chrome():
    """Header and footer lifted from about.html so generated pages match the site."""
    src = cfg.read("about.html")
    head_open = src[: src.index("<main")]
    # strip the injected v7 head block if patch_head already ran; it is re-added later
    head_open = re.sub(r"<!--v7:head-->.*?<!--/v7:head-->", "", head_open, flags=re.S)
    foot = src[src.index("</main>"):]
    return head_open, foot


LEGAL_PAGES = {
    "impressum": ("Impressum / Legal notice", """
<h2>Provider identification</h2>
<p>This page must satisfy §5 Telemediengesetz (TMG) and §18 Medienstaatsvertrag before the site is published in Germany. Complete every field below with the registered details of the operating entity.</p>
<table class="legal-table">
<tr><th>Operating entity</th><td>[Registered company name, legal form]</td></tr>
<tr><th>Registered address</th><td>[Street, postcode, city, country — no PO box]</td></tr>
<tr><th>Represented by</th><td>[Managing director(s)]</td></tr>
<tr><th>Contact</th><td>[Telephone] · [Email] · [Contact form URL]</td></tr>
<tr><th>Commercial register</th><td>[Register court and number]</td></tr>
<tr><th>VAT ID</th><td>[USt-IdNr. under §27a UStG]</td></tr>
<tr><th>Responsible for content</th><td>[Name and address under §18(2) MStV]</td></tr>
<tr><th>Supervisory authority</th><td>[If the activity is licensed — state the authority]</td></tr>
<tr><th>Professional liability insurance</th><td>[Insurer, geographic scope of cover]</td></tr>
</table>
<h2>EU online dispute resolution</h2>
<p>State the position on the EU ODR platform and on participation in consumer arbitration under the Verbraucherstreitbeilegungsgesetz.</p>
<h2>What this business is and is not</h2>
<p>Turkelitemedcare coordinates medical travel. It does not provide medical treatment, does not employ the treating clinicians and does not hold a healthcare provider licence. Medical care is delivered by independent licensed clinics in Turkey, which are separately responsible for assessment, consent, treatment and clinical aftercare.</p>
"""),

    "privacy-policy": ("Privacy notice", """
<h2>Draft status</h2>
<p class="legal-flag">This is a structural draft. It must be completed and reviewed by a data protection lawyer, and a Data Protection Impact Assessment must be carried out, before any real enquiry is collected. Health data is special-category data under Article 9 GDPR and attracts the strictest obligations in the regulation.</p>

<h2>1. Who is responsible</h2>
<p>[Controller name, address, contact]. Data Protection Officer: [name and contact, or a documented assessment that Art. 37 does not require one — note that large-scale processing of health data usually does require one].</p>

<h2>2. What we collect</h2>
<ul>
<li><b>Enquiry details:</b> name, email, telephone, country, preferred language, specialty and procedure of interest, travel window.</li>
<li><b>Health information you choose to share:</b> free-text description of your condition, previous treatment, and any records you later upload at a clinic's request. This is special-category data under Art. 9(1).</li>
<li><b>Technical data:</b> IP address, device and browser information, and pages viewed. See the cookie policy.</li>
</ul>

<h2>3. Why we process it, and on what basis</h2>
<table class="legal-table">
<tr><th>Purpose</th><th>Lawful basis</th></tr>
<tr><td>Responding to your enquiry and coordinating your journey</td><td>Art. 6(1)(b) — steps prior to a contract</td></tr>
<tr><td>Processing health information and passing it to a partner clinic</td><td>Art. 9(2)(a) — your explicit consent</td></tr>
<tr><td>Service emails about your enquiry</td><td>Art. 6(1)(b)</td></tr>
<tr><td>Marketing email, where you have opted in</td><td>Art. 6(1)(a) — consent, withdrawable at any time</td></tr>
<tr><td>Security, fraud prevention and record-keeping</td><td>Art. 6(1)(f) — legitimate interests, balanced in [reference the LIA]</td></tr>
</table>

<h2 id="transfers">4. Transfers outside the EU/EEA</h2>
<p>Partner clinics are in Turkey. Turkey is not covered by a European Commission adequacy decision. Transfers therefore rely on Article 46 safeguards: [state which — Standard Contractual Clauses, plus the transfer impact assessment and any supplementary measures]. Because this involves special-category data, the assessment and the supplementary measures must be documented and available on request. You may request a copy of the safeguards at [contact].</p>

<h2>5. Who receives your information</h2>
<ul>
<li>The partner clinic you are matched with, once you have given explicit consent.</li>
<li>Processors: [CRM], [email provider], [hosting], [analytics] — each under an Art. 28 agreement.</li>
<li>Travel and accommodation providers, limited to what a booking requires. Health information is never included.</li>
</ul>

<h2>6. How long we keep it</h2>
<p>[State a concrete period per category — for example, enquiries that do not proceed deleted after X months; coordinated journeys retained for Y years for liability and accounting reasons.] Health information is deleted or returned when the purpose ends, unless a legal retention duty applies.</p>

<h2>7. Your rights</h2>
<p>Access, rectification, erasure, restriction, portability, and objection to processing based on legitimate interests (Arts. 15–21). Where processing rests on consent, you may withdraw it at any time without affecting prior processing. To exercise any right, contact [address]. You may complain to a supervisory authority — in Germany, the authority for the state where the controller is established.</p>

<h2>8. Automated decision-making</h2>
<p>[Confirm whether clinic matching involves automated decision-making or profiling under Art. 22, and if so describe the logic and the right to human intervention.]</p>

<h2>9. Security</h2>
<p>[Describe encryption in transit and at rest, access control, the secure upload route for medical records, breach detection and the 72-hour notification process under Art. 33.]</p>
"""),

    "cookie-policy": ("Cookie policy", """
<h2>Draft status</h2>
<p class="legal-flag">Consent must be collected before any non-essential cookie or tracker loads. In Germany this is governed by §25 TDDDG as well as the GDPR. A pre-ticked box, a cookie wall or an "accept" button without an equally prominent "reject" button does not meet the standard.</p>
<h2>Categories in use</h2>
<table class="legal-table">
<tr><th>Category</th><th>Purpose</th><th>Consent needed</th></tr>
<tr><td>Strictly necessary</td><td>Session handling, security, load balancing, storing the consent choice itself</td><td>No</td></tr>
<tr><td>Preferences</td><td>Language selection</td><td>Yes</td></tr>
<tr><td>Analytics</td><td>[Name the tool, its retention period and whether IPs are truncated]</td><td>Yes</td></tr>
<tr><td>Marketing</td><td>[Name each pixel or remarketing tag]</td><td>Yes</td></tr>
</table>
<h2>Third-party embeds</h2>
<p>Procedure videos use <code>youtube-nocookie.com</code>, which does not set tracking cookies until playback starts. Even so, the embed should be gated behind a click-to-load placeholder so no request reaches Google before consent. Fonts are loaded from Google Fonts, which discloses the visitor's IP address to Google — a German court has held this to be a GDPR infringement without consent. <b>Self-host the Manrope and Open Sans files before launch.</b></p>
<h2>Managing your choice</h2>
<p>[Link the consent management platform's re-open control here.]</p>
"""),

    "terms": ("Platform terms", """
<h2>Draft status</h2>
<p class="legal-flag">Structural draft for legal review in Germany, the EU and Turkey.</p>
<h2>1. What Turkelitemedcare provides</h2>
<p>Turkelitemedcare provides coordination services: preparing and routing enquiries, organising communication with partner clinics, clarifying quotations, sequencing appointments and travel, and organising the practical follow-up handover. It does not provide medical services.</p>
<h2>2. What the clinic provides</h2>
<p>The treating clinic is an independent contracting party. It is solely responsible for clinical assessment, suitability decisions, informed consent, the treatment itself, complications, and clinical aftercare. Any treatment contract is between you and the clinic, not with Turkelitemedcare.</p>
<h2>3. Fees and how we are paid</h2>
<p>[State clearly: whether the patient pays a coordination fee, whether Turkelitemedcare receives a commission or referral fee from partner clinics, and how that is calculated. Disclose it here and on the "How we are paid" section of the About page. Undisclosed commission is both a trust problem and, in several markets, a regulatory one.]</p>
<h2>4. Quotations</h2>
<p>Quotations are issued by the clinic and are indicative until the clinic confirms them after assessment. Explain what triggers a change, and who bears the cost when a plan changes after travel is booked.</p>
<h2>5. Cancellation and withdrawal</h2>
<p>[Set out the statutory 14-day withdrawal right for distance contracts under §§312g, 355 BGB, when it applies, and the consequences of cancelling after travel is booked.]</p>
<h2>6. Liability</h2>
<p>[Define the limits of the coordinator's liability and state plainly that it does not extend to clinical outcomes. This clause must be drafted by a lawyer — over-broad exclusions are void under German law.]</p>
<h2>7. Complaints</h2>
<p>See the <a href="complaints.html">complaints process</a>.</p>
<h2>8. Governing law and jurisdiction</h2>
<p>[State the governing law and the competent courts, and note the mandatory consumer protections that cannot be contracted away.]</p>
"""),

    "medical-disclaimer": ("Medical disclaimer", """
<h2>This site does not give medical advice</h2>
<p>Everything on this website is general patient education. It is not a diagnosis, not a treatment recommendation and not a substitute for assessment by a qualified clinician who has examined you and reviewed your records.</p>
<h2>No doctor–patient relationship</h2>
<p>Submitting an enquiry, receiving a reply from a coordinator or reading any page here does not create a doctor–patient relationship with Turkelitemedcare or with any clinician.</p>
<h2>Emergencies</h2>
<p>This service is not for emergencies and is not monitored around the clock. If you have symptoms that worry you, contact your own doctor or your local emergency number — 112 across the EU, 112 in Turkey.</p>
<h2>Suitability is decided by the treating clinic</h2>
<p>Whether a procedure is appropriate for you is a clinical judgement made by the independent treating clinician after assessment. Nothing on this site should be read as confirmation that you are a candidate for any procedure.</p>
<h2>Outcomes are not guaranteed</h2>
<p>Individual results vary. Recovery times, risks and outcomes described on procedure pages are general patterns drawn from published patient-education sources; they are not a prediction about your case and not a promise.</p>
<h2>How our content is prepared</h2>
<p>Procedure guides are written for patients and checked against recognised professional and patient-education bodies. Pages that have been signed off by a named clinician carry a review byline with the reviewer and review date. Pages still awaiting sign-off say so plainly and are excluded from search engine indexing until they are reviewed.</p>
<h2>Reporting a problem with our content</h2>
<p>If you believe something here is inaccurate or misleading, write to [content contact]. We aim to acknowledge within [X] working days.</p>
"""),

    "complaints": ("Complaints process", """
<h2>Two different routes</h2>
<p>Complaints about <b>coordination</b> — communication, scheduling, quotations, transfers, accommodation, follow-up handover — are ours to resolve. Complaints about <b>clinical care</b> are handled by the treating clinic and, where applicable, by the Turkish health authorities, because the clinic is the medical provider. We will help you route a clinical complaint and will not obstruct it.</p>
<h2>How to raise a complaint with us</h2>
<ol>
<li>Email [complaints address] with your enquiry reference, or write to [postal address].</li>
<li>We acknowledge within [X] working days.</li>
<li>We aim to give a substantive response within [Y] working days, and tell you if we need longer.</li>
<li>If you are not satisfied, you may escalate to [named senior contact].</li>
</ol>
<h2>Escalating beyond us</h2>
<p>[List the consumer arbitration body, the ODR platform position, and the relevant data protection supervisory authority for privacy complaints.]</p>
<h2>Clinical complaints in Turkey</h2>
<p>[Set out the partner clinic's own complaints route, the Turkish Ministry of Health patient-rights channel (SABİM/HSGM) and how records are obtained. Confirm with legal counsel before publishing specifics.]</p>
<h2>What we record</h2>
<p>We log every complaint, the outcome and any change made as a result, and review the log at [frequency] as part of partner-clinic monitoring.</p>
"""),

    "clinic-selection-standards": ("Clinic selection standards", """
<h2>Why this page exists</h2>
<p>A coordinator that will not say how it chooses clinics is asking patients to take the most important part of the decision on trust. This page sets out the standard. It should be published only once it is true, and audited against real partner files.</p>
<h2>Minimum requirements for a partner clinic</h2>
<ul>
<li>Current licence from the Turkish Ministry of Health for the facility and for the procedures offered.</li>
<li>Authorisation for international patient services where required, and registration under the relevant health-tourism framework.</li>
<li>Named treating clinicians, each verified against the Turkish Medical Association register, with specialty and years in practice recorded.</li>
<li>Documented informed-consent process available in the patient's language.</li>
<li>Written complications and readmission pathway, including what happens if a complication appears after the patient has flown home.</li>
<li>Professional indemnity cover, with scope and limits recorded.</li>
<li>Documented infection prevention and control policy.</li>
<li>Written aftercare and follow-up handover protocol.</li>
</ul>
<h2>Accreditation</h2>
<p>[Record for each partner: JCI accreditation status, ISO 9001 or ISO 15189 where relevant, Turkish Ministry of Health quality certification, and the date each was verified. Publish only accreditations that have been checked against the issuing body's own register — never a logo supplied by the clinic.]</p>
<h2>Ongoing monitoring</h2>
<p>[State the review cycle, the outcome and complaint measures tracked, and the circumstances in which a clinic is suspended or removed from the network.]</p>
<h2>Commercial relationships</h2>
<p>[Disclose whether clinics pay to be listed, whether placement or ordering is influenced by commercial terms, and how that is prevented from overriding clinical suitability.]</p>
"""),

    "patient-rights": ("Patient rights and provider responsibilities", """
<h2>What you are entitled to expect</h2>
<ul>
<li>A clear statement of who is treating you, their qualifications, and who is responsible for each part of your journey.</li>
<li>Information about your proposed treatment, its alternatives, its risks and its likely recovery, in a language you understand, with enough time to consider it.</li>
<li>Informed consent taken before treatment, not on the morning of surgery under time pressure.</li>
<li>An itemised quotation that distinguishes what is included from what is not.</li>
<li>Access to your own medical records and a written discharge summary before you travel home.</li>
<li>A named contact for questions after you return, and a written route for complications.</li>
<li>The right to change your mind, and the right to a second opinion.</li>
<li>Your information handled lawfully, and shared with a clinic only with your explicit consent.</li>
</ul>
<h2>Where responsibility sits</h2>
<table class="legal-table">
<tr><th>Area</th><th>Responsible party</th></tr>
<tr><td>Clinical assessment and suitability</td><td>Treating clinic and clinician</td></tr>
<tr><td>Informed consent</td><td>Treating clinician</td></tr>
<tr><td>The procedure and inpatient care</td><td>Treating clinic</td></tr>
<tr><td>Complications and clinical aftercare</td><td>Treating clinic</td></tr>
<tr><td>Medical records and discharge summary</td><td>Treating clinic</td></tr>
<tr><td>Enquiry handling and clinic communication</td><td>Turkelitemedcare</td></tr>
<tr><td>Scheduling, travel sequencing, transfers, accommodation</td><td>Turkelitemedcare</td></tr>
<tr><td>Follow-up handover to your doctor at home</td><td>Turkelitemedcare, with the clinic's clinical content</td></tr>
</table>
<h2>Continuity of care at home</h2>
<p>Your own doctor is not obliged to take over aftercare for treatment arranged abroad. Discuss this before you travel, and take a written discharge summary, operation note, implant details and medication list home with you.</p>
<h2>Insurance</h2>
<p>[State plainly that standard travel insurance usually excludes planned treatment abroad and its complications, and what cover patients should arrange.]</p>
"""),
}


def build_legal_pages():
    head_open, foot = chrome()
    os.makedirs(os.path.join(cfg.ROOT, "legal"), exist_ok=True)
    for slug, (title, body) in LEGAL_PAGES.items():
        head = head_open
        # retarget root-relative chrome links one level deeper
        head = re.sub(r'(href|src)="(?!https?:|mailto:|#|\.\./)([^"]+)"', r'\1="../\2"', head)
        foot_l = re.sub(r'(href|src)="(?!https?:|mailto:|#|\.\./)([^"]+)"', r'\1="../\2"', foot)
        foot_l = foot_l.replace('window.SITE_PREFIX=""', 'window.SITE_PREFIX="../"')

        head = re.sub(r"<title>.*?</title>", "<title>%s | Turkelitemedcare</title>" % title, head, flags=re.S)
        head = re.sub(
            r'<meta content="[^"]*" name="description"/>',
            '<meta content="%s for Turkelitemedcare medical travel coordination. Draft for legal review before launch." name="description"/>' % title,
            head,
        )
        page = (
            head
            + '<main><section class="page-hero"><div class="container page-hero-grid"><div>'
            + '<div class="breadcrumbs"><a href="../index.html">Home</a><b>›</b>'
            + '<a href="../legal.html">Legal &amp; Privacy</a><b>›</b><span>%s</span></div>' % title
            + '<span class="eyebrow">Trust &amp; compliance</span><h1>%s</h1>' % title
            + "<p>Draft prepared for legal review. Do not publish until reviewed and signed off.</p>"
            + "</div></div></section>"
            + '<section class="template-section"><div class="container legal-doc">'
            + body
            + '<p class="legal-flag">Last structural update: this draft. Replace every bracketed placeholder '
            "and obtain sign-off from qualified counsel in Germany, the EU and Turkey before publication.</p>"
            + "</div></section></main>"
            + foot_l
        )
        with open(os.path.join(cfg.ROOT, "legal", slug + ".html"), "w", encoding="utf-8") as fh:
            fh.write(page)
    print("  legal pages generated: %d" % len(LEGAL_PAGES))


def rewire_legal_index():
    rel = "legal.html"
    src = cfg.read(rel)
    links = "".join(
        '<a class="legal-index-link" href="legal/%s.html"><b>%s</b><span>Draft — pending legal review</span></a>'
        % (slug, title)
        for slug, (title, _) in LEGAL_PAGES.items()
    )
    block = (
        '<div class="legal-index-grid">' + links + "</div>"
        '<p class="legal-flag">Every document above is a structural draft. None may be published without '
        "review by qualified counsel in Germany, the EU and Turkey. The Impressum is a statutory requirement "
        "for publishing in Germany and the cookie policy cannot go live without a working consent banner.</p>"
    )
    m = re.search(r"<h2[^>]*>Pages to publish before launch</h2>(.*?)(?=<h2|</section>)", src, re.S)
    if m:
        src = src[: m.end(1)] + block + src[m.end(1):]
    else:
        src = src.replace("</main>", '<section class="template-section"><div class="container">'
                          + block + "</div></section></main>", 1)
    cfg.write(rel, src)
    print("  legal.html rewired to the document set")


if __name__ == "__main__":
    patch_procedure_forms()
    patch_intake_form()
    patch_other_forms()
    build_legal_pages()
    rewire_legal_index()
