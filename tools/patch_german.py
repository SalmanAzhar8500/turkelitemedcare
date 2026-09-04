"""A German entry point, plus hreflang and a language switcher that actually works.

The site targets Germany — DE/EN in the header, "Germany" prefilled in every form,
prices in euros — and had no German page at all, while the language switcher was a
dead `href="#"` on all 259 pages.

Translating 259 pages is a commissioned-translation job, not something to fake. So
this ships one genuine German entry point covering what a German patient needs
before they will talk to anyone: what the service is, who is responsible for the
medicine, the risk and insurance position, and how to make contact. Every link out
of it is labelled as leading to English pages, so nobody is misled about what they
will find.

Run from the site root:  python3 tools/patch_german.py
"""
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

from urllib.parse import quote

PHONE_DISPLAY = cfg.ORG_PHONE
WA = "https://wa.me/%s?text=%s" % (
    re.sub(r"[^\d]", "", PHONE_DISPLAY),
    quote("Guten Tag, ich habe eine Frage zu einer Behandlung in der Türkei."),
)

BODY = """
<section class="template-section"><div class="container legal-doc">

<h2>Was wir tun — und was wir ausdrücklich nicht tun</h2>
<p>Turkelitemedcare koordiniert medizinische Reisen in die Türkei. Wir organisieren die Kommunikation mit der Klinik, die Unterlagen, die Termine, die Anreise, die Unterkunft und die Übergabe der Nachsorge an Ihre Ärztin oder Ihren Arzt zu Hause.</p>
<p><b>Wir behandeln nicht.</b> Wir sind kein medizinischer Leistungserbringer, wir beschäftigen die behandelnden Ärztinnen und Ärzte nicht, und wir treffen keine medizinischen Entscheidungen. Die Behandlung erfolgt durch unabhängige, in der Türkei zugelassene Kliniken. Diese sind allein verantwortlich für Untersuchung, Aufklärung, Einwilligung, Behandlung, Komplikationen und die klinische Nachsorge. Der Behandlungsvertrag besteht zwischen Ihnen und der Klinik — nicht mit uns.</p>

<h2>Warum wir keine Festpreise veröffentlichen</h2>
<p>Ein Preis ohne die Liste der enthaltenen Leistungen ist die irreführendste Zahl in der Medizintourismus-Branche. Zwei Angebote, die identisch aussehen, decken regelmäßig unterschiedliche Implantate, unterschiedliche Anästhesie, unterschiedlich lange Aufenthalte und völlig unterschiedliche Nachsorge ab.</p>
<p>Wir schlüsseln stattdessen jedes Klinikangebot nach derselben Checkliste auf — enthalten, nicht enthalten, und was eine Komplikation kosten würde. Eine verbindliche Zahl kann es erst nach der klinischen Beurteilung geben. Jede Zahl davor ist eine Schätzung, und wir sagen Ihnen ausdrücklich, wann ein Angebot verbindlich ist und wann nicht.</p>

<h2>Wenn etwas schiefgeht</h2>
<p>Das ist die richtige Frage vor der Buchung. Klären Sie vier Punkte schriftlich, bevor Sie etwas buchen: den Komplikationsweg (wen Sie wann und in welcher Sprache erreichen), die Revisionsregelung, wer die Kosten einer Komplikation trägt, und Ihre vollständigen Unterlagen zur Mitnahme.</p>
<p><b>Wichtig zur Versicherung:</b> Reiseversicherungen schließen geplante Behandlungen im Ausland und deren Folgen fast immer aus. Die gesetzliche Krankenversicherung erstattet elektive Behandlungen im Ausland in der Regel nicht ohne vorherige Genehmigung. Fragen Sie Ihre Versicherung schriftlich, bevor Sie buchen. Wir verkaufen keine Versicherungen und verdienen daran nichts.</p>
<p>Ihre Hausärztin oder Ihr Hausarzt ist nicht verpflichtet, die reguläre Nachsorge einer privat im Ausland durchgeführten Behandlung zu übernehmen. Besprechen Sie das <em>vorher</em>.</p>

<h2>Ihre Daten</h2>
<p>Gesundheitsdaten sind besondere Kategorien personenbezogener Daten nach Art. 9 DSGVO. Wir holen dafür Ihre ausdrückliche Einwilligung ein und geben sie erst danach an eine Partnerklinik weiter. Die Türkei unterliegt keinem Angemessenheitsbeschluss der EU-Kommission; die Übermittlung erfolgt auf Grundlage der Garantien nach Art. 46 DSGVO. Sie können Ihre Einwilligung jederzeit widerrufen.</p>

<h2>So erreichen Sie uns — auf Deutsch</h2>
<p>Unser Team antwortet auf Deutsch. Sie müssen kein Formular ausfüllen und keine medizinischen Angaben machen, um uns eine Frage zu stellen.</p>
<div class="de-contact-grid">
<a class="route route-wa" href="%(wa)s" rel="noopener" target="_blank"><b>Per WhatsApp schreiben</b><span>Eine Frage genügt. Ohne Formular, ohne Verpflichtung.</span></a>
<a class="route route-call" href="../contact.html#callback"><b>Rückruf anfordern</b><span>Auf Deutsch, zu einer Zeit, die Ihnen passt.</span></a>
<a class="route route-plan" href="../treatment-plan.html"><b>Behandlungsanfrage starten</b><span>Wenn Sie bereit sind, dass eine Klinik Ihren Fall prüft.</span></a>
</div>
<p>E-Mail: <a href="mailto:%(email)s">%(email)s</a> · Telefon: <a href="tel:%(tel)s">%(phone)s</a> · Mo–Fr, 08:00–18:00 MEZ</p>

<h2 id="english">Die übrigen Seiten sind derzeit auf Englisch</h2>
<p class="legal-flag">Wir sagen das lieber offen, als Sie auf eine englische Seite zu schicken, die als deutsch angekündigt war. Die Behandlungs-, Klinik- und Rechtsseiten liegen aktuell nur auf Englisch vor. Die deutsche Fassung wird vorbereitet. Bis dahin: Unser Team beantwortet jede dieser Fragen auf Deutsch — schriftlich, wenn Sie möchten.</p>
<ul class="clinical-list">
<li><a href="../treatments/index.html">Fachbereiche und Behandlungen</a> <span class="lang-tag">englisch</span></li>
<li><a href="../clinics/index.html">Partnerkliniken</a> <span class="lang-tag">englisch</span></li>
<li><a href="../how-it-works.html">Ablauf einer koordinierten Behandlungsreise</a> <span class="lang-tag">englisch</span></li>
<li><a href="../guides/if-something-goes-wrong.html">Komplikationen, Nachsorge und Versicherung</a> <span class="lang-tag">englisch</span></li>
<li><a href="../legal/privacy-policy.html">Datenschutzhinweise</a> <span class="lang-tag">englisch, Entwurf</span></li>
</ul>
</div></section>
""" % {"wa": WA, "email": cfg.ORG_EMAIL, "tel": re.sub(r"[^\d+]", "", PHONE_DISPLAY),
       "phone": PHONE_DISPLAY}


