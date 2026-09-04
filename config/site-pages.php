<?php

return [
    'home' => [
        'title' => 'Medical Treatment in Turkey | Turkelite Medcare',
        'description' => 'Explore treatment pathways and coordinated medical travel with independent providers in Turkey.',
        'content' => [
            'hero' => [
                'kicker' => 'Medical treatment in Turkey',
                'headline' => 'Care you can understand and compare',
                'lead' => 'Choose a treatment pathway and let one coordination team keep the clinic, schedule, travel, arrival and follow-up handovers connected around you.',
                'actions' => [
                    ['label' => 'Get treatment plan', 'href' => '/treatment-plan.html', 'variant' => 'primary'],
                    ['label' => 'Explore treatments', 'href' => '/treatments', 'variant' => 'ghost'],
                ],
            ],
            'welcome' => [
                'heading' => 'Welcome to Turkelite Medcare',
                'features' => [
                    ['icon' => 'clinic', 'number' => '01', 'title' => 'Provider profiles with clear roles', 'text' => 'Treatment-linked clinic profiles make it clear where care is delivered and who is responsible for the clinical plan.'],
                    ['icon' => 'specialist', 'number' => '02', 'title' => 'Specialist-led decisions', 'text' => 'Treatment suitability remains with the independent clinician who evaluates your case.'],
                    ['icon' => 'journey', 'number' => '03', 'title' => 'Whole-journey coordination', 'text' => 'One point of contact keeps clinic communication, scheduling, travel, arrival, and follow-up connected.'],
                    ['icon' => 'support', 'number' => '04', 'title' => 'International support', 'text' => 'Journey planning for international patients travelling to partner clinics in Turkey.'],
                ],
                'form_heading' => 'Get treatment plan',
                'specialty_label' => 'Specialty',
                'specialty_placeholder' => 'Choose specialty',
                'country_label' => 'Your country',
                'country_placeholder' => 'Germany',
                'contact_label' => 'Preferred contact',
                'contact_placeholder' => 'WhatsApp or email',
                'button_label' => 'Start my request',
                'note' => 'Medical files can be requested securely later if a clinic needs them for review.',
            ],
            'specialties' => [
                'eyebrow' => 'Treatment library',
                'heading' => 'Explore our specialties',
                'lead' => 'Choose a medical specialty, then explore the procedure pages already published in the legacy library.',
            ],
            'services' => [
                'eyebrow' => 'Patient services',
                'heading' => 'Your treatment trip should feel looked after',
                'lead' => 'Before you travel, the clinical plan, provider choice, appointments, logistics and return-home follow-up all need to line up.',
                'items' => [
                    ['title' => 'Coordinated appointments', 'text' => 'We keep clinic dates and handovers aligned.'],
                    ['title' => 'Travel planning', 'text' => 'Flights, transfers and accommodation can be sequenced around the treatment plan.'],
                    ['title' => 'Follow-up organisation', 'text' => 'We keep the post-treatment handover visible before departure.'],
                ],
                'cta' => ['label' => 'Patient services', 'href' => '/patient-services'],
                'secondary' => ['label' => 'See how it works', 'href' => '/how-it-works'],
            ],
            'testimonial' => [
                'eyebrow' => 'Patient journey examples',
                'heading' => 'What whole-journey assurance should feel like',
                'lead' => 'Patient journeys showing how treatment planning, travel and follow-up stay connected.',
                'quote' => 'I wanted to understand implant options before choosing a clinic. Having the treatment guide, provider questions and practical journey in one place made the questions I needed to ask much clearer.',
                'cite' => 'Martin K. - Germany - Illustrative dental journey',
            ],
            'specialist_teams' => [
                'eyebrow' => 'Specialists',
                'heading' => 'Meet specialist teams',
                'lead' => 'Specialist profiles connect treatment pathways to the clinics and teams that provide the care.',
                'empty_heading' => 'Specialist profiles are being prepared',
                'empty_text' => 'Published doctor profiles will appear here when they are added by the admin team.',
            ],
            'guides_videos' => [
                'eyebrow' => 'Guides & articles',
                'heading' => 'Prepare before you travel',
                'lead' => 'Educational content helps patients ask better questions and understand the practical side of treatment abroad.',
                'guide_label' => 'Guide',
                'guide_action' => 'Read guide',
                'video_label' => 'Article',
                'video_heading' => 'Treatment decision checklist',
                'video_meta' => 'Decision support',
                'video_text' => 'Practical decision articles help patients prepare questions about providers, scope, travel timing and follow-up.',
                'video_action' => 'Read articles',
            ],
        ],
    ],
    'about' => [
        'title' => 'About Us | Turkelite Medcare',
        'description' => 'Who Turkelite Medcare is, what we coordinate, what independent partner clinics remain responsible for, and how we are paid.',
        'content' => [
            'hero' => [
                'kicker' => 'About the platform',
                'headline' => 'About Us',
                'lead' => 'A medical-travel coordination platform designed to connect international patients with selected independent providers in Turkey.',
            ],
            'hero_card' => [
                'label' => 'Care pathway',
                'heading' => 'Plan -> Coordinate -> Return supported',
                'text' => 'One team coordinating the journey around your care',
            ],
            'intro' => [
                'what_we_do_heading' => 'What we do',
                'what_we_do' => 'Turkelite Medcare is a medical-travel coordination brand designed for international patients seeking treatment pathways in Turkey. The platform is designed to explain treatment pathways, present provider information, collect patient enquiries and coordinate practical next steps.',
                'what_we_do_not_heading' => 'What we do not do',
                'what_we_do_not' => 'The platform is not a hospital and does not diagnose, prescribe or perform treatment. Final clinical recommendations, informed consent, treatment and aftercare are responsibilities of the independent healthcare provider.',
            ],
            'clinic_standards' => [
                'heading' => 'How clinic onboarding should work',
                'items' => [
                    ['title' => 'Identity & licensing', 'text' => 'Verify the legal provider and appropriate healthcare authorisations.'],
                    ['title' => 'Specialist credentials', 'text' => 'Confirm named clinicians and the procedures they perform.'],
                    ['title' => 'Patient pathway', 'text' => 'Document assessment, consent, safety and follow-up processes.'],
                    ['title' => 'Content approval', 'text' => 'Have provider-specific medical claims reviewed before publication.'],
                ],
            ],
            'clinic_sidebar' => [
                'kicker' => 'For clinics',
                'heading' => 'Join the network',
                'text' => 'The partner section is ready for an onboarding/application workflow.',
                'link_label' => 'Partner with us',
                'link' => '/for-clinics',
            ],
            'contact_routes' => [
                ['title' => 'Message us on WhatsApp', 'text' => 'Ask one question. No form, no commitment.', 'href' => 'https://wa.me/493023125400?text=Hello%2C%20I%20would%20like%20to%20ask%20about%20treatment%20in%20Turkey.', 'variant' => 'wa'],
                ['title' => 'Request a callback', 'text' => 'German or English, at a time that suits you.', 'href' => '/contact#callback', 'variant' => 'call'],
                ['title' => 'Start a treatment enquiry', 'text' => 'For when you are ready for a clinic to review your case.', 'href' => '/treatment-plan', 'variant' => 'plan'],
            ],
            'transparency' => [
                'kicker' => 'Commercial transparency',
                'heading' => 'How we are paid',
                'paragraphs' => [
                    'You should know who pays us before you rely on anything we tell you. Ask us directly and we will answer in writing who pays our fee, whether it differs between clinics, and whether any clinic can pay to be featured.',
                    'What we will commit to in public: a clinic\'s commercial terms with us never decide whether it is clinically right for you. Suitability is the treating clinician\'s judgement, and we do not overrule it or route around it.',
                ],
                'link' => '/legal/terms.html',
                'link_prefix' => 'Full terms are in the',
                'link_label' => 'platform terms',
            ],
        ],
    ],
    'legal' => [
        'title' => 'Legal & Privacy | Turkelite Medcare',
        'description' => 'Legal, privacy and compliance information for Turkelite Medcare, including medical responsibility, privacy and complaints guidance.',
        'content' => [
            'hero' => [
                'kicker' => 'Trust & compliance',
                'headline' => 'Legal & Privacy',
                'lead' => 'Clear information about how Turkelite Medcare coordinates care, protects information and works with independent healthcare providers.',
            ],
            'hero_card' => [
                'label' => 'Role clarity',
                'heading' => 'Coordinate with confidence',
                'text' => 'Medical decisions remain with independent partner providers.',
            ],
            'documents' => [
                'heading' => 'Legal and privacy documents',
                'intro' => 'Use the documents below to understand the platform, your rights and the responsibilities of Turkelite Medcare and its independent healthcare partners.',
                'items' => [
                    ['title' => 'Impressum / Legal notice', 'url' => '/legal/impressum.html'],
                    ['title' => 'Privacy notice', 'url' => '/legal/privacy-policy.html'],
                    ['title' => 'Cookie policy', 'url' => '/legal/cookie-policy.html'],
                    ['title' => 'Platform terms', 'url' => '/legal/terms.html'],
                    ['title' => 'Medical disclaimer', 'url' => '/legal/medical-disclaimer.html'],
                    ['title' => 'Complaints process', 'url' => '/legal/complaints.html'],
                    ['title' => 'Clinic selection standards', 'url' => '/legal/clinic-selection-standards.html'],
                    ['title' => 'Patient rights and provider responsibilities', 'url' => '/legal/patient-rights.html'],
                ],
            ],
            'notice' => 'These documents are structural drafts and must be reviewed by qualified counsel in the relevant jurisdictions before production publication.',
            'ui' => [
                'important_notice_label' => 'Important notice',
                'draft_label' => 'Draft - pending legal review',
                'legal_flag' => 'The Impressum is a statutory requirement for publishing in Germany. The cookie policy also depends on a working consent banner. No document on this page replaces professional legal advice.',
                'urgent_notice' => 'For urgent medical concerns, contact your treating provider or local emergency services.',
            ],            'role_clarity' => [
                'heading' => 'Role clarity',
                'paragraphs' => [
                    'Turkelite Medcare is a coordination platform. We help organise communication, scheduling and practical travel support around treatment provided by independent healthcare providers.',
                    'Partner clinics and doctors remain responsible for clinical assessment, treatment recommendations, informed consent, medical care, billing for medical services and clinical aftercare.',
                ],
            ],
            'sidebar' => [
                'kicker' => 'Need help?',
                'heading' => 'Questions about your information?',
                'text' => 'Contact the coordination team if you need help understanding a request or want to raise a concern about how your information is handled.',
                'button' => 'Contact the team',
            ],
        ],
    ],
    'contact' => [
        'title' => 'Contact | Turkelite Medcare',
        'description' => 'Contact the Turkelite Medcare international patient team about a treatment enquiry, an existing journey or a partner-clinic question.',
        'content' => [
            'hero' => [
                'kicker' => 'Talk to us',
                'headline' => 'Contact',
                'lead' => 'Use this page for general enquiries. Treatment-related enquiries should use the structured treatment request.',
            ],
            'contact_info' => [
                ['label' => 'Email', 'value' => '{{brand.email}}'],
                ['label' => 'Phone', 'value' => '{{brand.phone}}'],
                ['label' => 'Languages', 'value' => 'German, English, additional languages by arrangement'],
            ],
            'form' => [
                'heading' => 'Contact the coordination team',
                'note' => 'Your enquiry continues through the treatment-coordination workflow.',
            ],
            'callback' => [
                'heading' => 'Request a callback',
                'lead' => 'Tell us when suits and which language you would prefer. We will call you. There is no obligation, and you do not need to share any medical detail to talk to us.',
                'note' => 'Weekday callbacks, usually the same or next working day.',
            ],
        ],
    ],
    'for-clinics' => [
        'title' => 'For Clinics | Turkelite Medcare',
        'description' => 'Partner with Turkelite Medcare as an independent clinic or provider in Turkey.',
        'content' => [
            'hero' => [
                'kicker' => 'Partner clinics',
                'headline' => 'For Clinics',
                'lead' => 'We work with independent clinics that want a clear patient-coordination layer around their treatment pathways.',
            ],
            'features' => [
                'heading' => 'What a partner relationship should cover',
                'items' => [
                    ['title' => 'Identity and licensing', 'text' => 'Verify the legal provider and appropriate healthcare authorisations.'],
                    ['title' => 'Specialist credentials', 'text' => 'Confirm named clinicians and the procedures they perform.'],
                    ['title' => 'Patient pathway', 'text' => 'Document assessment, consent, safety and follow-up processes.'],
                    ['title' => 'Content approval', 'text' => 'Have provider-specific medical claims reviewed before publication.'],
                ],
                'cta' => ['label' => 'Contact us', 'href' => '/contact'],
            ],
        ],
    ],
    'how-it-works' => [
        'title' => 'How It Works | Turkelite Medcare',
        'description' => 'How a coordinated treatment journey works step by step, the decisions to settle before booking, and what a clinic quotation should include.',
        'content' => [
            'hero' => [
                'kicker' => 'The Turkelite Medcare model',
                'headline' => 'From first enquiry to follow-up',
                'lead' => 'We organise the international-patient journey around independent clinical decisions made by the partner clinic. One coordinator keeps records, providers, travel logistics and communication moving in the same direction.',
            ],
            'summary' => [
                'heading' => 'Three clear roles',
                'items' => [
                    'The patient explains the need and shares requested information.',
                    'Turkelite Medcare prepares, routes, compares and coordinates the case.',
                    'The clinic assesses, recommends, consents and provides treatment.',
                ],
            ],
            'model' => [
                'eyebrow' => 'Business model', 'heading' => 'One connected pathway, three distinct responsibilities',
                'text' => 'The platform is the coordination layer between international patients and enrolled healthcare providers, not the medical provider itself.',
            ],
            'experience' => [
                'eyebrow' => 'Step by step', 'heading' => 'What the patient actually experiences',
                'text' => 'Every stage has a clear owner, a clear output, and a clear point at which clinical responsibility stays with the treating provider.',
            ],
            'roles' => [
                'eyebrow' => 'Responsibility map', 'heading' => 'Who is responsible for what?',
                'patient' => ['label' => 'Patient', 'items' => ['Provides accurate medical information', 'Asks questions and compares options', 'Chooses the provider', 'Follows clinical and travel instructions']],
                'coordinator' => ['label' => 'Turkelite Medcare', 'items' => ['Structures and routes the case', 'Coordinates provider communication', 'Organises practical travel services', 'Keeps the journey and follow-up handover connected']],
                'clinic' => ['label' => 'Partner clinic', 'items' => ['Assesses clinical suitability', 'Explains treatment, risks and alternatives', 'Obtains informed consent', 'Provides treatment and clinical aftercare instructions']],
                'partner_eyebrow' => 'Partner model', 'partner_heading' => 'Turkelite Medcare is the practical coordination layer, not the medical provider.',
                'partner_text' => 'Partner clinics remain independent healthcare providers. Clinical recommendations, consent, treatment, and medical follow-up remain with the provider.',
            ],
            'cta' => ['eyebrow' => 'Ready to start?', 'heading' => 'Tell us what treatment you are considering.'],
            'quotations' => ['eyebrow' => 'Before you book', 'text' => 'Before a treatment journey is ready to book, each of these should have a clear answer and a clear owner.', 'decision' => 'Decision', 'why' => 'Why it matters', 'coordination' => 'How we coordinate it', 'cost_eyebrow' => 'Quotations', 'cost_heading' => 'What "included" actually means', 'cost_text' => 'Every clinic proposal should be broken down against the same checklist, so quotations can be compared on the same terms.', 'included' => 'Usually included', 'excluded' => 'Frequently excluded - ask directly', 'note' => 'A firm price can only follow clinical assessment. Any figure before a clinician reviews your case is an estimate and can change.'],
            'routes' => ['message_title' => 'Send us a message', 'message_text' => 'Ask one question. No commitment.', 'callback_title' => 'Request a callback', 'callback_text' => 'German or English, at a time that suits you.', 'plan_title' => 'Start a treatment enquiry', 'plan_text' => 'For when you are ready for a clinic to review your case.'],
            'steps' => [
                ['number' => '01', 'title' => 'Tell us what you need', 'text' => 'Choose a specialty or procedure, or simply describe the concern in your own words.', 'owner' => 'Patient'],
                ['number' => '02', 'title' => 'Share the requested records', 'text' => 'Upload reports, images, medication details and relevant history requested for preliminary review.', 'owner' => 'Patient'],
                ['number' => '03', 'title' => 'Case preparation', 'text' => 'We check completeness, organise the case, translate logistics where required and route it to suitable partner teams.', 'owner' => 'Turkelite Medcare'],
                ['number' => '04', 'title' => 'Clinical review', 'text' => 'The independent clinic or doctor evaluates the information and decides what assessment or treatment may be appropriate.', 'owner' => 'Partner clinic'],
            ],
            'decisions' => [
                'heading' => 'The four decisions that should have an owner',
                'items' => [
                    ['decision' => 'Is this procedure suitable for me?', 'why' => 'The procedure must fit your clinical situation, not simply your preference.', 'coordination' => 'We organise the records and questions the partner clinic asks for, so the clinical review can actually happen.'],
                    ['decision' => 'What exactly is included?', 'why' => 'Quotes can look similar while covering different treatment, facility and aftercare items.', 'coordination' => 'We structure the clinic proposal so inclusions, exclusions and practical extras are comparable.'],
                    ['decision' => 'When should I book travel?', 'why' => 'Clinical dates and recovery requirements should determine the journey, not the other way round.', 'coordination' => 'We sequence flights, transfers and accommodation around the clinic-confirmed plan.'],
                    ['decision' => 'What happens after I return?', 'why' => 'Follow-up should not become unclear once you leave Turkey.', 'coordination' => 'We make the handover, contact route and provider instructions visible before departure.'],
                ],
            ],
        ],
    ],
    'patient-services' => [
        'title' => 'Patient Services | Turkelite Medcare',
        'description' => 'Practical support for travel, scheduling, transfers, language and follow-up during a treatment journey.',
        'content' => [
            'hero' => [
                'kicker' => 'Practical support',
                'headline' => 'Patient Services',
                'lead' => 'Coordinated appointments, travel planning and return-home follow-up so the practical side of treatment stays connected.',
            ],
            'services' => [
                'heading' => 'What we coordinate',
                'items' => ['Coordinated appointments', 'Travel planning', 'Follow-up organisation', 'Language support'],
            ],
            'pre_travel' => [
                'eyebrow' => 'Before you travel',
                'heading' => 'What a well-prepared patient should have',
                'lead' => 'The exact requirements vary by treatment, but the practical package should be complete before departure.',
                'items' => [
                    ['number' => '01', 'title' => 'Confirmed clinical pathway', 'text' => 'Provider, appointment sequence, requested tests and next decision points.'],
                    ['number' => '02', 'title' => 'Journey itinerary', 'text' => 'Arrival, accommodation, clinic visits, transfer contacts and expected departure window.'],
                    ['number' => '03', 'title' => 'Named contacts', 'text' => 'Coordinator, clinic international desk and aftercare contact details.'],
                    ['number' => '04', 'title' => 'Follow-up plan', 'text' => 'Discharge documents, warning signs, review timing and return-home communication route.'],
                ],
            ],
            'cta' => [
                'eyebrow' => 'Patient support',
                'heading' => 'Build the treatment and travel plan together.',
                'button_label' => 'Start my request',
            ],
            'contact_routes' => [
                ['title' => 'Message us on WhatsApp', 'text' => 'Ask one question. No form, no commitment.', 'href' => 'https://wa.me/493023125400?text=Hello%2C%20I%20would%20like%20to%20ask%20about%20treatment%20in%20Turkey.'],
                ['title' => 'Request a callback', 'text' => 'German or English, at a time that suits you.', 'href' => '/contact#callback'],
                ['title' => 'Start a treatment enquiry', 'text' => 'For when you are ready for a clinic to review your case.', 'href' => '/treatment-plan'],
            ],
        ],
    ],
    'guides.index' => [
        'title' => 'Guides | Turkelite Medcare',
        'description' => 'Patient-friendly educational content for better-informed medical-travel decisions.',
        'content' => [
            'hero' => [
                'kicker' => 'Knowledge hub',
                'headline' => 'Guides',
                'lead' => 'Patient-friendly educational content supporting safer, better-informed medical-travel decisions.',
                'card_label' => 'Care pathway',
                'card_heading' => 'Plan -> Coordinate -> Return supported',
                'card_text' => 'One team coordinating the journey around your care.',
            ],
            'listing' => [
                'label' => 'Guide',
                'read_label' => 'Read guide',
                'empty_label' => 'Guide library',
                'empty_heading' => 'Guides are being prepared',
                'empty_text' => 'Published guide pages will appear here when they are added in the admin content library.',
            ],
            'contact_routes' => [
                ['title' => 'Send us a message', 'text' => 'Ask one question. No commitment.', 'href' => '/contact#message'],
                ['title' => 'Request a callback', 'text' => 'German or English, at a time that suits you.', 'href' => '/contact#callback'],
                ['title' => 'Start a treatment enquiry', 'text' => 'For when you are ready for a clinic to review your case.', 'href' => '/treatment-plan'],
            ],
        ],
    ],    'stories.index' => [
        'title' => 'Patient Stories | Turkelite Medcare',
        'description' => 'Published patient journey stories.',
        'content' => [
            'hero' => [
                'kicker' => 'Patient journeys',
                'headline' => 'Patient Stories',
                'lead' => 'See how research, clinical review, travel planning and follow-up can connect across a treatment journey.',
                'card_label' => 'Patient pathway',
                'card_heading' => 'Concern -> Options -> Provider -> Journey',
                'card_text' => 'Designed for transparent medical-travel planning',
            ],
            'intro' => [
                'eyebrow' => 'Journey examples',
                'heading' => 'From question to coordinated plan',
                'lead' => 'Each published story follows the journey from first research through provider review, travel preparation and follow-up planning.',
                'card_meta' => 'Patient journey',
                'published_label' => 'Published story',
                'read_label' => 'Read story',
                'date_label' => 'Story',
            ],
            'empty' => [
                'heading' => 'No patient stories are published yet',
                'text' => 'Published patient journeys will appear here soon.',
            ],
        ],
    ],    'doctors.index' => ['title' => 'Doctors | Turkelite Medcare', 'description' => 'Published specialist profiles from the partner network.', 'content' => ['hero' => ['kicker' => 'Medical teams', 'headline' => 'Doctors', 'lead' => 'Explore specialist profiles by medical focus, affiliated clinic, languages and treatment pathways.', 'card_label' => 'Provider transparency', 'card_heading' => 'Doctor -> Clinic -> Procedure', 'card_text' => 'Credentials must be verified before publication.'], 'listing' => ['eyebrow' => 'Specialist network', 'heading' => 'Specialists across our treatment pathways', 'lead' => 'Specialist profiles are structured around the information international patients need before requesting a clinical review.', 'card_label' => 'Specialist profile', 'empty_heading' => 'Specialist profiles are being prepared', 'empty_text' => 'Published profiles will appear here when they are added by the admin team.']]],
    'treatment-plan' => ['title' => 'Get Your Treatment Plan | Turkelite Medcare', 'description' => 'Start a treatment enquiry and share the information needed for coordinator review.', 'content' => ['hero' => ['kicker' => 'Start here', 'headline' => 'Get Your Treatment Plan', 'lead' => 'You do not need to know the exact procedure. Tell us what you are looking for and this structured request prepares the information for coordinator review.', 'card_label' => 'Care pathway', 'card_heading' => 'Plan, coordinate, return supported', 'card_text' => 'One team coordinating the practical journey around your care.'], 'form' => ['success_heading' => 'Request received', 'error' => 'Please check the highlighted fields and try again.', 'reply_heading' => 'A coordinator replies within one working day.', 'reply_text' => 'Monday to Friday, in German or English.', 'step1' => 'What can we help with?', 'step2' => 'About you', 'step3' => 'Tell us anything useful', 'next' => 'Continue', 'back' => 'Back', 'submit' => 'Submit Treatment Request'], 'aside' => ['heading' => 'What happens next?', 'privacy_heading' => 'Your information matters']]],
    'clinics.index' => ['title' => 'Clinics | Turkelite Medcare', 'description' => 'Compare published partner clinic profiles.', 'content' => ['hero' => ['kicker' => 'Partner network', 'headline' => 'Selected clinics in Turkey', 'lead' => 'Explore published partner clinics by city, profile, and international-patient support.', 'card_label' => 'Network snapshot']]],
    'treatments.index' => [
        'title' => 'Treatments | Turkelite Medcare',
        'description' => 'Browse the current specialty library and open a treatment pathway that matches your concern.',
        'content' => [
            'hero' => [
                'kicker' => 'Treatment library',
                'headline' => 'Treatments',
                'lead' => 'Choose a medical specialty, then explore the legacy condition and procedure pages already published for that pathway.', 'primary_action' => 'Get treatment plan', 'secondary_action' => 'Browse specialties', 'library_label' => 'How to use this library', 'specialty_step' => 'Choose a specialty', 'condition_step' => 'Start from a condition or concern', 'procedure_step' => 'Explore relevant procedures', 'clinic_step' => 'Compare clinics and specialists',
            ],
        ],
    ],
    'treatments.show' => [
        'title' => 'Treatment specialty | Turkelite Medcare',
        'description' => 'Open a treatment specialty pathway with the published condition and procedure pages.',
        'content' => [
            'hero' => [
                'kicker' => 'Treatment specialty',
                'headline' => 'Specialty detail',
                'lead' => 'Explore the current library and the published pages that sit behind this specialty.', 'procedure_action' => 'Explore procedures', 'assurance_plan' => 'Plan your journey', 'assurance_guidance' => 'Personal guidance', 'assurance_library' => 'Published library', 'options_label' => 'Treatment options', 'explore_label' => 'Explore', 'empty_text' => 'No procedure content is published yet.',
            ],
        ],
    ],
];
