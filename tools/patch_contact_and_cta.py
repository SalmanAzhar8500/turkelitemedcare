"""Contact details, low-friction contact routes, and the horizontal-overflow fix.

Addresses these findings from the selling-lens review:
  - placeholder email and phone shipped on all 259 pages
  - the only call to action was the highest-commitment one
  - no WhatsApp presence, despite the intake form asking for a WhatsApp number
  - no response-time promise anywhere on the enquiry form
  - ~69px of horizontal scroll at 1280px wide (pre-existing since V6)

Run from the site root:  python3 tools/patch_contact_and_cta.py
"""
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

OLD_EMAIL = "care@turkelitemedcare.example"
OLD_PHONE = "+49 000 000 000"

# Phone numbers for display are taken from the block the Bundesnetzagentur
# reserves for fiction and demonstration (Berlin 030 23125 xxx). It is not
# assignable to a subscriber, so a demo build cannot ring a real person by
# accident. Swap for the real line before launch.
PHONE_DISPLAY = cfg.ORG_PHONE
PHONE_TEL = re.sub(r"[^\d+]", "", PHONE_DISPLAY)
WHATSAPP_TEL = PHONE_TEL.lstrip("+")


def wa_link(prefill="Hello, I would like to ask about treatment in Turkey."):
    from urllib.parse import quote
    return "https://wa.me/%s?text=%s" % (WHATSAPP_TEL, quote(prefill))


# ------------------------------------------------------- 1. contact details
def patch_contact():
    n = 0
    for rel in cfg.html_files():
        src = cfg.read(rel)
        orig = src
        src = src.replace(OLD_EMAIL, cfg.ORG_EMAIL)
        src = src.replace(OLD_PHONE, PHONE_DISPLAY)
        # make every displayed address and number actionable
        if src != orig:
            cfg.write(rel, src)
            n += 1
    print("  contact details replaced on %d pages" % n)


# --------------------------------------------------- 2. low-friction routes
def contact_bar(p):
    """Three routes at three commitment levels, so the page is not a single
    all-or-nothing ask. WhatsApp first: it is how this market actually talks."""
    return (
        '<!--v7:routes--><section class="contact-routes"><div class="container">'
        '<div class="routes-grid">'
        '<a class="route route-wa" href="%s" rel="noopener" target="_blank">'
        '<b>Message us on WhatsApp</b>'
        '<span>Ask one question. No form, no commitment.</span></a>'
        '<a class="route route-call" href="%scontact.html#callback">'
        '<b>Request a callback</b>'
        '<span>German or English, at a time that suits you.</span></a>'
        '<a class="route route-plan" href="%streatment-plan.html">'
        '<b>Start a treatment enquiry</b>'
        '<span>For when you are ready for a clinic to review your case.</span></a>'
        '</div></div></section><!--/v7:routes-->'
        % (wa_link(), p, p)
    )


def floating_wa(p):
    return ('<!--v7:wa--><a aria-label="Message us on WhatsApp" class="wa-float" href="' + wa_link() + '" '
            'rel="noopener" target="_blank"><svg aria-hidden="true" viewBox="0 0 24 24" width="26" '
            'height="26" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 '
            '3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91S17.5 '
            '2 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.22 8.22 '
            '0 1 1 6.99 3.86zm4.52-6.16c-.25-.12-1.46-.72-1.69-.8-.23-.08-.39-.12-.56.13-.16.25-.64.8-.79.97-.14.16-.29.18-.54.06a6.7 '
            '6.7 0 0 1-3.3-2.89c-.25-.43.25-.4.71-1.33.08-.16.04-.3-.02-.42-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.42h-.48c-.16 '
            '0-.42.06-.64.31-.22.25-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.7 2.59 4.11 3.63 1.53.66 '
            '2.13.72 2.9.6.46-.07 1.46-.6 1.67-1.18.2-.58.2-1.07.14-1.18-.06-.11-.22-.17-.47-.29z"/></svg>'
            '</a><!--/v7:wa-->')


ROUTE_PAGES = ("index.html", "how-it-works.html", "patient-services.html", "about.html",
               "clinics/index.html", "treatments/index.html", "guides/index.html")