def build_page():
    src = cfg.read("about.html")
    head = src[: src.index("<main")]
    head = re.sub(r"<!--v7:head-->.*?<!--/v7:head-->", "", head, flags=re.S)
    foot = src[src.index("</main>"):]
    for tag in ("routes", "paid", "wa", "worry"):
        foot = re.sub(r"<!--v7:%s-->.*?<!--/v7:%s-->" % (tag, tag), "", foot, flags=re.S)

    head = re.sub(r'(href|src)="(?!https?:|mailto:|tel:|#|\.\./)([^"]+)"', r'\1="../\2"', head)
    foot = re.sub(r'(href|src)="(?!https?:|mailto:|tel:|#|\.\./)([^"]+)"', r'\1="../\2"', foot)
    foot = foot.replace('window.SITE_PREFIX=""', 'window.SITE_PREFIX="../"')

    head = head.replace('<html lang="en"', '<html lang="de"', 1)
    head = re.sub(r"<title>.*?</title>",
                  "<title>Medizinische Behandlung in der Türkei | Turkelitemedcare</title>",
                  head, flags=re.S)
    head = re.sub(r'<meta content="[^"]*" name="description"/>',
                  '<meta content="Koordinierte medizinische Behandlungsreisen in die Türkei für '
                  'Patientinnen und Patienten aus Deutschland. Wer behandelt, wer haftet, was ein '
                  'Angebot enthält und was Ihre Versicherung wahrscheinlich nicht abdeckt." '
                  'name="description"/>', head)

    page = (head
            + '<main><section class="page-hero"><div class="container page-hero-grid"><div>'
            + '<div class="breadcrumbs"><a href="../index.html">Home</a><b>›</b><span>Deutsch</span></div>'
            + '<span class="eyebrow">Für Patientinnen und Patienten aus Deutschland</span>'
            + '<h1>Behandlung in der Türkei — klar erklärt</h1>'
            + '<p>Wer behandelt, wer verantwortlich ist, was ein Angebot wirklich enthält und was '
              'passiert, wenn etwas schiefgeht. Auf Deutsch, ohne Beschönigung.</p>'
            + '<div class="slide-actions"><a class="btn btn-primary" href="%s" rel="noopener" '
              'target="_blank">Per WhatsApp fragen</a>'
              '<a class="btn btn-ghost" href="../contact.html#callback">Rückruf anfordern</a></div>' % WA
            + "</div></div></section>"
            + BODY + "</main>" + foot)
    cfg.write("de/index.html", page)
    print("  de/index.html created")


