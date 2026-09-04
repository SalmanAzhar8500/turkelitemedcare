"""Replace the templated 'What is X?' block on procedure pages with real clinical content.

Pages that have an entry in tools/procedure-content.json get a full patient-education
section, a procedure-specific at-a-glance matrix and a review byline, and stay indexable.
Pages without an entry keep the templated copy, gain a visible "guide in preparation"
notice, and are marked noindex so 90%-duplicate pages never reach the index.

A content brief for every unwritten page is written to tools/CONTENT-BRIEFS.md.

Run from the site root:  python3 tools/patch_procedure_content.py
"""
import os
import re
import json
import sys
import html

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

START = "<!--v7:clinical-->"
END = "<!--/v7:clinical-->"

with open(os.path.join(cfg.ROOT, "tools", "procedure-content.json"), encoding="utf-8") as fh:
    CONTENT = {k: v for k, v in json.load(fh).items() if not k.startswith("_")}


def e(text):
    return html.escape(str(text), quote=False)


def li(items):
    return "".join("<li>%s</li>" % e(x) for x in items)


def clinical_block(name, data):
    """The unique, procedure-specific section."""
    reviewer = data.get("reviewedBy", "").strip()
    reviewed_on = data.get("reviewedOn", "").strip()
    if reviewer and reviewed_on:
        byline = (
            '<p class="medical-byline"><span class="medical-byline-status is-reviewed">Clinically reviewed</span> '
            "Reviewed by %s · Last reviewed %s · Next review due within 12 months</p>"
            % (e(reviewer), e(reviewed_on))
        )
    else:
        byline = (
            '<p class="medical-byline"><span class="medical-byline-status">Awaiting clinical sign-off</span> '
            "This guide is written for patient education and has not yet been signed off by a named clinician. "
            "It is general information, not advice about your case.</p>"
        )

    paras = "".join("<p>%s</p>" % e(p) for p in data.get("what", []))

    glance = data.get("glance", {})
    glance_html = ""
    if glance:
        glance_html = '<div class="procedure-at-glance clinical-glance">' + "".join(
            '<div><span class="procedure-stat-label">%s</span><b>%s</b></div>' % (e(k), e(v))
            for k, v in glance.items()
        ) + "</div>"

    why = data.get("why", "")
    why_html = "<h3>Why patients consider it</h3><p>%s</p>" % e(why) if why else ""

    suit = ""
    if data.get("suitable") or data.get("notsuitable"):
        suit = (
            '<h3>Who it usually suits — and who it usually does not</h3>'
            '<div class="clinical-split">'
            '<div class="clinical-col is-yes"><h4>Commonly suitable</h4><ul>%s</ul></div>'
            '<div class="clinical-col is-no"><h4>Commonly not suitable, or needs further assessment</h4><ul>%s</ul></div>'
            "</div>"
            '<p class="clinical-note">These are general patterns, not a screening tool. Only the treating '
            "clinician can decide whether this procedure is appropriate for you.</p>"
            % (li(data.get("suitable", [])), li(data.get("notsuitable", [])))
        )

    rec = ""
    if data.get("recovery"):
        rec = "<h3>Typical recovery timeline</h3><ul class=\"clinical-list\">%s</ul>" % li(data["recovery"])
        rec += ('<p class="clinical-note">Timelines vary between patients and between clinics. '
                "Your discharge instructions override anything on this page — including when it is safe to fly.</p>")

    risks = ""
    if data.get("risks"):
        risks = ('<h3>Risks and possible complications</h3>'
                 '<p>Every procedure carries risk. These are the ones most worth discussing at consultation:</p>'
                 '<ul class="clinical-list is-risk">%s</ul>' % li(data["risks"]))

    alts = ""
    if data.get("alternatives"):
        alts = ("<h3>Alternatives worth asking about</h3><ul class=\"clinical-list\">%s</ul>" % li(data["alternatives"]))

    return (
        START
        + '<h2 id="guide">What is %s?</h2>' % e(name)
        + byline
        + paras
        + glance_html
        + why_html
        + suit
        + rec
        + risks
        + alts
        + END
    )


def pending_block(name):
    return (
        START
        + '<h2 id="guide">What is %s?</h2>' % e(name)
        + '<div class="guide-pending"><b>Detailed clinical guide in preparation.</b>'
        "<p>We publish procedure guides only once a qualified clinician has written and signed off the "
        "content. Until then this page explains how the coordination works and how to reach a partner "
        "clinic — it deliberately does not describe the technique, recovery or risks of the procedure. "
        "Ask the treating clinic for that detail, or request a consultation and we will put the question to them.</p></div>"
        + '<p>This pathway is reviewed by a treating clinician using the records, images and examinations relevant '
        "to your case. The technique, materials and timing are confirmed by the clinic after assessment.</p>"
        + END
    )


