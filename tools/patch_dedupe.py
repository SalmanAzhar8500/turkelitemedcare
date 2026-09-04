"""Move repeated coordination copy off the 100 procedure pages.

The decision matrix, the five-step journey, the cost checklist and the generic
before/after paragraphs were identical on all 100 procedure pages. Identical
content repeated 100 times is a duplicate-content signal, and it also pushes the
reader past ~700 words of boilerplate before reaching anything about their actual
procedure.

Each block is replaced with a short summary and a link to the canonical page that
owns it. The full versions are consolidated onto how-it-works.html.

Run from the site root:  python3 tools/patch_dedupe.py
"""
import os
import re
import sys
import json

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import sitecfg as cfg

with open(os.path.join(cfg.ROOT, "tools", "content-status.json"), encoding="utf-8") as fh:
    STATUS = json.load(fh)


def summary_blocks(p):
    """Compact replacements, linking to the page that owns the full version."""
    decision = (
        '<!--v7:sum-decision-->'
        '<h2>Before you book anything</h2>'
        '<p>Four questions should have a clear owner and a clear answer before a treatment journey is '
        'ready to book: whether the procedure suits you (the clinic decides), what the quotation actually '
        'includes, when travel can safely be booked, and what happens after you fly home. '
        '<a href="%show-it-works.html#decisions">See how each of those is handled →</a></p>'
        '<!--/v7:sum-decision-->' % p
    )
    journey = (
        '<!--v7:sum-journey-->'
        '<h2>How the journey works</h2>'
        '<p>Five steps, in this order: tell us what you are considering; share the records the clinic asks '
        'for; review the clinic plan and its inclusions; coordinate treatment dates and travel around the '
        'confirmed plan; complete the return-home follow-up handover. '
        '<a href="%show-it-works.html#journey">The full breakdown is here →</a></p>'
        '<!--/v7:sum-journey-->' % p
    )
    cost = (
        '<!--v7:sum-cost-->'
        '<h2 id="cost">What it costs</h2>'
        '<p>We do not publish a headline price, because a number without its inclusions is the most '
        'misleading figure in medical travel — two quotes that look identical routinely cover different '
        'implants, different anaesthesia, different lengths of stay and completely different aftercare. '
        'Every clinic proposal we pass on is broken down against the same checklist so you can see what '
        'each price does and does not buy, including the exclusions worth asking about directly: '
        'complications, revision, take-home medicines and aftercare once you are back. '
        '<a href="%show-it-works.html#cost">See the full inclusions and exclusions checklist →</a></p>'
        '<p class="clinical-note">A firm price can only follow a clinical assessment. Any figure quoted '
        'before a clinician has reviewed your case is an estimate.</p>'
        '<!--/v7:sum-cost-->' % p
    )
    return decision, journey, cost


def patch_pages():
    n = 0
    for rel in cfg.html_files():
        if cfg.page_kind(rel) != "procedure":
            continue
        src = cfg.read(rel)
        p = cfg.prefix_for(rel)
        decision, journey, cost = summary_blocks(p)
        written = STATUS.get(rel, {}).get("contentState") == "written"

        # Remove any summary written by an earlier run. Without this the
        # summaries accumulate on every pipeline run, and the cost summary
        # carries an id, so the page ends up with duplicate element ids.
        for tag in ("sum-decision", "sum-journey", "sum-cost"):
            src = re.sub(r"<!--v7:%s-->.*?<!--/v7:%s-->" % (tag, tag), "", src, flags=re.S)

        # decision matrix
        src = re.sub(
            r"<h2>The decision matrix before you travel</h2>.*?(?=<h2>How the journey works</h2>)",
            decision + "\n\n    ",
            src, flags=re.S,
        )
        # five-step journey (stop before the mid-article CTA)
        src = re.sub(
            r"<h2>How the journey works</h2>.*?(?=<div class=\"procedure-midcta|<h2>Partner clinics)",
            journey + "\n\n    ",
            src, flags=re.S,
        )
        # cost module
        src = re.sub(r"<!--v7:cost-->.*?<!--/v7:cost-->", cost, src, flags=re.S)

        # generic before/after paragraphs — on written pages the clinical section
        # already covers this properly, so they are pure repetition
        if written:
            src = re.sub(
                r"<h2>What happens before treatment\?</h2>\s*<p>.*?</p>\s*"
                r"<h2>Recovery and follow-up</h2>\s*<p>.*?</p>",
                "", src, flags=re.S,
            )

        cfg.write(rel, src)
        n += 1
    print("  repeated blocks summarised on %d procedure pages" % n)


CANONICAL = """<!--v7:canonical-coordination--><section class="template-section" id="decisions"><div class="container">
<span class="eyebrow">Before you book</span>
<h2>The four decisions that should have an owner</h2>
<p>Before a treatment journey is ready to book, each of these should have a clear answer and a clear owner. This is the checklist every procedure page links back to.</p>
<table class="legal-table">
<tr><th>Decision</th><th>Why it matters</th><th>How we coordinate it</th></tr>
<tr><td>Is this procedure suitable for me?</td><td>The procedure must fit your clinical situation, not simply your preference.</td><td>We organise the records and questions the partner clinic asks for, so the clinical review can actually happen.</td></tr>
<tr><td>What exactly is included?</td><td>Quotes can look similar while covering different treatment, facility and aftercare items.</td><td>We structure the clinic proposal so inclusions, exclusions and practical extras are comparable.</td></tr>
<tr><td>When should I book travel?</td><td>Clinical dates and recovery requirements should determine the journey, not the other way round.</td><td>We sequence flights, transfers and accommodation around the clinic-confirmed plan.</td></tr>
<tr><td>What happens after I return?</td><td>Follow-up should not become unclear once you leave Turkey.</td><td>We make the handover, contact route and provider instructions visible before departure.</td></tr>
</table>
</div></section>

<section class="template-section alt" id="cost"><div class="container">
<span class="eyebrow">Quotations</span>
<h2>What "included" actually means</h2>
<p>Every clinic proposal we pass on is broken down against the same checklist, so two quotes can be compared on the same terms.</p>
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
<p class="clinical-note">A firm price can only follow a clinical assessment. Any figure quoted before a clinician has reviewed your case is an estimate, and it can change. We will tell you when a quotation is firm and when it is not.</p>
</div></section><!--/v7:canonical-coordination-->"""


def patch_how_it_works():
    rel = "how-it-works.html"
    src = cfg.read(rel)
    src = re.sub(r"<!--v7:canonical-coordination-->.*?<!--/v7:canonical-coordination-->", "", src, flags=re.S)
    # give the existing journey section a stable anchor for the procedure links
    if 'id="journey"' not in src:
        src = re.sub(r'(<section class="template-section[^"]*")(>)', r'\1 id="journey"\2', src, count=1)
    src = src.replace("</main>", CANONICAL + "</main>", 1)
    cfg.write(rel, src)
    print("  canonical coordination content consolidated on how-it-works.html")


if __name__ == "__main__":
    patch_pages()
    patch_how_it_works()
