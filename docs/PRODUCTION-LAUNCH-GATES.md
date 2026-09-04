# FINAL — Production Launch Gates

The FINAL package is a private-presentation candidate, not yet an unrestricted public-production certification.

## Must be complete before public launch

1. Replace sample clinics/doctors with contracted provider records.
2. Verify provider legal identity, facility authorisations, named clinicians, treatment scope and follow-up pathways from appropriate primary evidence.
3. Connect a real review source before displaying review counts, ratings or rating-based ranking.
4. Replace all presentation clinic images with approved real partner media or clearly licensed imagery.
5. Complete and sign off all legal documents for Germany/EU/Turkey applicability.
6. Confirm privacy/cookie implementation against actual analytics/marketing tools in production.
7. Review treatment content medically/editorially and add reviewer/last-reviewed/source metadata.
8. Run desktop/mobile browser regression tests on production infrastructure.
9. Run Lighthouse/performance/accessibility tests after the real production asset/CDN configuration is known.
10. Configure real mail delivery, secure record-upload flow if later introduced, backups, monitoring and production secrets.

## Hard automated gate

`START-WINDOWS.ps1` and `PRESENT-FINAL.ps1` require `site:factory:verify-db` to pass. An incomplete catalogue must not be served as the presentation build.