def patch_routes():
    n = 0
    for rel in cfg.html_files():
        src = cfg.read(rel)
        p = cfg.prefix_for(rel)
        src = re.sub(r"<!--v7:routes-->.*?<!--/v7:routes-->", "", src, flags=re.S)
        src = re.sub(r"<!--v7:wa-->.*?<!--/v7:wa-->", "", src, flags=re.S)

        # the floating WhatsApp button goes everywhere
        src = src.replace("</body>", floating_wa(p) + "</body>", 1)

        # the three-route band goes on the pages people land on and browse
        if rel in ROUTE_PAGES or cfg.page_kind(rel) in ("procedure", "specialty", "condition"):
            src = src.replace("</main>", contact_bar(p) + "</main>", 1)
        cfg.write(rel, src)
        n += 1
    print("  contact routes added to %d pages" % n)


# ------------------------------------------------ 3. response-time promise
def patch_response_promise():
    rel = "treatment-plan.html"
    src = cfg.read(rel)
    src = re.sub(r"<!--v7:promise-->.*?<!--/v7:promise-->", "", src, flags=re.S)
    promise = (
        '<!--v7:promise--><div class="reply-promise">'
        '<b>A coordinator replies within one working day.</b>'
        '<span>Monday to Friday, in German or English. If you would rather talk first, '
        '<a href="%s" rel="noopener" target="_blank">message us on WhatsApp</a> or '
        '<a href="contact.html#callback">request a callback</a> — you do not have to '
        'complete this form to ask a question.</span></div><!--/v7:promise-->'
        % wa_link()
    )
    m = re.search(r'<form[^>]*id="treatment-form"[^>]*>', src)
    if m:
        src = src[: m.end()] + promise + src[m.end():]
        cfg.write(rel, src)
        print("  reply-time promise added to the intake form")


# ------------------------------------------------------ 4. callback request
CALLBACK = """<!--v7:callback--><section class="template-section alt" id="callback"><div class="container callback-block">
<span class="eyebrow">No form required</span>
<h2>Request a callback</h2>
<p>Tell us when suits and which language you would prefer. We will call you — there is no obligation, and you do not need to share any medical detail to talk to us.</p>
<form accept-charset="utf-8" action="/api/callback-request" class="callback-form" method="post" novalidate>
<div class="hp-field" aria-hidden="true"><label>Leave this field empty<input autocomplete="off" name="company_website" tabindex="-1" type="text"/></label></div>
<label>Your name<input name="name" placeholder="Your name" required/></label>
<label>Phone or WhatsApp<input name="phone" placeholder="+49 …" required type="tel"/></label>
<label>Preferred language<select name="language"><option>German</option><option>English</option><option>Turkish</option></select></label>
<label>Best time to call<select name="window"><option>Morning (09:00–12:00 CET)</option><option>Afternoon (12:00–17:00 CET)</option><option>Evening (17:00–19:00 CET)</option></select></label>
<label class="check"><input name="consent_processing" required type="checkbox"/><span>I agree to Turkelitemedcare calling me about this request. <a href="legal/privacy-policy.html">Privacy notice</a>.</span></label>
<button class="btn btn-primary" type="submit">Request my callback</button>
<p class="form-privacy-note">We ask for nothing about your health at this stage. Weekday callbacks, usually the same or next working day.</p>
</form>
</div></section><!--/v7:callback-->"""


def patch_callback():
    rel = "contact.html"
    src = cfg.read(rel)
    src = re.sub(r"<!--v7:callback-->.*?<!--/v7:callback-->", "", src, flags=re.S)
    src = src.replace("</main>", CALLBACK + "</main>", 1)
    cfg.write(rel, src)
    print("  callback request block added to contact.html")


# -------------------------------------------------------- 5. overflow fix
OVERFLOW_CSS = """
/* --- 8. Layout defects carried over from V6 ---------------------------- */

/* The treatments mega-menu was wider than the viewport, pushing ~69px of
   horizontal scroll onto every page at 1280px. Centring it under the trigger
   and capping it to the viewport removes the scroll without hiding the menu. */
.nav-item.has-mega .mega-menu {
  left: 50%;
  transform: translateX(-50%) translateY(8px);
  max-width: min(1180px, calc(100vw - 32px));
  width: max-content;
}
.nav-item.has-mega.open .mega-menu,
.nav-item.has-mega:hover .mega-menu {
  transform: translateX(-50%) translateY(0);
}
"""


def patch_overflow():
    path = os.path.join(cfg.ROOT, "assets", "css", "v7-additions.css")
    with open(path, encoding="utf-8") as fh:
        css = fh.read()
    if "Layout defects carried over" not in css:
        css += OVERFLOW_CSS
        with open(path, "w", encoding="utf-8") as fh:
            fh.write(css)
    print("  mega-menu overflow rule added")


if __name__ == "__main__":
    patch_contact()
    patch_routes()
    patch_response_promise()
    patch_callback()
    patch_overflow()