def main():
    files = [f for f in cfg.html_files() if cfg.page_kind(f) == "procedure"]
    status = {}
    written = 0
    pending = 0
    briefs = []

    for rel in files:
        src = cfg.read(rel)
        spec = cfg.specialty_of(rel)
        slug = os.path.basename(rel)[:-5]
        key = "%s/%s" % (spec, slug)
        name = cfg.get_h1(src)

        # strip any previous injection
        src = re.sub(re.escape(START) + ".*?" + re.escape(END), "", src, flags=re.S)

        # locate the templated block: <h2>What is ...</h2> plus the two generic paragraphs
        pattern = re.compile(
            r"<h2[^>]*>What is .*?</h2>\s*(?:<p>.*?</p>\s*){1,3}", re.S
        )
        m = pattern.search(src)
        if not m:
            print("  ! could not locate templated block:", rel)
            continue

        data = CONTENT.get(key)
        if data:
            src = src[: m.start()] + clinical_block(name, data) + src[m.end():]
            status[rel] = {"index": True, "contentState": "written"}
            if data.get("reviewedOn"):
                status[rel]["lastReviewed"] = data["reviewedOn"]
            written += 1
        else:
            src = src[: m.start()] + pending_block(name) + src[m.end():]
            status[rel] = {"index": False, "contentState": "templated"}
            pending += 1
            briefs.append((key, name, rel))

        cfg.write(rel, src)

    with open(os.path.join(cfg.ROOT, "tools", "content-status.json"), "w", encoding="utf-8") as fh:
        json.dump(status, fh, indent=2, sort_keys=True)

    # ------------------------------------------------------------- briefs
    lines = [
        "# Procedure content briefs",
        "",
        "%d of %d procedure pages still carry templated copy. They are set to `noindex,follow` "
        "and show a visible 'guide in preparation' notice until written." % (pending, len(files)),
        "",
        "## How to write one",
        "",
        "Add an entry to `tools/procedure-content.json` keyed `specialty/slug`, then re-run:",
        "",
        "```",
        "python3 tools/patch_procedure_content.py && python3 tools/patch_head.py && python3 tools/build_sitemap.py",
        "```",
        "",
        "Required fields per entry — aim for 450–650 unique words:",
        "",
        "| Field | What it must contain |",
        "| --- | --- |",
        "| `what` | 2 paragraphs: what the procedure physically involves, and how a session runs (duration, anaesthetic, stages) |",
        "| `why` | 1 paragraph: the clinical reasons patients are offered it |",
        "| `suitable` | 4–5 bullets: who it commonly suits |",
        "| `notsuitable` | 4–6 bullets: contraindications and cautions |",
        "| `recovery` | 5–6 bullets: a real timeline, including when the clinic typically clears air travel |",
        "| `risks` | 6–8 bullets: named complications, not 'as with any surgery' |",
        "| `alternatives` | 3–5 bullets, including no treatment where that is reasonable |",
        "| `glance` | 4 key/value pairs for the at-a-glance matrix |",
        "| `reviewedBy` / `reviewedOn` | Named clinician and ISO date. Until both are set the page shows an 'awaiting sign-off' byline. |",
        "",
        "Every entry must be signed off by a clinician registered in a relevant jurisdiction before the "
        "page is switched to indexable. Do not copy text from ADA, ASPS, AAO, AAOS, ASMBS, ENT Health or "
        "similar bodies — use them to check accuracy, then write original copy.",
        "",
        "## Still to write",
        "",
    ]
    by_spec = {}
    for key, name, rel in briefs:
        by_spec.setdefault(key.split("/")[0], []).append((key, name, rel))
    for spec in sorted(by_spec):
        lines.append("### %s" % cfg.SPECIALTY_LABELS.get(spec, spec))
        lines.append("")
        for key, name, rel in sorted(by_spec[spec]):
            lines.append("- [ ] `%s` — %s → `%s`" % (key, name, rel))
        lines.append("")

    with open(os.path.join(cfg.ROOT, "tools", "CONTENT-BRIEFS.md"), "w", encoding="utf-8") as fh:
        fh.write("\n".join(lines))

    print("patch_procedure_content: %d written, %d pending (noindex)" % (written, pending))


if __name__ == "__main__":
    main()