def wire_switcher():
    """Point the DE control at a page that now exists, and declare the alternate."""
    n = 0
    for rel in cfg.html_files():
        src = cfg.read(rel)
        p = cfg.prefix_for(rel)
        orig = src
        src = re.sub(
            r'<a class="lang is-pending" href="[^"]*contact\.html"[^>]*>DE</a>',
            '<a class="lang" href="%sde/index.html" hreflang="de" lang="de" '
            'title="Deutschsprachige Einstiegsseite">DE</a>' % p,
            src,
        )
        if rel == "de/index.html":
            src = src.replace(
                '<span aria-current="true" class="lang active">EN</span>',
                '<a class="lang" href="../index.html" hreflang="en" lang="en">EN</a>', 1)
            src = re.sub(r'<a class="lang" href="\.\./de/index\.html"[^>]*>DE</a>',
                         '<span aria-current="true" class="lang active">DE</span>', src)
        if src != orig:
            cfg.write(rel, src)
            n += 1
    print("  language switcher wired on %d pages" % n)


def localise_chrome():
    """The German page was cloned from about.html, so it inherited that page's
    active-nav state and English chrome. Correct both."""
    rel = "de/index.html"
    src = cfg.read(rel)
    src = src.replace('<a aria-current="page" href="../about.html"', '<a href="../about.html"')
    src = src.replace('<a aria-current="true" href="../about.html"', '<a href="../about.html"')
    src = src.replace("International patient support", "Internationale Patientenbetreuung")
    src = src.replace("Mon–Fri · 08:00–18:00 CET", "Mo–Fr · 08:00–18:00 MEZ")
    src = src.replace(">Plan my treatment journey →<", ">Behandlungsanfrage starten →<")
    src = src.replace(">PLAN MY JOURNEY<", ">ANFRAGE STARTEN<")
    src = src.replace("Skip to main content", "Zum Hauptinhalt springen")
    cfg.write(rel, src)
    print("  German page chrome localised")


def wire_hreflang():
    """The German page and the English home are alternates of each other. Every
    other page stays self-referencing until its German translation exists —
    claiming an alternate that does not exist is worse than claiming none."""
    de_url = cfg.canonical_for("de/index.html")
    en_url = cfg.canonical_for("index.html")
    for rel, alt_de in (("index.html", de_url), ("de/index.html", en_url)):
        src = cfg.read(rel)
        src = re.sub(r'<link href="[^"]*" hreflang="(en|de)" rel="alternate"/>', "", src)
        block = ('<link href="%s" hreflang="en" rel="alternate"/>'
                 '<link href="%s" hreflang="de" rel="alternate"/>' % (en_url, de_url))
        src = src.replace("</head>", block + "</head>", 1)
        cfg.write(rel, src)
    print("  hreflang alternates declared between the English and German entry points")


