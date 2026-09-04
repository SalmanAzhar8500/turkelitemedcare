# Procedure content briefs

0 of 100 procedure pages still carry templated copy. They are set to `noindex,follow` and show a visible 'guide in preparation' notice until written.

## How to write one

Add an entry to `tools/procedure-content.json` keyed `specialty/slug`, then re-run:

```
python3 tools/patch_procedure_content.py && python3 tools/patch_head.py && python3 tools/build_sitemap.py
```

Required fields per entry — aim for 450–650 unique words:

| Field | What it must contain |
| --- | --- |
| `what` | 2 paragraphs: what the procedure physically involves, and how a session runs (duration, anaesthetic, stages) |
| `why` | 1 paragraph: the clinical reasons patients are offered it |
| `suitable` | 4–5 bullets: who it commonly suits |
| `notsuitable` | 4–6 bullets: contraindications and cautions |
| `recovery` | 5–6 bullets: a real timeline, including when the clinic typically clears air travel |
| `risks` | 6–8 bullets: named complications, not 'as with any surgery' |
| `alternatives` | 3–5 bullets, including no treatment where that is reasonable |
| `glance` | 4 key/value pairs for the at-a-glance matrix |
| `reviewedBy` / `reviewedOn` | Named clinician and ISO date. Until both are set the page shows an 'awaiting sign-off' byline. |

Every entry must be signed off by a clinician registered in a relevant jurisdiction before the page is switched to indexable. Do not copy text from ADA, ASPS, AAO, AAOS, ASMBS, ENT Health or similar bodies — use them to check accuracy, then write original copy.

## Still to write
