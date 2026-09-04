# Turkelite Medcare — Management Final

This package is the management handoff build for Turkelite Medcare.

## Product position
Turkelite Medcare coordinates the practical medical-travel journey around a provider-confirmed clinical plan: enquiry preparation, clinic communication, appointment coordination, travel timing, transfers, accommodation coordination, return travel and follow-up handover. Diagnosis, suitability, informed consent and treatment remain with the treating licensed provider.

## Build identity
- Build: `FINAL-MGMT`
- Local port: `8135`
- Local database: `turenewtheme_final_mgmt`
- Build endpoint: `/__build`

## Public content contract
- 10 treatment specialties
- exactly 10 procedures per specialty (100 total)
- 1 published clinic: Clinic Expert
- 0 synthetic doctors
- 0 synthetic patient stories
- 8 guides
- 9 patient services
- English / German / Arabic use the same canonical records, order and image assets
- all 100 procedures, all guides and all patient services carry German and Arabic record-level content
- Arabic uses controlled RTL section layout rather than indiscriminate page mirroring

## Procedure-page doctrine
Each treatment page follows the patient's decision sequence: summary -> practical snapshot -> why considered -> suitability/planning -> how it works -> treatment/recovery timeline -> recovery/results -> risks/considerations -> Turkelite journey -> responsibility split -> questions -> real clinic relevance -> deeper detail -> alternatives -> one primary CTA.

## Quality and safety
- 100 local procedure images; no missing or duplicate files
- Periodontal Treatment and key bariatric/cosmetic visuals were corrected/upgraded
- local SVG language flags replace unreliable emoji flags
- English locale routing returns to root paths correctly
- public registration is disabled
- inquiry POST routes are rate-limited and include a honeypot field
- admin media uploads accept JPG/PNG/WebP only
- admin catalogue supports German and Arabic translations
- FULL package includes Composer vendor dependencies and prebuilt frontend assets

## Windows / MAMP
First install:
```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
.\INSTALL-WINDOWS.ps1
```
Then:
```powershell
.\PRESENT-FINAL.ps1
```
Normal later use:
```powershell
.\START-WINDOWS.ps1
```

## Remaining production gate
The legal-document drafts require qualified legal review/sign-off before public production launch. This is intentionally the only release warning in the automated management audit.