CSS = """
/* --- 9. German entry point and contact routes -------------------------- */

.contact-routes {
  padding: 54px 0 60px;
  background: var(--sky);
  border-top: 1px solid var(--line);
}
.routes-grid,
.de-contact-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
}
.de-contact-grid {
  margin: 20px 0 26px;
}
.route {
  display: block;
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 14px;
  padding: 22px 24px;
  transition: 0.18s;
}
.route:hover {
  border-color: #9fd2e0;
  transform: translateY(-2px);
  box-shadow: 0 14px 32px rgba(20, 70, 90, 0.10);
}
.route b {
  display: block;
  font-size: 15px;
  color: var(--navy);
  margin-bottom: 5px;
}
.route span {
  display: block;
  font-size: 12.5px;
  color: #5b7683;
  line-height: 1.5;
}
.route-wa {
  border-top: 3px solid #25d366;
}
.route-call {
  border-top: 3px solid var(--blue);
}
.route-plan {
  border-top: 3px solid var(--teal);
}

.wa-float {
  position: fixed;
  left: 22px;
  bottom: 22px;
  z-index: 60;
  width: 54px;
  height: 54px;
  border-radius: 50%;
  display: grid;
  place-items: center;
  background: #25d366;
  color: #fff;
  box-shadow: 0 10px 26px rgba(6, 60, 30, 0.30);
  transition: 0.18s;
}
.wa-float:hover {
  transform: scale(1.06);
}

.reply-promise {
  background: #e9f7f4;
  border-left: 4px solid var(--teal);
  border-radius: 0 10px 10px 0;
  padding: 16px 18px;
  margin: 0 0 26px;
}
.reply-promise b {
  display: block;
  font-size: 14px;
  color: var(--navy);
  margin-bottom: 4px;
}
.reply-promise span {
  font-size: 12.5px;
  color: #4d6773;
  line-height: 1.55;
}
.reply-promise a {
  color: var(--blue);
  font-weight: 700;
  text-decoration: underline;
}

.worry-link {
  max-width: 1180px;
  margin: 0 auto;
  padding: 26px 24px 0;
}
.worry-link a {
  font-weight: 750;
  color: var(--blue);
  text-decoration: underline;
  text-underline-offset: 4px;
}

.callback-block {
  max-width: 720px;
}
.callback-form {
  margin-top: 22px;
}
.callback-form label {
  display: block;
  font-size: 12px;
  font-weight: 750;
  margin: 16px 0 6px;
}
.callback-form .check {
  font-weight: 500;
}

.lang-tag {
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  background: var(--sky);
  color: #5b7683;
  border-radius: 20px;
  padding: 2px 8px;
  margin-left: 6px;
}

@media (max-width: 860px) {
  .routes-grid,
  .de-contact-grid {
    grid-template-columns: 1fr;
  }
  .wa-float {
    left: 16px;
    bottom: 84px;
  }
}
"""


def add_css():
    path = os.path.join(cfg.ROOT, "assets", "css", "v7-additions.css")
    with open(path, encoding="utf-8") as fh:
        css = fh.read()
    if "German entry point and contact routes" not in css:
        with open(path, "w", encoding="utf-8") as fh:
            fh.write(css + CSS)
    print("  route and German-page styles added")


if __name__ == "__main__":
    # `--wire-only` re-asserts the switcher and hreflang after patch_head has
    # rewritten every <head>, without rebuilding the German page from about.html
    # (which by then carries a different set of injected blocks).
    if "--wire-only" in sys.argv:
        wire_switcher()
        localise_chrome()
        wire_hreflang()
    else:
        build_page()
        wire_switcher()
        add_css()
        localise_chrome()
        wire_hreflang()
