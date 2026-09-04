# Turkelite Medcare V104 — SEO + Revenue Architecture

## Objective

Build organic traffic around a **small number of high-intent, decision-useful treatment pages**, move visitors into a low-friction treatment enquiry, and avoid manufacturing 100 thin pages simply because 100 treatment records exist.

Turkelite Medcare is positioned as an **international medical-travel coordination layer**, not as the treating clinic. The website therefore has to sell clarity, coordination and access to an appropriate provider review — not make treatment promises.

## Phase 1: 20 commercial priority treatment guides

Only these 20 procedure pages are `index,follow` in V104:

1. FUE Hair Transplant
2. DHI Hair Transplant
3. Dental Implants
4. All-on-4
5. Dental Veneers
6. Dental Crowns
7. Rhinoplasty
8. Breast Augmentation
9. Breast Lift
10. Liposuction
11. Tummy Tuck / Abdominoplasty
12. Blepharoplasty
13. Sleeve Gastrectomy
14. Roux-en-Y Gastric Bypass
15. Gastric Balloon
16. LASIK
17. Cataract Surgery
18. In Vitro Fertilisation (IVF)
19. Total Knee Replacement
20. Total Hip Replacement

The remaining 80 treatment records stay useful for browsing in English, but remain `noindex,follow` until they receive the same level of procedure-specific editorial depth and review.

### Why this structure

The objective is **depth before breadth**. A 20-page commercial layer is easier to:

- proofread properly;
- medically review;
- keep current;
- translate well;
- link internally;
- measure by enquiry conversion;
- improve based on Search Console data;
- protect from accidental scaled/thin content production.

This is not a claim that these 20 are mathematically the highest-volume keywords in every market. They are a practical commercial priority set across the strongest treatment categories; keyword-volume refinement should be done later with Search Console and a dedicated keyword dataset.

## Page architecture for every priority treatment

Each priority page is designed around the decision rather than around generic SEO filler:

1. What the procedure is.
2. A procedure-specific decision point.
3. Questions worth asking the treating provider.
4. Travel and follow-up considerations specific to the procedure.
5. What a good consultation should clarify.
6. Planning treatment in Turkey.
7. Who is medically responsible vs what Turkelite coordinates.
8. A low-friction treatment-enquiry CTA.
9. Educational/medical disclaimer.

The current editorial QA floor is approximately 380 words for EN/DE and 300 for AR. The actual V104 priority corpus is deeper than those floors. Word count is a QA check, **not a ranking target**.

## Search intent map

### 1. Decision / education intent
Examples: “FUE hair transplant Turkey”, “dental implants Turkey”, “gastric sleeve Turkey”.

Destination: the 20 priority treatment guides.

Conversion: **Build my treatment plan** / request provider review.

### 2. Category discovery intent
Examples: hair restoration, dental, bariatric surgery, eye care.

Destination: specialty pages.

Conversion: move into one of the priority guides, then enquiry.

### 3. Provider-comparison intent
Examples: clinic/doctor searches.

The FINAL clinic layer is restricted to Clinic Expert. Add additional clinics only after commercial permission, credentials, treatment scope and approved media are verified.

### 4. Practical journey intent
Examples: how treatment abroad works, travel timing, follow-up, international patient support.

Destination: How It Works + Patient Services.

Conversion: treatment enquiry.

## Conversion funnel

```text
Search / referral / direct
        ↓
Priority treatment guide
        ↓
Understand decision + provider questions
        ↓
Build treatment enquiry
        ↓
Structured lead in admin / CRM handoff
        ↓
Provider review
        ↓
Qualified treatment route
        ↓
Booking / commission event
```

### Conversion principles built into V104

- One primary CTA language across the site.
- No obligation language to reduce form anxiety.
- Clinical decision remains with the provider.
- Travel follows the confirmed medical plan, not vice versa.
- Treatment enquiry asks for the minimum useful initial information; no medical-document upload in the first form.
- CTAs repeat after the decision content rather than only in the header.
- Provider/sample pages do not make unverifiable public claims.

## Multilingual SEO

V104 uses one application and one content model with localized routes:

- English: `/...`
- German: `/de/...`
- Arabic: `/ar/...`

Indexable EN/DE/AR pages receive self-referencing canonicals plus alternate `hreflang` URLs and `x-default`. Arabic uses RTL rendering.

Avoid four independent codebases. They drift, duplicate bugs and make content governance almost impossible.

## Medical/YMYL publication standard

Before public search launch, every priority medical page should receive a real clinical editorial review workflow:

- named qualified reviewer;
- reviewer credentials and scope;
- reviewed/updated date;
- authoritative source references where useful;
- change log when treatment guidance materially changes;
- no outcome guarantees;
- no unsupported success rates;
- no fixed medical pricing presented as universally applicable;
- clear emergency and provider-responsibility boundaries.

V104 copy is deliberately educational and cautious, but it does **not** pretend that AI/editorial drafting is a substitute for clinical sign-off.

## Provider publishing standard

Do not index a clinic or doctor until the company has verified at minimum:

- legal/provider identity;
- professional registration / applicable facility authorization;
- specific services being represented;
- named clinical responsibility;
- permission to publish supplied profile/media;
- how complications and follow-up are handled;
- commercial relationship disclosure where relevant;
- a process for keeping the record current.

Do not add Physician, MedicalClinic, Review or AggregateRating structured data merely because a page visually looks like a provider profile. Structured data should reflect verified visible facts.

## Internal linking

Priority structure:

```text
Home
  → Specialty
      → Priority procedure
          → Related priority procedures
          → How it works / Patient services
          → Treatment enquiry
```

The wider library can help users discover terminology, but it should not dilute the internal-link priority of the 20 commercial pages.

## Content expansion rule

A long-tail page graduates from `noindex` only when it has:

1. unique procedure-specific content;
2. localized metadata and body copy for the target locale;
3. internal links from a relevant specialty/priority page;
4. clinical editorial review;
5. a real reason for a search user to land on it instead of another Turkelite page.

Never make separate pages for tiny keyword variations whose primary content is essentially the same.

## KPIs that matter

Do not judge success by “number of indexed pages”. Track:

- organic impressions/clicks to the 20 priority pages;
- non-brand query growth by treatment;
- priority-page → enquiry-start rate;
- enquiry-start → enquiry-submit rate;
- qualified enquiry rate;
- provider-review rate;
- booked-treatment rate;
- commission/revenue per treatment category;
- content-assisted conversions;
- language/market conversion differences;
- unanswered-query clusters from Search Console that justify the next content page.

The target is **commercially useful search visibility**, not a large page count.
