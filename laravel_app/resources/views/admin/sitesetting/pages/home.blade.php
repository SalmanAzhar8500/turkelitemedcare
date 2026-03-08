@extends('admin.layouts.app')
@section('title', 'Home Page Settings')
@section('content')
   <style>
       .bootstrap-select .dropdown-toggle {
           display: none !important;
       }
   </style>
    <section class="content">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-5 col-sm-12">
                    <h2>Home Page Content Management
                        <small class="text-muted">Manage hero section, about us, and contact information</small>
                    </h2>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Hero</strong> Section</h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.hero.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Hero Title/Heading</label>
                                            <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $homeSetting->hero_title ?? '') }}" placeholder="Enter main heading (e.g., Welcome to Elite Med Care)">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Hero Subtitle</label>
                                            <input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $homeSetting->hero_subtitle ?? '') }}" placeholder="Enter subtitle or tagline">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Hero Description</label>
                                            <textarea name="hero_description" rows="4" class="form-control no-resize" placeholder="Enter brief description about your services">{{ old('hero_description', $homeSetting->hero_description ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Primary Button Text</label>
                                            <input type="text" name="hero_button_text" class="form-control" value="{{ old('hero_button_text', $homeSetting->hero_button_text ?? '') }}" placeholder="e.g., Get Started, Book Appointment">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Primary Button Link</label>
                                            <input type="text" name="hero_button_link" class="form-control" value="{{ old('hero_button_link', $homeSetting->hero_button_link ?? '') }}" placeholder="e.g., /services, /contact">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Secondary Button Text (Optional)</label>
                                            <input type="text" name="hero_button_text_secondary" class="form-control" value="{{ old('hero_button_text_secondary', $homeSetting->hero_button_text_secondary ?? '') }}" placeholder="e.g., Learn More, View Services">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Secondary Button Link (Optional)</label>
                                            <input type="text" name="hero_button_link_secondary" class="form-control" value="{{ old('hero_button_link_secondary', $homeSetting->hero_button_link_secondary ?? '') }}" placeholder="e.g., /about, /services">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Hero Background Image</label>
                                            <input type="file" name="hero_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended size: 1920x1080px</small>
                                            @if(!empty($homeSetting?->hero_image))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current image preview:</small>
                                                    <img src="{{ asset('storage/' . $homeSetting->hero_image) }}" alt="Hero Image" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Video URL (Optional - YouTube/Vimeo)</label>
                                            <input type="text" name="hero_video_url" class="form-control" value="{{ old('hero_video_url', $homeSetting->hero_video_url ?? '') }}" placeholder="Enter video URL for background video">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Hero Video Upload (Optional)</label>
                                            <input type="file" name="hero_video" class="form-control" accept="video/*">
                                            <small class="text-muted">Upload mp4/webm/mov (max 100MB)</small>
                                            @if(!empty($homeSetting?->hero_video))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current video preview:</small>
                                                    <video controls style="max-width:260px; height:auto; border-radius:6px; border:1px solid #ddd;">
                                                        <source src="{{ asset('storage/' . $homeSetting->hero_video) }}" type="video/mp4">
                                                    </video>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Hero Footer Items</strong></h5>
                                        <small class="text-muted">These are the small bullet points shown in hero section footer. You can add more items.</small>
                                    </div>
                                    <div class="col-sm-12" id="hero-items-wrapper">
                                        @php
                                            $heroItems = old('hero_items', $homeSetting->hero_items ?? []);
                                        @endphp

                                        @foreach($heroItems as $itemIndex => $heroItem)
                                            <div class="form-group hero-item-row">
                                                <label>Hero Item {{ $itemIndex + 1 }}</label>
                                                <div class="input-group">
                                                    <input type="text" name="hero_items[]" class="form-control" value="{{ $heroItem }}" placeholder="Enter hero footer item">
                                                    <span class="input-group-btn">
                                                        <button type="button" id="remove-hero-item-{{ $itemIndex }}" data-item-index="{{ $itemIndex }}" data-sync="1" class="btn btn-danger remove-hero-item">Remove</button>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="button" id="add-hero-item" class="btn btn-info btn-round">Add More Item</button>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save Hero Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>About Us</strong> Section <small>Brief introduction about your medical center</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.about.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @php
                                    $aboutData = $homeSetting->about_data ?? [];
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="about_title" class="form-control" value="{{ old('about_title', $aboutData['about_title'] ?? '') }}" placeholder="e.g., About Elite Med Care">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Subtitle</label>
                                            <input type="text" name="about_subtitle" class="form-control" value="{{ old('about_subtitle', $aboutData['about_subtitle'] ?? '') }}" placeholder="e.g., Your Health is Our Priority">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>About Description</label>
                                            <textarea name="about_description" rows="6" class="form-control no-resize" placeholder="Enter detailed description about your medical center, services, and values">{{ old('about_description', $aboutData['about_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Support Title</label>
                                            <input type="text" name="about_support_title" class="form-control" value="{{ old('about_support_title', $aboutData['about_support_title'] ?? '') }}" placeholder="e.g., End-to-End Patient Support">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Support Description</label>
                                            <textarea name="about_support_description" rows="3" class="form-control no-resize" placeholder="Enter support description">{{ old('about_support_description', $aboutData['about_support_description'] ?? ($aboutData['about_support_text'] ?? '')) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Support Icon SVG (Code)</label>
                                            <textarea name="about_support_icon_svg" rows="5" class="form-control no-resize" placeholder="Paste SVG code here, e.g. <svg ...>...</svg>">{{ old('about_support_icon_svg', $aboutData['about_support_icon_svg'] ?? '') }}</textarea>
                                            <small class="text-muted">Upload ki zarurat nahi, yahan direct SVG markup paste karein.</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Years of Experience</label>
                                            <input type="number" name="about_years" class="form-control" value="{{ old('about_years', $aboutData['about_years'] ?? '') }}" placeholder="e.g., 25">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Number of Doctors</label>
                                            <input type="number" name="about_doctors" class="form-control" value="{{ old('about_doctors', $aboutData['about_doctors'] ?? '') }}" placeholder="e.g., 50">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>About Section Image</label>
                                            <input type="file" name="about_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended size: 800x600px</small>
                                            @if(!empty($homeSetting?->about_data['about_image']))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current image 1 preview:</small>
                                                    <img src="{{ asset('storage/' . $homeSetting->about_data['about_image']) }}" alt="About Image 1" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>About Section Image 2 (Second Div)</label>
                                            <input type="file" name="about_image2" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended size: 800x600px</small>
                                            @if(!empty($homeSetting?->about_data['about_image2']))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current image 2 preview:</small>
                                                    <img src="{{ asset('storage/' . $homeSetting->about_data['about_image2']) }}" alt="About Image 2" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save About Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Contact & Social Media</strong> Links <small>Add your contact and social media information</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.contact.store') }}" method="POST">
                                @csrf
                                @php
                                    $contactData = $homeSetting->contact_data ?? [];
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-phone"></i></span>
                                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $contactData['contact_phone'] ?? '') }}" placeholder="+90 123 456 7890">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $contactData['contact_email'] ?? '') }}" placeholder="info@elitemedcare.com">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-pin"></i></span>
                                                <input type="text" name="contact_address" class="form-control" value="{{ old('contact_address', $contactData['contact_address'] ?? '') }}" placeholder="Enter full address">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Social Media Links</strong></h5>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Facebook URL</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-facebook"></i></span>
                                                <input type="url" name="social_facebook" class="form-control" value="{{ old('social_facebook', $contactData['social_facebook'] ?? '') }}" placeholder="https://facebook.com/yourpage">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Twitter URL</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-twitter"></i></span>
                                                <input type="url" name="social_twitter" class="form-control" value="{{ old('social_twitter', $contactData['social_twitter'] ?? '') }}" placeholder="https://twitter.com/yourpage">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Instagram URL</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-instagram"></i></span>
                                                <input type="url" name="social_instagram" class="form-control" value="{{ old('social_instagram', $contactData['social_instagram'] ?? '') }}" placeholder="https://instagram.com/yourpage">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>LinkedIn URL</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-linkedin"></i></span>
                                                <input type="url" name="social_linkedin" class="form-control" value="{{ old('social_linkedin', $contactData['social_linkedin'] ?? '') }}" placeholder="https://linkedin.com/company/yourpage">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>YouTube URL</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-youtube-play"></i></span>
                                                <input type="url" name="social_youtube" class="form-control" value="{{ old('social_youtube', $contactData['social_youtube'] ?? '') }}" placeholder="https://youtube.com/channel/yourpage">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>WhatsApp Number</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-whatsapp"></i></span>
                                                <input type="text" name="social_whatsapp" class="form-control" value="{{ old('social_whatsapp', $contactData['social_whatsapp'] ?? '') }}" placeholder="+90 123 456 7890">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save Contact & Social Media</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Services Section Start -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Our Services</strong> Section <small>Manage comprehensive services section</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.services.store') }}" method="POST">
                                @csrf
                                @php
                                    $servicesData = $homeSetting->services_data ?? [];
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Subtitle</label>
                                            <input type="text" name="services_subtitle" class="form-control" value="{{ old('services_subtitle', $servicesData['services_subtitle'] ?? '') }}" placeholder="e.g., services">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="services_title" class="form-control" value="{{ old('services_title', $servicesData['services_title'] ?? '') }}" placeholder="e.g., Our comprehensive services">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Description</label>
                                            <textarea name="services_description" rows="4" class="form-control no-resize" placeholder="Enter description about your services">{{ old('services_description', $servicesData['services_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="alert alert-info">
                                            <strong>Note:</strong> Service items are managed separately through <a href="{{ route('admin.site.services') }}">Services Management</a> page. This section only manages the header content.
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Footer Contact Text</label>
                                            <input type="text" name="services_footer_text" class="form-control" value="{{ old('services_footer_text', $servicesData['services_footer_text'] ?? '') }}" placeholder="e.g., You will be satisfy with our work">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Footer Contact Phone</label>
                                            <input type="text" name="services_footer_phone" class="form-control" value="{{ old('services_footer_phone', $servicesData['services_footer_phone'] ?? '') }}" placeholder="e.g., (+91) 123 456 789">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save Services Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Services Section End -->

            <!-- What We Do Section Start -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>What We Do</strong> Section <small>Highlight your key features and capabilities</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.whatwedo.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @php
                                    $whatWeDoData = $homeSetting->whatwedo_data ?? [];
                                    $whatWeDoFeatures = old('whatwedo_features', $whatWeDoData['features'] ?? []);

                                    if (empty($whatWeDoFeatures)) {
                                        $legacyFeatures = [
                                            [
                                                'icon' => $whatWeDoData['whatwedo_feature1_icon'] ?? '',
                                                'title' => $whatWeDoData['whatwedo_feature1_title'] ?? '',
                                                'desc' => $whatWeDoData['whatwedo_feature1_desc'] ?? '',
                                            ],
                                            [
                                                'icon' => $whatWeDoData['whatwedo_feature2_icon'] ?? '',
                                                'title' => $whatWeDoData['whatwedo_feature2_title'] ?? '',
                                                'desc' => $whatWeDoData['whatwedo_feature2_desc'] ?? '',
                                            ],
                                            [
                                                'icon' => $whatWeDoData['whatwedo_feature3_icon'] ?? '',
                                                'title' => $whatWeDoData['whatwedo_feature3_title'] ?? '',
                                                'desc' => $whatWeDoData['whatwedo_feature3_desc'] ?? '',
                                            ],
                                        ];

                                        $hasLegacyData = collect($legacyFeatures)->contains(function ($item) {
                                            return !empty($item['icon']) || !empty($item['title']) || !empty($item['desc']);
                                        });

                                        $whatWeDoFeatures = $hasLegacyData ? $legacyFeatures : [['icon' => '', 'title' => '', 'desc' => '']];
                                    }
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="whatwedo_title" class="form-control" value="{{ old('whatwedo_title', $whatWeDoData['whatwedo_title'] ?? '') }}" placeholder="e.g., What we do for you">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Subtitle</label>
                                            <input type="text" name="whatwedo_subtitle" class="form-control" value="{{ old('whatwedo_subtitle', $whatWeDoData['whatwedo_subtitle'] ?? '') }}" placeholder="e.g., what we do">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Feature Items</strong></h5>
                                    </div>
                                    <div class="col-sm-12" id="whatwedo-features-wrapper">
                                        @foreach($whatWeDoFeatures as $featureIndex => $feature)
                                            <div class="whatwedo-feature-item" style="padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;">
                                                <div class="row clearfix">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Feature {{ $featureIndex + 1 }} - Icon Class</label>
                                                            <input type="text" name="whatwedo_features[{{ $featureIndex }}][icon]" class="form-control" value="{{ $feature['icon'] ?? '' }}" placeholder="e.g., flaticon-food-donation">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Feature {{ $featureIndex + 1 }} - Title</label>
                                                            <input type="text" name="whatwedo_features[{{ $featureIndex }}][title]" class="form-control" value="{{ $feature['title'] ?? '' }}" placeholder="e.g., Food For Hunger">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Feature {{ $featureIndex + 1 }} - Description</label>
                                                            <textarea name="whatwedo_features[{{ $featureIndex }}][desc]" rows="3" class="form-control no-resize" placeholder="Enter feature description">{{ $feature['desc'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="button" id="remove-whatwedo-feature-{{ $featureIndex }}" data-item-index="{{ $featureIndex }}" data-sync="1" class="btn btn-danger btn-round remove-whatwedo-feature">Remove Feature</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="button" id="add-whatwedo-feature" class="btn btn-info btn-round">Add More Feature</button>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Section Images</strong></h5>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Image 1 (Main)</label>
                                            <input type="file" name="whatwedo_image1" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended size: 600x700px</small>
                                            @if(!empty($whatWeDoData['whatwedo_image1']))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current image 1 preview:</small>
                                                    <img src="{{ asset('storage/' . $whatWeDoData['whatwedo_image1']) }}" alt="What We Do Image 1" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Image 2 (Secondary)</label>
                                            <input type="file" name="whatwedo_image2" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended size: 400x500px</small>
                                            @if(!empty($whatWeDoData['whatwedo_image2']))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current image 2 preview:</small>
                                                    <img src="{{ asset('storage/' . $whatWeDoData['whatwedo_image2']) }}" alt="What We Do Image 2" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save What We Do Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- What We Do Section End -->

            <!-- Our Causes Section Start -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Our Causes</strong> Section <small>Supporting communities causes content</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.causes.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @php
                                    $causesData = $homeSetting->causes_data ?? [];
                                    $causeItems = old('causes', $causesData['items'] ?? []);

                                    if (empty($causeItems)) {
                                        $legacyCauses = [
                                            [
                                                'title' => $causesData['cause1_title'] ?? '',
                                                'category' => $causesData['cause1_category'] ?? '',
                                                'desc' => $causesData['cause1_desc'] ?? '',
                                                'goal' => $causesData['cause1_goal'] ?? '',
                                                'raised' => $causesData['cause1_raised'] ?? '',
                                                'link' => $causesData['cause1_link'] ?? '',
                                                'image' => $causesData['cause1_image'] ?? '',
                                            ],
                                            [
                                                'title' => $causesData['cause2_title'] ?? '',
                                                'category' => $causesData['cause2_category'] ?? '',
                                                'desc' => $causesData['cause2_desc'] ?? '',
                                                'goal' => $causesData['cause2_goal'] ?? '',
                                                'raised' => $causesData['cause2_raised'] ?? '',
                                                'link' => $causesData['cause2_link'] ?? '',
                                                'image' => $causesData['cause2_image'] ?? '',
                                            ],
                                            [
                                                'title' => $causesData['cause3_title'] ?? '',
                                                'category' => $causesData['cause3_category'] ?? '',
                                                'desc' => $causesData['cause3_desc'] ?? '',
                                                'goal' => $causesData['cause3_goal'] ?? '',
                                                'raised' => $causesData['cause3_raised'] ?? '',
                                                'link' => $causesData['cause3_link'] ?? '',
                                                'image' => $causesData['cause3_image'] ?? '',
                                            ],
                                        ];

                                        $hasLegacyCauseData = collect($legacyCauses)->contains(function ($item) {
                                            return !empty($item['title']) || !empty($item['category']) || !empty($item['goal']) || !empty($item['raised']) || !empty($item['link']) || !empty($item['image']);
                                        });

                                        $causeItems = $hasLegacyCauseData ? $legacyCauses : [['title' => '', 'category' => '', 'goal' => '', 'raised' => '', 'link' => '', 'image' => '']];
                                    }
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Subtitle</label>
                                            <input type="text" name="causes_subtitle" class="form-control" value="{{ old('causes_subtitle', $causesData['causes_subtitle'] ?? '') }}" placeholder="e.g., our causes">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="causes_title" class="form-control" value="{{ old('causes_title', $causesData['causes_title'] ?? '') }}" placeholder="e.g., Supporting communities causes">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Description</label>
                                            <textarea name="causes_description" rows="4" class="form-control no-resize" placeholder="Enter description about your causes">{{ old('causes_description', $causesData['causes_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Cause Items</strong></h5>
                                    </div>
                                    <div class="col-sm-12" id="causes-items-wrapper">
                                        @foreach($causeItems as $causeIndex => $causeItem)
                                            <div class="cause-item" style="padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;">
                                                <div class="row clearfix">
                                                    <div class="col-sm-12">
                                                        <h5><strong>Cause Item {{ $causeIndex + 1 }}</strong></h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <input type="text" name="causes[{{ $causeIndex }}][title]" class="form-control" value="{{ $causeItem['title'] ?? '' }}" placeholder="e.g., Education For All Children">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <input type="text" name="causes[{{ $causeIndex }}][category]" class="form-control" value="{{ $causeItem['category'] ?? '' }}" placeholder="e.g., Education">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <textarea name="causes[{{ $causeIndex }}][desc]" rows="3" class="form-control no-resize" placeholder="Enter cause description">{{ $causeItem['desc'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Image</label>
                                                            @if(!empty($causeItem['image']))
                                                                <input type="hidden" name="causes[{{ $causeIndex }}][existing_image]" value="{{ $causeItem['image'] }}">
                                                            @endif
                                                            <input type="file" name="causes[{{ $causeIndex }}][image]" class="form-control" accept="image/*">
                                                            <small class="text-muted">Recommended size: 600x400px</small>
                                                            @if(!empty($causeItem['image']))
                                                                <div style="margin-top:10px;">
                                                                    <small class="text-success d-block">Current image preview:</small>
                                                                    <img src="{{ asset('storage/' . $causeItem['image']) }}" alt="Cause Image {{ $causeIndex + 1 }}" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Goal Amount ($)</label>
                                                            <input type="number" step="0.01" name="causes[{{ $causeIndex }}][goal]" class="form-control" value="{{ $causeItem['goal'] ?? '' }}" placeholder="e.g., 25000">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Raised Amount ($)</label>
                                                            <input type="number" step="0.01" name="causes[{{ $causeIndex }}][raised]" class="form-control" value="{{ $causeItem['raised'] ?? '' }}" placeholder="e.g., 18500">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label>Link URL</label>
                                                            <input type="text" name="causes[{{ $causeIndex }}][link]" class="form-control" value="{{ $causeItem['link'] ?? '' }}" placeholder="e.g., /causes/education">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="button" id="remove-cause-item-{{ $causeIndex }}" data-item-index="{{ $causeIndex }}" data-sync="1" class="btn btn-danger btn-round remove-cause-item">Remove Cause</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12" style="margin-bottom: 12px;">
                                        <button type="button" id="add-cause-item" class="btn btn-info btn-round">Add More Cause</button>
                                    </div>

                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save Our Causes Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Our Causes Section End -->

            <!-- Why Choose Us Section Start -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Why Choose Us</strong> Section <small>Manage why choose us content and counters</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.whychoose.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @php
                                    $whyChooseData = $homeSetting->whychoose_data ?? [];
                                    $defaultWhyChoosePoints = [
                                        'community-centered approach',
                                        'transparency and accountability',
                                        'empowerment through partner',
                                        'volunteer and donor engagement',
                                    ];
                                    $whyChoosePoints = old('whychoose_points', $whyChooseData['whychoose_points'] ?? $defaultWhyChoosePoints);
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Subtitle</label>
                                            <input type="text" name="whychoose_subtitle" class="form-control" value="{{ old('whychoose_subtitle', $whyChooseData['whychoose_subtitle'] ?? '') }}" placeholder="e.g., why choose us">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="whychoose_title" class="form-control" value="{{ old('whychoose_title', $whyChooseData['whychoose_title'] ?? '') }}" placeholder="e.g., Why we stand out together">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Description</label>
                                            <textarea name="whychoose_description" rows="4" class="form-control no-resize" placeholder="Enter why choose us description">{{ old('whychoose_description', $whyChooseData['whychoose_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>List Items</strong></h5>
                                    </div>
                                    <div class="col-sm-12" id="whychoose-points-wrapper">
                                        @foreach($whyChoosePoints as $pointIndex => $point)
                                            <div class="form-group whychoose-point-row">
                                                <label>Point {{ $pointIndex + 1 }}</label>
                                                <div class="input-group">
                                                    <input type="text" name="whychoose_points[]" class="form-control" value="{{ $point }}" placeholder="Enter list item">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-danger remove-whychoose-point">Remove</button>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12" style="margin-bottom:12px;">
                                        <button type="button" id="add-whychoose-point" class="btn btn-info btn-round">Add More Point</button>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Counters</strong></h5>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Counter 1 Number</label>
                                            <input type="text" name="whychoose_counter1_number" class="form-control" value="{{ old('whychoose_counter1_number', $whyChooseData['whychoose_counter1_number'] ?? '') }}" placeholder="e.g., 25+">
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Counter 1 Label</label>
                                            <input type="text" name="whychoose_counter1_label" class="form-control" value="{{ old('whychoose_counter1_label', $whyChooseData['whychoose_counter1_label'] ?? '') }}" placeholder="e.g., Years of experience">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Counter 2 Number</label>
                                            <input type="text" name="whychoose_counter2_number" class="form-control" value="{{ old('whychoose_counter2_number', $whyChooseData['whychoose_counter2_number'] ?? '') }}" placeholder="e.g., 230+">
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Counter 2 Label</label>
                                            <input type="text" name="whychoose_counter2_label" class="form-control" value="{{ old('whychoose_counter2_label', $whyChooseData['whychoose_counter2_label'] ?? '') }}" placeholder="e.g., Thousands volunteers">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Counter 3 Number</label>
                                            <input type="text" name="whychoose_counter3_number" class="form-control" value="{{ old('whychoose_counter3_number', $whyChooseData['whychoose_counter3_number'] ?? '') }}" placeholder="e.g., 400+">
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Counter 3 Label</label>
                                            <input type="text" name="whychoose_counter3_label" class="form-control" value="{{ old('whychoose_counter3_label', $whyChooseData['whychoose_counter3_label'] ?? '') }}" placeholder="e.g., Word wide office">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Image 1</label>
                                            <input type="file" name="whychoose_image1" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended size: 600x700px</small>
                                            @if(!empty($whyChooseData['whychoose_image1']))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current image 1 preview:</small>
                                                    <img src="{{ asset('storage/' . $whyChooseData['whychoose_image1']) }}" alt="Why Choose Image 1" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Image 2</label>
                                            <input type="file" name="whychoose_image2" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended size: 500x600px</small>
                                            @if(!empty($whyChooseData['whychoose_image2']))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current image 2 preview:</small>
                                                    <img src="{{ asset('storage/' . $whyChooseData['whychoose_image2']) }}" alt="Why Choose Image 2" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save Why Choose Us Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Why Choose Us Section End -->

            <!-- How It Work Section Start -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>How It Work</strong> Section <small>Manage process steps and action content</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.howitwork.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @php
                                    $howItWorkData = $homeSetting->howitwork_data ?? [];
                                    $howItWorkSteps = old('howitwork_steps', $howItWorkData['steps'] ?? []);

                                    if (empty($howItWorkSteps)) {
                                        $howItWorkSteps = [
                                            ['title' => '', 'desc' => '', 'image' => ''],
                                        ];
                                    }
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Subtitle</label>
                                            <input type="text" name="howitwork_subtitle" class="form-control" value="{{ old('howitwork_subtitle', $howItWorkData['howitwork_subtitle'] ?? '') }}" placeholder="e.g., work process">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="howitwork_title" class="form-control" value="{{ old('howitwork_title', $howItWorkData['howitwork_title'] ?? '') }}" placeholder="e.g., How we work process">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Description</label>
                                            <textarea name="howitwork_description" rows="4" class="form-control no-resize" placeholder="Enter section description">{{ old('howitwork_description', $howItWorkData['howitwork_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Process Steps</strong></h5>
                                    </div>
                                    <div class="col-sm-12" id="howitwork-steps-wrapper">
                                        @foreach($howItWorkSteps as $stepIndex => $step)
                                            <div class="howitwork-step-item" style="padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;">
                                                <div class="row clearfix">
                                                    <div class="col-sm-12">
                                                        <h5><strong>Step {{ $stepIndex + 1 }}</strong></h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Step Title</label>
                                                            <input type="text" name="howitwork_steps[{{ $stepIndex }}][title]" class="form-control" value="{{ $step['title'] ?? '' }}" placeholder="e.g., Start Donation">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Step Image</label>
                                                            @if(!empty($step['image']))
                                                                <input type="hidden" name="howitwork_steps[{{ $stepIndex }}][existing_image]" value="{{ $step['image'] }}">
                                                            @endif
                                                            <input type="file" name="howitwork_steps[{{ $stepIndex }}][image]" class="form-control" accept="image/*">
                                                            <small class="text-muted">Recommended size: 400x300px</small>
                                                            @if(!empty($step['image']))
                                                                <div style="margin-top:10px;">
                                                                    <small class="text-success d-block">Current step image preview:</small>
                                                                    <img src="{{ asset('storage/' . $step['image']) }}" alt="How It Work Step {{ $stepIndex + 1 }}" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Step Description</label>
                                                            <textarea name="howitwork_steps[{{ $stepIndex }}][desc]" rows="3" class="form-control no-resize" placeholder="Enter step description">{{ $step['desc'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Step Icon SVG (Code)</label>
                                                            <textarea name="howitwork_steps[{{ $stepIndex }}][icon_svg]" rows="4" class="form-control no-resize" placeholder="Paste SVG code, e.g. <svg ...>...</svg>">{{ $step['icon_svg'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-danger btn-round remove-howitwork-step">Remove Step</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12" style="margin-bottom:12px;">
                                        <button type="button" id="add-howitwork-step" class="btn btn-info btn-round">Add More Step</button>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Button Text</label>
                                            <input type="text" name="howitwork_button_text" class="form-control" value="{{ old('howitwork_button_text', $howItWorkData['howitwork_button_text'] ?? '') }}" placeholder="e.g., view all causes">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Button Link</label>
                                            <input type="text" name="howitwork_button_link" class="form-control" value="{{ old('howitwork_button_link', $howItWorkData['howitwork_button_link'] ?? '') }}" placeholder="e.g., /causes">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save How It Work Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- How It Work Section End -->

            <!-- Testimonials Section Start -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Testimonials</strong> Section <small>Manage client feedback and review items</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @php
                                    $testimonialsData = $homeSetting->testimonials_data ?? [];
                                    $testimonialItems = old('testimonials', $testimonialsData['items'] ?? []);

                                    if (empty($testimonialItems)) {
                                        $testimonialItems = [['name' => '', 'designation' => '', 'quote' => '', 'image' => '']];
                                    }
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Subtitle</label>
                                            <input type="text" name="testimonials_subtitle" class="form-control" value="{{ old('testimonials_subtitle', $testimonialsData['testimonials_subtitle'] ?? '') }}" placeholder="e.g., testimonials">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="testimonials_title" class="form-control" value="{{ old('testimonials_title', $testimonialsData['testimonials_title'] ?? '') }}" placeholder="e.g., What people say about us">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Description</label>
                                            <textarea name="testimonials_description" rows="4" class="form-control no-resize" placeholder="Enter testimonials section description">{{ old('testimonials_description', $testimonialsData['testimonials_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                       <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Main Image</label>
                                            <input type="file" name="testimonials_main_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended size: 700x760px</small>
                                            @if(!empty($testimonialsData['testimonials_main_image']))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current main image preview:</small>
                                                    <img src="{{ asset('storage/' . $testimonialsData['testimonials_main_image']) }}" alt="Testimonials Main Image" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Testimonial Items</strong></h5>
                                    </div>
                                    <div class="col-sm-12" id="testimonials-items-wrapper">
                                        @foreach($testimonialItems as $testimonialIndex => $testimonialItem)
                                            <div class="testimonial-item" style="padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;">
                                                <div class="row clearfix">
                                                    <div class="col-sm-12">
                                                        <h5><strong>Testimonial {{ $testimonialIndex + 1 }}</strong></h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" name="testimonials[{{ $testimonialIndex }}][name]" class="form-control" value="{{ $testimonialItem['name'] ?? '' }}" placeholder="e.g., John Doe">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Designation</label>
                                                            <input type="text" name="testimonials[{{ $testimonialIndex }}][designation]" class="form-control" value="{{ $testimonialItem['designation'] ?? '' }}" placeholder="e.g., Volunteer">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Quote</label>
                                                            <textarea name="testimonials[{{ $testimonialIndex }}][quote]" rows="3" class="form-control no-resize" placeholder="Enter review text">{{ $testimonialItem['quote'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Profile Image</label>
                                                            @if(!empty($testimonialItem['image']))
                                                                <input type="hidden" name="testimonials[{{ $testimonialIndex }}][existing_image]" value="{{ $testimonialItem['image'] }}">
                                                            @endif
                                                            <input type="file" name="testimonials[{{ $testimonialIndex }}][image]" class="form-control" accept="image/*">
                                                            <small class="text-muted">Recommended size: 120x120px</small>
                                                            @if(!empty($testimonialItem['image']))
                                                                <div style="margin-top:10px;">
                                                                    <small class="text-success d-block">Current image preview:</small>
                                                                    <img src="{{ asset('storage/' . $testimonialItem['image']) }}" alt="Testimonial {{ $testimonialIndex + 1 }}" style="max-width:120px; height:auto; border-radius:50%; border:1px solid #ddd; padding:2px;">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-danger btn-round remove-testimonial-item">Remove Testimonial</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12" style="margin-bottom:12px;">
                                        <button type="button" id="add-testimonial-item" class="btn btn-info btn-round">Add More Testimonial</button>
                                    </div>

                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save Testimonials Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Testimonials Section End -->

            <!-- Our Gallery Section Start -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Our Gallery</strong> Section <small>Manage gallery images and categories</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.gallery.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @php
                                    $galleryData = $homeSetting->gallery_data ?? [];
                                    $galleryItems = old('gallery', $galleryData['items'] ?? []);

                                    if (empty($galleryItems)) {
                                        $galleryItems = [['title' => '', 'category' => '', 'image' => '']];
                                    }
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Subtitle</label>
                                            <input type="text" name="gallery_subtitle" class="form-control" value="{{ old('gallery_subtitle', $galleryData['gallery_subtitle'] ?? '') }}" placeholder="e.g., our gallery">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="gallery_title" class="form-control" value="{{ old('gallery_title', $galleryData['gallery_title'] ?? '') }}" placeholder="e.g., Moments from our work">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Description</label>
                                            <textarea name="gallery_description" rows="4" class="form-control no-resize" placeholder="Enter gallery section description">{{ old('gallery_description', $galleryData['gallery_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Gallery Items</strong></h5>
                                    </div>
                                    <div class="col-sm-12" id="gallery-items-wrapper">
                                        @foreach($galleryItems as $galleryIndex => $galleryItem)
                                            <div class="gallery-item" style="padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;">
                                                <div class="row clearfix">
                                                    <div class="col-sm-12">
                                                        <h5><strong>Gallery Item {{ $galleryIndex + 1 }}</strong></h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Image Title</label>
                                                            <input type="text" name="gallery[{{ $galleryIndex }}][title]" class="form-control" value="{{ $galleryItem['title'] ?? '' }}" placeholder="e.g., Medical Camp 2026">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <input type="text" name="gallery[{{ $galleryIndex }}][category]" class="form-control" value="{{ $galleryItem['category'] ?? '' }}" placeholder="e.g., Camp">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Image</label>
                                                            @if(!empty($galleryItem['image']))
                                                                <input type="hidden" name="gallery[{{ $galleryIndex }}][existing_image]" value="{{ $galleryItem['image'] }}">
                                                            @endif
                                                            <input type="file" name="gallery[{{ $galleryIndex }}][image]" class="form-control" accept="image/*">
                                                            <small class="text-muted">Recommended size: 800x600px</small>
                                                            @if(!empty($galleryItem['image']))
                                                                <div style="margin-top:10px;">
                                                                    <small class="text-success d-block">Current image preview:</small>
                                                                    <img src="{{ asset('storage/' . $galleryItem['image']) }}" alt="Gallery Image {{ $galleryIndex + 1 }}" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-danger btn-round remove-gallery-item">Remove Gallery Item</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12" style="margin-bottom:12px;">
                                        <button type="button" id="add-gallery-item" class="btn btn-info btn-round">Add More Gallery Item</button>
                                    </div>

                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save Our Gallery Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Our Gallery Section End -->

            <!-- Last Hope Section Start -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Last Hope</strong> Section <small>Manage final call-to-action content</small> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.site.home.lasthope.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @php
                                    $lastHopeData = $homeSetting->lasthope_data ?? [];
                                    $lastHopeItems = old('lasthope_items', $lastHopeData['items'] ?? []);

                                    if (empty($lastHopeItems)) {
                                        $lastHopeItems = [['title' => '', 'description' => '', 'image' => '']];
                                    }
                                @endphp
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Subtitle</label>
                                            <input type="text" name="lasthope_subtitle" class="form-control" value="{{ old('lasthope_subtitle', $lastHopeData['lasthope_subtitle'] ?? '') }}" placeholder="e.g., last hope">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="lasthope_title" class="form-control" value="{{ old('lasthope_title', $lastHopeData['lasthope_title'] ?? '') }}" placeholder="e.g., Help us make a better world">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Section Description</label>
                                            <textarea name="lasthope_description" rows="4" class="form-control no-resize" placeholder="Enter section description">{{ old('lasthope_description', $lastHopeData['lasthope_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Background / Section Image</label>
                                            <input type="file" name="lasthope_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Recommended size: 1600x900px</small>
                                            @if(!empty($lastHopeData['lasthope_image']))
                                                <div style="margin-top:10px;">
                                                    <small class="text-success d-block">Current image preview:</small>
                                                    <img src="{{ asset('storage/' . $lastHopeData['lasthope_image']) }}" alt="Last Hope Image" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr>
                                        <h5><strong>Hope Items</strong></h5>
                                    </div>
                                    <div class="col-sm-12" id="lasthope-items-wrapper">
                                        @foreach($lastHopeItems as $lastHopeIndex => $lastHopeItem)
                                            <div class="lasthope-item" style="padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;">
                                                <div class="row clearfix">
                                                    <div class="col-sm-12">
                                                        <h5><strong>Hope Item {{ $lastHopeIndex + 1 }}</strong></h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Hope Title</label>
                                                            <input type="text" name="lasthope_items[{{ $lastHopeIndex }}][title]" class="form-control" value="{{ $lastHopeItem['title'] ?? '' }}" placeholder="e.g., Medical Support for Families">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Hope Image</label>
                                                            @if(!empty($lastHopeItem['image']))
                                                                <input type="hidden" name="lasthope_items[{{ $lastHopeIndex }}][existing_image]" value="{{ $lastHopeItem['image'] }}">
                                                            @endif
                                                            <input type="file" name="lasthope_items[{{ $lastHopeIndex }}][image]" class="form-control" accept="image/*">
                                                            <small class="text-muted">Recommended size: 600x400px</small>
                                                            @if(!empty($lastHopeItem['image']))
                                                                <div style="margin-top:10px;">
                                                                    <small class="text-success d-block">Current hope image preview:</small>
                                                                    <img src="{{ asset('storage/' . $lastHopeItem['image']) }}" alt="Last Hope Item {{ $lastHopeIndex + 1 }}" style="max-width:220px; height:auto; border-radius:6px; border:1px solid #ddd; padding:2px;">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Hope Description</label>
                                                            <textarea name="lasthope_items[{{ $lastHopeIndex }}][description]" rows="3" class="form-control no-resize" placeholder="Enter hope description">{{ $lastHopeItem['description'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-danger btn-round remove-lasthope-item">Remove Hope Item</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12" style="margin-bottom:12px;">
                                        <button type="button" id="add-lasthope-item" class="btn btn-info btn-round">Add More Hope</button>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Button Text</label>
                                            <input type="text" name="lasthope_button_text" class="form-control" value="{{ old('lasthope_button_text', $lastHopeData['lasthope_button_text'] ?? '') }}" placeholder="e.g., Donate Now">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Button Link</label>
                                            <input type="text" name="lasthope_button_link" class="form-control" value="{{ old('lasthope_button_link', $lastHopeData['lasthope_button_link'] ?? '') }}" placeholder="e.g., /donate">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-round">Save Last Hope Section</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Last Hope Section End -->
            
        </div>
    </section>

@endsection

@push('js')
<script>
    (function () {
        let heroItemIndex = document.querySelectorAll('#hero-items-wrapper .hero-item-row').length;
        let whatWeDoFeatureIndex = document.querySelectorAll('#whatwedo-features-wrapper .whatwedo-feature-item').length;
        let causeItemIndex = document.querySelectorAll('#causes-items-wrapper .cause-item').length;
        let whyChoosePointIndex = document.querySelectorAll('#whychoose-points-wrapper .whychoose-point-row').length;
        let howItWorkStepIndex = document.querySelectorAll('#howitwork-steps-wrapper .howitwork-step-item').length;
        let testimonialItemIndex = document.querySelectorAll('#testimonials-items-wrapper .testimonial-item').length;
        let galleryItemIndex = document.querySelectorAll('#gallery-items-wrapper .gallery-item').length;
        let lastHopeItemIndex = document.querySelectorAll('#lasthope-items-wrapper .lasthope-item').length;

        document.addEventListener('click', function (event) {
            if (event.target && event.target.id === 'add-hero-item') {
                heroItemIndex++;
                const wrapper = document.getElementById('hero-items-wrapper');
                if (!wrapper) return;

                const row = document.createElement('div');
                row.className = 'form-group hero-item-row';
                row.innerHTML = `
                    <label>Hero Item ${heroItemIndex}</label>
                    <div class="input-group">
                        <input type="text" name="hero_items[]" class="form-control" placeholder="Enter hero footer item">
                        <span class="input-group-btn">
                            <button type="button" id="remove-hero-item-new-${heroItemIndex}" data-item-index="new-${heroItemIndex}" data-sync="0" class="btn btn-danger remove-hero-item">Remove</button>
                        </span>
                    </div>
                `;
                wrapper.appendChild(row);
            }

            if (event.target && event.target.classList.contains('remove-hero-item')) {
                const button = event.target;
                const row = button.closest('.hero-item-row');
                if (!row) return;

                const reindexHeroRows = () => {
                    let syncIndex = 0;
                    const rows = document.querySelectorAll('#hero-items-wrapper .hero-item-row');

                    rows.forEach((itemRow, visualIndex) => {
                        const label = itemRow.querySelector('label');
                        if (label) {
                            label.textContent = `Hero Item ${visualIndex + 1}`;
                        }

                        const removeBtn = itemRow.querySelector('.remove-hero-item');
                        if (!removeBtn) return;

                        if (removeBtn.dataset.sync === '1') {
                            removeBtn.dataset.itemIndex = syncIndex;
                            removeBtn.id = `remove-hero-item-${syncIndex}`;
                            syncIndex++;
                        } else {
                            removeBtn.id = `remove-hero-item-new-${visualIndex + 1}`;
                        }
                    });

                    heroItemIndex = rows.length;
                };

                const isSynced = button.dataset.sync === '1';

                if (!isSynced) {
                    row.remove();
                    reindexHeroRows();
                    return;
                }

                const itemIndex = Number(button.dataset.itemIndex);

                fetch("{{ route('admin.site.home.remove-item') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        section: 'hero_items',
                        index: itemIndex
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to remove item');
                    }

                    return response.json();
                })
                .then(() => {
                    row.remove();
                    reindexHeroRows();
                })
                .catch(() => {
                    alert('Unable to remove item from database. Please try again.');
                });
            }

            if (event.target && event.target.id === 'add-whatwedo-feature') {
                const wrapper = document.getElementById('whatwedo-features-wrapper');
                if (!wrapper) return;

                const currentIndex = whatWeDoFeatureIndex;
                whatWeDoFeatureIndex++;

                const item = document.createElement('div');
                item.className = 'whatwedo-feature-item';
                item.style.cssText = 'padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;';
                item.innerHTML = `
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Feature ${currentIndex + 1} - Icon Class</label>
                                <input type="text" name="whatwedo_features[${currentIndex}][icon]" class="form-control" placeholder="e.g., flaticon-food-donation">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Feature ${currentIndex + 1} - Title</label>
                                <input type="text" name="whatwedo_features[${currentIndex}][title]" class="form-control" placeholder="e.g., Food For Hunger">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Feature ${currentIndex + 1} - Description</label>
                                <textarea name="whatwedo_features[${currentIndex}][desc]" rows="3" class="form-control no-resize" placeholder="Enter feature description"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="button" id="remove-whatwedo-feature-new-${currentIndex + 1}" data-item-index="new-${currentIndex + 1}" data-sync="0" class="btn btn-danger btn-round remove-whatwedo-feature">Remove Feature</button>
                        </div>
                    </div>
                `;
                wrapper.appendChild(item);
            }

            if (event.target && event.target.classList.contains('remove-whatwedo-feature')) {
                const button = event.target;
                const item = button.closest('.whatwedo-feature-item');
                if (!item) return;

                const reindexFeatureRows = () => {
                    let syncIndex = 0;
                    const items = document.querySelectorAll('#whatwedo-features-wrapper .whatwedo-feature-item');

                    items.forEach((featureItem, visualIndex) => {
                        const labels = featureItem.querySelectorAll('label');
                        if (labels[0]) labels[0].textContent = `Feature ${visualIndex + 1} - Icon Class`;
                        if (labels[1]) labels[1].textContent = `Feature ${visualIndex + 1} - Title`;
                        if (labels[2]) labels[2].textContent = `Feature ${visualIndex + 1} - Description`;

                        const removeBtn = featureItem.querySelector('.remove-whatwedo-feature');
                        if (!removeBtn) return;

                        if (removeBtn.dataset.sync === '1') {
                            removeBtn.dataset.itemIndex = syncIndex;
                            removeBtn.id = `remove-whatwedo-feature-${syncIndex}`;
                            syncIndex++;
                        } else {
                            removeBtn.id = `remove-whatwedo-feature-new-${visualIndex + 1}`;
                        }
                    });

                    whatWeDoFeatureIndex = items.length;
                };

                const featureItems = document.querySelectorAll('#whatwedo-features-wrapper .whatwedo-feature-item');
                if (featureItems.length <= 1) return;

                const isSynced = button.dataset.sync === '1';
                if (!isSynced) {
                    item.remove();
                    reindexFeatureRows();
                    return;
                }

                const itemIndex = Number(button.dataset.itemIndex);

                fetch("{{ route('admin.site.home.remove-item') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        section: 'whatwedo_features',
                        index: itemIndex
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to remove feature');
                    }

                    return response.json();
                })
                .then(() => {
                    item.remove();
                    reindexFeatureRows();
                })
                .catch(() => {
                    alert('Unable to remove feature from database. Please try again.');
                });
            }

            if (event.target && event.target.id === 'add-cause-item') {
                const wrapper = document.getElementById('causes-items-wrapper');
                if (!wrapper) return;

                const currentIndex = causeItemIndex;
                causeItemIndex++;

                const item = document.createElement('div');
                item.className = 'cause-item';
                item.style.cssText = 'padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;';
                item.innerHTML = `
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <h5><strong>Cause Item ${currentIndex + 1}</strong></h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="causes[${currentIndex}][title]" class="form-control" placeholder="e.g., Education For All Children">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" name="causes[${currentIndex}][category]" class="form-control" placeholder="e.g., Education">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="causes[${currentIndex}][desc]" rows="3" class="form-control no-resize" placeholder="Enter cause description"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="causes[${currentIndex}][image]" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended size: 600x400px</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Goal Amount ($)</label>
                                <input type="number" step="0.01" name="causes[${currentIndex}][goal]" class="form-control" placeholder="e.g., 25000">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Raised Amount ($)</label>
                                <input type="number" step="0.01" name="causes[${currentIndex}][raised]" class="form-control" placeholder="e.g., 18500">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Link URL</label>
                                <input type="text" name="causes[${currentIndex}][link]" class="form-control" placeholder="e.g., /causes/education">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="button" id="remove-cause-item-new-${currentIndex + 1}" data-item-index="new-${currentIndex + 1}" data-sync="0" class="btn btn-danger btn-round remove-cause-item">Remove Cause</button>
                        </div>
                    </div>
                `;
                wrapper.appendChild(item);
            }

            if (event.target && event.target.classList.contains('remove-cause-item')) {
                const button = event.target;
                const item = button.closest('.cause-item');
                if (!item) return;

                const reindexCauseRows = () => {
                    let syncIndex = 0;
                    const items = document.querySelectorAll('#causes-items-wrapper .cause-item');

                    items.forEach((causeItem, visualIndex) => {
                        const heading = causeItem.querySelector('h5 strong');
                        if (heading) {
                            heading.textContent = `Cause Item ${visualIndex + 1}`;
                        }

                        const removeBtn = causeItem.querySelector('.remove-cause-item');
                        if (!removeBtn) return;

                        if (removeBtn.dataset.sync === '1') {
                            removeBtn.dataset.itemIndex = syncIndex;
                            removeBtn.id = `remove-cause-item-${syncIndex}`;
                            syncIndex++;
                        } else {
                            removeBtn.id = `remove-cause-item-new-${visualIndex + 1}`;
                        }
                    });

                    causeItemIndex = items.length;
                };

                const causeItems = document.querySelectorAll('#causes-items-wrapper .cause-item');
                if (causeItems.length <= 1) return;

                const isSynced = button.dataset.sync === '1';
                if (!isSynced) {
                    item.remove();
                    reindexCauseRows();
                    return;
                }

                const itemIndex = Number(button.dataset.itemIndex);

                fetch("{{ route('admin.site.home.remove-item') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        section: 'causes_items',
                        index: itemIndex
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to remove cause');
                    }

                    return response.json();
                })
                .then(() => {
                    item.remove();
                    reindexCauseRows();
                })
                .catch(() => {
                    alert('Unable to remove cause from database. Please try again.');
                });
            }

            if (event.target && event.target.id === 'add-whychoose-point') {
                whyChoosePointIndex++;
                const wrapper = document.getElementById('whychoose-points-wrapper');
                if (!wrapper) return;

                const row = document.createElement('div');
                row.className = 'form-group whychoose-point-row';
                row.innerHTML = `
                    <label>Point ${whyChoosePointIndex}</label>
                    <div class="input-group">
                        <input type="text" name="whychoose_points[]" class="form-control" placeholder="Enter list item">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-danger remove-whychoose-point">Remove</button>
                        </span>
                    </div>
                `;
                wrapper.appendChild(row);
            }

            if (event.target && event.target.classList.contains('remove-whychoose-point')) {
                const rows = document.querySelectorAll('#whychoose-points-wrapper .whychoose-point-row');
                if (rows.length <= 1) return;

                const row = event.target.closest('.whychoose-point-row');
                if (row) {
                    row.remove();
                }
            }

            if (event.target && event.target.id === 'add-howitwork-step') {
                const wrapper = document.getElementById('howitwork-steps-wrapper');
                if (!wrapper) return;

                const currentIndex = howItWorkStepIndex;
                howItWorkStepIndex++;

                const item = document.createElement('div');
                item.className = 'howitwork-step-item';
                item.style.cssText = 'padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;';
                item.innerHTML = `
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <h5><strong>Step ${currentIndex + 1}</strong></h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Step Title</label>
                                <input type="text" name="howitwork_steps[${currentIndex}][title]" class="form-control" placeholder="e.g., Start Donation">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Step Image</label>
                                <input type="file" name="howitwork_steps[${currentIndex}][image]" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended size: 400x300px</small>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Step Description</label>
                                <textarea name="howitwork_steps[${currentIndex}][desc]" rows="3" class="form-control no-resize" placeholder="Enter step description"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Step Icon SVG (Code)</label>
                                <textarea name="howitwork_steps[${currentIndex}][icon_svg]" rows="4" class="form-control no-resize" placeholder="Paste SVG code, e.g. <svg ...>...</svg>"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-danger btn-round remove-howitwork-step">Remove Step</button>
                        </div>
                    </div>
                `;
                wrapper.appendChild(item);
            }

            if (event.target && event.target.classList.contains('remove-howitwork-step')) {
                const items = document.querySelectorAll('#howitwork-steps-wrapper .howitwork-step-item');
                if (items.length <= 1) return;

                const item = event.target.closest('.howitwork-step-item');
                if (item) {
                    item.remove();
                }
            }

            if (event.target && event.target.id === 'add-testimonial-item') {
                const wrapper = document.getElementById('testimonials-items-wrapper');
                if (!wrapper) return;

                const currentIndex = testimonialItemIndex;
                testimonialItemIndex++;

                const item = document.createElement('div');
                item.className = 'testimonial-item';
                item.style.cssText = 'padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;';
                item.innerHTML = `
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <h5><strong>Testimonial ${currentIndex + 1}</strong></h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="testimonials[${currentIndex}][name]" class="form-control" placeholder="e.g., John Doe">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Designation</label>
                                <input type="text" name="testimonials[${currentIndex}][designation]" class="form-control" placeholder="e.g., Volunteer">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Quote</label>
                                <textarea name="testimonials[${currentIndex}][quote]" rows="3" class="form-control no-resize" placeholder="Enter review text"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Profile Image</label>
                                <input type="file" name="testimonials[${currentIndex}][image]" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended size: 120x120px</small>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-danger btn-round remove-testimonial-item">Remove Testimonial</button>
                        </div>
                    </div>
                `;
                wrapper.appendChild(item);
            }

            if (event.target && event.target.classList.contains('remove-testimonial-item')) {
                const items = document.querySelectorAll('#testimonials-items-wrapper .testimonial-item');
                if (items.length <= 1) return;

                const item = event.target.closest('.testimonial-item');
                if (item) {
                    item.remove();
                }
            }

            if (event.target && event.target.id === 'add-gallery-item') {
                const wrapper = document.getElementById('gallery-items-wrapper');
                if (!wrapper) return;

                const currentIndex = galleryItemIndex;
                galleryItemIndex++;

                const item = document.createElement('div');
                item.className = 'gallery-item';
                item.style.cssText = 'padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;';
                item.innerHTML = `
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <h5><strong>Gallery Item ${currentIndex + 1}</strong></h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Image Title</label>
                                <input type="text" name="gallery[${currentIndex}][title]" class="form-control" placeholder="e.g., Medical Camp 2026">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" name="gallery[${currentIndex}][category]" class="form-control" placeholder="e.g., Camp">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="gallery[${currentIndex}][image]" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended size: 800x600px</small>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-danger btn-round remove-gallery-item">Remove Gallery Item</button>
                        </div>
                    </div>
                `;
                wrapper.appendChild(item);
            }

            if (event.target && event.target.classList.contains('remove-gallery-item')) {
                const items = document.querySelectorAll('#gallery-items-wrapper .gallery-item');
                if (items.length <= 1) return;

                const item = event.target.closest('.gallery-item');
                if (item) {
                    item.remove();
                }
            }

            if (event.target && event.target.id === 'add-lasthope-item') {
                const wrapper = document.getElementById('lasthope-items-wrapper');
                if (!wrapper) return;

                const currentIndex = lastHopeItemIndex;
                lastHopeItemIndex++;

                const item = document.createElement('div');
                item.className = 'lasthope-item';
                item.style.cssText = 'padding:12px; border:1px solid #e9ecef; border-radius:6px; margin-bottom:12px;';
                item.innerHTML = `
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <h5><strong>Hope Item ${currentIndex + 1}</strong></h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Hope Title</label>
                                <input type="text" name="lasthope_items[${currentIndex}][title]" class="form-control" placeholder="e.g., Medical Support for Families">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Hope Image</label>
                                <input type="file" name="lasthope_items[${currentIndex}][image]" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended size: 600x400px</small>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Hope Description</label>
                                <textarea name="lasthope_items[${currentIndex}][description]" rows="3" class="form-control no-resize" placeholder="Enter hope description"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-danger btn-round remove-lasthope-item">Remove Hope Item</button>
                        </div>
                    </div>
                `;
                wrapper.appendChild(item);
            }

            if (event.target && event.target.classList.contains('remove-lasthope-item')) {
                const items = document.querySelectorAll('#lasthope-items-wrapper .lasthope-item');
                if (items.length <= 1) return;

                const item = event.target.closest('.lasthope-item');
                if (item) {
                    item.remove();
                }
            }
        });
    })();
</script>
@endpush
