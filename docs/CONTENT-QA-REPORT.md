# Turkelite Medcare V104 — Content / SEO / Presentation QA Report

## Build status

V104 is a multilingual presentation build with three visual concepts running on the same Laravel/database/content factory.

### Canonical content inventory

- 26 site-page records
- 10 specialties
- 100 treatment/procedure records
- 20 phase-one SEO priority procedures
- 6 sample clinic profiles
- 9 sample doctor profiles
- 8 English general guides
- 9 patient-service records
- 6 illustrative/sample patient-story records
- 8 legal-document drafts
- EN / DE / AR locale architecture

## Priority treatment QA

Exactly 20 treatment pages are `index,follow`.

Current body-copy range after V104 editorial work:

- English: ~458–504 words per priority page
- German: ~397–434 words per priority page
- Arabic: ~384–418 tokenized words per priority page

Each priority record has localized:

- treatment name;
- summary;
- body copy;
- SEO title;
- SEO description.

Each priority page contains a procedure-specific decision module rather than only a swapped procedure name.

## Long-tail protection

The other 80 treatment records are `noindex,follow` until they receive equivalent editorial depth and review. This lets the library remain usable without treating page count as SEO strategy.

## Proofreading / cleanup completed

- Removed known mojibake/import-marker artifacts from canonical public content/views.
- Corrected malformed legacy titles such as the old placeholder How It Works title.
- Normalized the public brand name to **Turkelite Medcare**.
- Corrected truncated German SEO titles so the brand is not cut mid-word.
- Corrected awkward priority headings such as “What is Dental Implants?” and equivalent DE/AR grammar on the main procedure introductions.
- Removed public-facing “this page is intentionally noindex” style implementation language.
- Reframed medical copy away from guaranteed outcomes and toward provider questions, suitability, alternatives, recovery and follow-up.
- Kept diagnosis, informed consent and treatment responsibility with the independent treating provider.
- Kept initial enquiry forms deliberately lighter than a full medical-record intake.

## Multilingual QA

- English uses unprefixed URLs.
- German uses `/de`.
- Arabic uses `/ar`.
- Arabic document direction is RTL.
- Indexable localized routes emit alternate-language links plus `x-default`.
- Priority treatment content is available in all three presentation languages.
- Core commercial pages use locale-specific copy rather than relying on browser translation.

## Presentation integrity

The build intentionally does **not** invent:

- verified clinic accreditations;
- doctor registrations;
- patient reviews;
- treatment success percentages;
- guaranteed outcomes;
- universal fixed prices;
- legal approval that has not occurred.

Current clinic/doctor/patient-story records remain presentation samples and are `noindex`.

## Automated gates

Run:

```bash
php artisan site:factory:validate --content=content/en --strict
php artisan site:factory:audit
```

The presentation audit checks:

- schema/locales;
- exactly 20 priority pages;
- multilingual priority copy/metadata;
- minimum editorial depth;
- unique SEO titles;
- long-tail noindex status;
- sample provider/story noindex status;
- known encoding/import artifacts;
- legal/photo/provider launch blockers.

Production uses the stricter command:

```bash
php artisan site:factory:audit --production
```

A public launch should not proceed while that command reports failures.

## Deliberate production blockers remaining

1. **Legal documents** — still contain company-specific/legal-review placeholders.
2. **Showcase photography** — three home concepts currently use remote presentation photography and need approved/licensed local assets.
3. **Provider records** — clinic/doctor/story data is sample/demo content until real partners, credentials, media permissions and commercial terms are verified.
4. **Clinical editorial review** — the 20 medical priority pages should receive qualified clinical sign-off before public search launch.

These are not hidden defects; they are explicit launch gates so presentation data cannot accidentally become production truth.
