@extends('admin.layouts.app')
@section('title', 'About Page Settings')
@section('content')

	@php
		$aboutPage = $homeSetting->about_page_data ?? [];
	@endphp

	<section class="content">
		<div class="block-header">
			<div class="row">
				<div class="col-lg-7 col-md-5 col-sm-12">
					<h2>About Page Content Management
						<small class="text-muted">Manage frontend about-us page content and media</small>
					</h2>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card">
						<div class="header">
							<h2><strong>About</strong> Page Settings</h2>
						</div>
						<div class="body">
							<form action="{{ route('admin.site.about.store') }}" method="POST" enctype="multipart/form-data">
								@csrf

								<h5><strong>Page Header</strong></h5>
								<div class="row clearfix">
									<div class="col-sm-4">
										<div class="form-group">
											<label>Main Title</label>
											<input type="text" name="page_header_title" class="form-control" value="{{ old('page_header_title', $aboutPage['page_header_title'] ?? 'About us') }}">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label>Highlighted Word</label>
											<input type="text" name="page_header_highlight" class="form-control" value="{{ old('page_header_highlight', $aboutPage['page_header_highlight'] ?? 'About') }}">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label>Current Breadcrumb</label>
											<input type="text" name="breadcrumb_current" class="form-control" value="{{ old('breadcrumb_current', $aboutPage['breadcrumb_current'] ?? 'about us') }}">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label>Breadcrumb Home Text</label>
											<input type="text" name="breadcrumb_home_text" class="form-control" value="{{ old('breadcrumb_home_text', $aboutPage['breadcrumb_home_text'] ?? 'home') }}">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label>Breadcrumb Home Link</label>
											<input type="text" name="breadcrumb_home_link" class="form-control" value="{{ old('breadcrumb_home_link', $aboutPage['breadcrumb_home_link'] ?? route('home')) }}">
										</div>
									</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label>Page Header Background Image</label>
												<input type="file" name="page_header_image" class="form-control" accept="image/*">
											</div>
										</div>
								</div>

								<hr>
								<h5><strong>About Us Section</strong></h5>
								<div class="row clearfix">
									<div class="col-sm-4"><div class="form-group"><label>Subtitle</label><input type="text" name="about_subtitle" class="form-control" value="{{ old('about_subtitle', $aboutPage['about_subtitle'] ?? 'about us') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>Title</label><input type="text" name="about_title" class="form-control" value="{{ old('about_title', $aboutPage['about_title'] ?? 'United in compassion, changing lives') }}"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Description</label><textarea name="about_description" rows="3" class="form-control no-resize">{{ old('about_description', $aboutPage['about_description'] ?? 'Driven by compassion and a shared vision, we work hand-in-hand with communities to create meaningful change.') }}</textarea></div></div>

									<div class="col-sm-6"><div class="form-group"><label>About Image 1</label><input type="file" name="about_image1" class="form-control" accept="image/*"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>About Image 2</label><input type="file" name="about_image2" class="form-control" accept="image/*"></div></div>

									<div class="col-sm-3"><div class="form-group"><label>Funded Amount</label><input type="text" name="about_funded_amount" class="form-control" value="{{ old('about_funded_amount', $aboutPage['about_funded_amount'] ?? '75') }}"></div></div>
									<div class="col-sm-9"><div class="form-group"><label>Funded Label</label><input type="text" name="about_funded_label" class="form-control" value="{{ old('about_funded_label', $aboutPage['about_funded_label'] ?? "We've funded k Dollars") }}"></div></div>

									<div class="col-sm-6"><div class="form-group"><label>Support Title</label><input type="text" name="about_support_title" class="form-control" value="{{ old('about_support_title', $aboutPage['about_support_title'] ?? 'Healthcare Support') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>Donate Button Text</label><input type="text" name="about_donate_button_text" class="form-control" value="{{ old('about_donate_button_text', $aboutPage['about_donate_button_text'] ?? 'donate now') }}"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Support Description</label><textarea name="about_support_description" rows="2" class="form-control no-resize">{{ old('about_support_description', $aboutPage['about_support_description'] ?? 'Providing essential healthcare services and resources to communities.') }}</textarea></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Donate Button Link</label><input type="text" name="about_donate_button_link" class="form-control" value="{{ old('about_donate_button_link', $aboutPage['about_donate_button_link'] ?? url('/contact-us')) }}"></div></div>

									<div class="col-sm-4"><div class="form-group"><label>Helped Fund Count</label><input type="text" name="about_helped_fund_count" class="form-control" value="{{ old('about_helped_fund_count', $aboutPage['about_helped_fund_count'] ?? '75958') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Helped Fund Title</label><input type="text" name="about_helped_fund_title" class="form-control" value="{{ old('about_helped_fund_title', $aboutPage['about_helped_fund_title'] ?? 'helped fund') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Helped Fund Image</label><input type="file" name="about_helped_fund_image" class="form-control" accept="image/*"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Helped Fund Description</label><textarea name="about_helped_fund_description" rows="2" class="form-control no-resize">{{ old('about_helped_fund_description', $aboutPage['about_helped_fund_description'] ?? 'Supporting growth through community- funding.') }}</textarea></div></div>
								</div>

								<hr>
								<h5><strong>Our Approach Section</strong></h5>
								<div class="row clearfix">
									<div class="col-sm-4"><div class="form-group"><label>Subtitle</label><input type="text" name="approach_subtitle" class="form-control" value="{{ old('approach_subtitle', $aboutPage['approach_subtitle'] ?? 'our approach') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>Title</label><input type="text" name="approach_title" class="form-control" value="{{ old('approach_title', $aboutPage['approach_title'] ?? 'Compassionate solutions for lasting impact') }}"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Description</label><textarea name="approach_description" rows="3" class="form-control no-resize">{{ old('approach_description', $aboutPage['approach_description'] ?? 'Our approach focuses on creating sustainable change by addressing root causes, empowering communities, and delivering compassionate solutions.') }}</textarea></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Button Text</label><input type="text" name="approach_button_text" class="form-control" value="{{ old('approach_button_text', $aboutPage['approach_button_text'] ?? 'contact now') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Button Link</label><input type="text" name="approach_button_link" class="form-control" value="{{ old('approach_button_link', $aboutPage['approach_button_link'] ?? url('/contact-us')) }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Approach Image</label><input type="file" name="approach_image" class="form-control" accept="image/*"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Mission Title</label><input type="text" name="approach_mission_title" class="form-control" value="{{ old('approach_mission_title', $aboutPage['approach_mission_title'] ?? 'our mission') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>Mission Description</label><input type="text" name="approach_mission_description" class="form-control" value="{{ old('approach_mission_description', $aboutPage['approach_mission_description'] ?? 'We strive to create positive change, empower.') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Vision Title</label><input type="text" name="approach_vision_title" class="form-control" value="{{ old('approach_vision_title', $aboutPage['approach_vision_title'] ?? 'our vision') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>Vision Description</label><input type="text" name="approach_vision_description" class="form-control" value="{{ old('approach_vision_description', $aboutPage['approach_vision_description'] ?? 'We strive to create positive change, empower.') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Value Title</label><input type="text" name="approach_value_title" class="form-control" value="{{ old('approach_value_title', $aboutPage['approach_value_title'] ?? 'our value') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>Value Description</label><input type="text" name="approach_value_description" class="form-control" value="{{ old('approach_value_description', $aboutPage['approach_value_description'] ?? 'We strive to create positive change, empower.') }}"></div></div>
								</div>

								<hr>
								<h5><strong>Why Choose Us & How We Help</strong></h5>
								<div class="row clearfix">
									<div class="col-sm-4"><div class="form-group"><label>Why Subtitle</label><input type="text" name="why_subtitle" class="form-control" value="{{ old('why_subtitle', $aboutPage['why_subtitle'] ?? 'why choose us') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>Why Title</label><input type="text" name="why_title" class="form-control" value="{{ old('why_title', $aboutPage['why_title'] ?? 'Why we stand out together') }}"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Why Description</label><textarea name="why_description" rows="2" class="form-control no-resize">{{ old('why_description', $aboutPage['why_description'] ?? 'Our dedication, transparency, and community-driven approach set us apart. partnering with us,programs that create meaningful change.') }}</textarea></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Why Points (one per line)</label><textarea name="why_points" rows="4" class="form-control no-resize">{{ old('why_points', implode("\n", $aboutPage['why_points'] ?? ['community-centered approach','transparency and accountability','empowerment through partner','volunteer and donor engagement'])) }}</textarea></div></div>
									<div class="col-sm-2"><div class="form-group"><label>Counter 1</label><input type="text" name="why_counter1_number" class="form-control" value="{{ old('why_counter1_number', $aboutPage['why_counter1_number'] ?? '25') }}"></div></div>
									<div class="col-sm-2"><div class="form-group"><label>+ Label 1</label><input type="text" name="why_counter1_label" class="form-control" value="{{ old('why_counter1_label', $aboutPage['why_counter1_label'] ?? 'Years of experience') }}"></div></div>
									<div class="col-sm-2"><div class="form-group"><label>Counter 2</label><input type="text" name="why_counter2_number" class="form-control" value="{{ old('why_counter2_number', $aboutPage['why_counter2_number'] ?? '230') }}"></div></div>
									<div class="col-sm-2"><div class="form-group"><label>+ Label 2</label><input type="text" name="why_counter2_label" class="form-control" value="{{ old('why_counter2_label', $aboutPage['why_counter2_label'] ?? 'Thousands volunteers') }}"></div></div>
									<div class="col-sm-2"><div class="form-group"><label>Counter 3</label><input type="text" name="why_counter3_number" class="form-control" value="{{ old('why_counter3_number', $aboutPage['why_counter3_number'] ?? '400') }}"></div></div>
									<div class="col-sm-2"><div class="form-group"><label>+ Label 3</label><input type="text" name="why_counter3_label" class="form-control" value="{{ old('why_counter3_label', $aboutPage['why_counter3_label'] ?? 'Word wide office') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>Why Image 1</label><input type="file" name="why_image1" class="form-control" accept="image/*"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>Why Image 2</label><input type="file" name="why_image2" class="form-control" accept="image/*"></div></div>

									<div class="col-sm-4"><div class="form-group"><label>How Subtitle</label><input type="text" name="how_subtitle" class="form-control" value="{{ old('how_subtitle', $aboutPage['how_subtitle'] ?? 'how we help') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>How Title</label><input type="text" name="how_title" class="form-control" value="{{ old('how_title', $aboutPage['how_title'] ?? 'Bringing hope to every community') }}"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>How Description</label><textarea name="how_description" rows="2" class="form-control no-resize">{{ old('how_description', $aboutPage['how_description'] ?? 'We work tirelessly to uplift communities by providing resources, support, and sustainable solutions, fostering hope and creating brighter futures.') }}</textarea></div></div>
									<div class="col-sm-12"><div class="form-group"><label>How Bullet Points (one per line)</label><textarea name="how_points" rows="4" class="form-control no-resize">{{ old('how_points', implode("\n", $aboutPage['how_points'] ?? ['Community Development Programs','Women and Youth Empowerment','Advocacy and Awareness Campaigns'])) }}</textarea></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Button Text</label><input type="text" name="how_button_text" class="form-control" value="{{ old('how_button_text', $aboutPage['how_button_text'] ?? 'contact now') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Button Link</label><input type="text" name="how_button_link" class="form-control" value="{{ old('how_button_link', $aboutPage['how_button_link'] ?? url('/contact-us')) }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Item 1 Title</label><input type="text" name="how_item1_title" class="form-control" value="{{ old('how_item1_title', $aboutPage['how_item1_title'] ?? 'healthcare access') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Item 1 Description</label><input type="text" name="how_item1_description" class="form-control" value="{{ old('how_item1_description', $aboutPage['how_item1_description'] ?? 'Providing medical care, health education, and wellness resources.') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Item 2 Title</label><input type="text" name="how_item2_title" class="form-control" value="{{ old('how_item2_title', $aboutPage['how_item2_title'] ?? 'hunger relief') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Item 2 Description</label><input type="text" name="how_item2_description" class="form-control" value="{{ old('how_item2_description', $aboutPage['how_item2_description'] ?? 'Providing medical care, health education, and wellness resources.') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Item 3 Title</label><input type="text" name="how_item3_title" class="form-control" value="{{ old('how_item3_title', $aboutPage['how_item3_title'] ?? 'educational support') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Item 3 Description</label><input type="text" name="how_item3_description" class="form-control" value="{{ old('how_item3_description', $aboutPage['how_item3_description'] ?? 'Providing medical care, health education, and wellness resources.') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Item 4 Title</label><input type="text" name="how_item4_title" class="form-control" value="{{ old('how_item4_title', $aboutPage['how_item4_title'] ?? 'awareness campaigns') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>How Item 4 Description</label><input type="text" name="how_item4_description" class="form-control" value="{{ old('how_item4_description', $aboutPage['how_item4_description'] ?? 'Providing medical care, health education, and wellness resources.') }}"></div></div>
								</div>

								<hr>
								<h5><strong>Features, Facts, Testimonials, FAQ</strong></h5>
								<div class="row clearfix">
									<div class="col-sm-4"><div class="form-group"><label>Features Subtitle</label><input type="text" name="features_subtitle" class="form-control" value="{{ old('features_subtitle', $aboutPage['features_subtitle'] ?? 'our feature') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>Features Title</label><input type="text" name="features_title" class="form-control" value="{{ old('features_title', $aboutPage['features_title'] ?? 'Highlights our impactful work') }}"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Features Description</label><textarea name="features_description" rows="2" class="form-control no-resize">{{ old('features_description', $aboutPage['features_description'] ?? "Discover the positive change we've created through our programs, partnerships, and dedicated efforts. From healthcare and education to environmental sustainability.") }}</textarea></div></div>

									<div class="col-sm-2"><div class="form-group"><label>Feature 1 %</label><input type="text" name="features_item1_percent" class="form-control" value="{{ old('features_item1_percent', $aboutPage['features_item1_percent'] ?? '96') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Feature 1 Title</label><input type="text" name="features_item1_title" class="form-control" value="{{ old('features_item1_title', $aboutPage['features_item1_title'] ?? 'healthcare support') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Feature 1 Description</label><input type="text" name="features_item1_description" class="form-control" value="{{ old('features_item1_description', $aboutPage['features_item1_description'] ?? 'Provide essential healthcare services and resources to communities.') }}"></div></div>
									<div class="col-sm-2"><div class="form-group"><label>Feature 1 Image</label><input type="file" name="features_item1_image" class="form-control" accept="image/*"></div></div>

									<div class="col-sm-2"><div class="form-group"><label>Feature 2 %</label><input type="text" name="features_item2_percent" class="form-control" value="{{ old('features_item2_percent', $aboutPage['features_item2_percent'] ?? '94') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Feature 2 Title</label><input type="text" name="features_item2_title" class="form-control" value="{{ old('features_item2_title', $aboutPage['features_item2_title'] ?? 'education support') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Feature 2 Description</label><input type="text" name="features_item2_description" class="form-control" value="{{ old('features_item2_description', $aboutPage['features_item2_description'] ?? 'Provide essential healthcare services and resources to communities.') }}"></div></div>
									<div class="col-sm-2"><div class="form-group"><label>Feature 2 Image</label><input type="file" name="features_item2_image" class="form-control" accept="image/*"></div></div>

									<div class="col-sm-2"><div class="form-group"><label>Feature 3 %</label><input type="text" name="features_item3_percent" class="form-control" value="{{ old('features_item3_percent', $aboutPage['features_item3_percent'] ?? '95') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Feature 3 Title</label><input type="text" name="features_item3_title" class="form-control" value="{{ old('features_item3_title', $aboutPage['features_item3_title'] ?? 'food support') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Feature 3 Description</label><input type="text" name="features_item3_description" class="form-control" value="{{ old('features_item3_description', $aboutPage['features_item3_description'] ?? 'Provide essential healthcare services and resources to communities.') }}"></div></div>
									<div class="col-sm-2"><div class="form-group"><label>Feature 3 Image</label><input type="file" name="features_item3_image" class="form-control" accept="image/*"></div></div>

									<div class="col-sm-4"><div class="form-group"><label>Fact Subtitle</label><input type="text" name="fact_subtitle" class="form-control" value="{{ old('fact_subtitle', $aboutPage['fact_subtitle'] ?? 'some fact') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>Fact Title</label><input type="text" name="fact_title" class="form-control" value="{{ old('fact_title', $aboutPage['fact_title'] ?? 'Impactful numbers that inspire change') }}"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Fact Description</label><textarea name="fact_description" rows="2" class="form-control no-resize">{{ old('fact_description', $aboutPage['fact_description'] ?? "Discover the transformative impact of our initiatives through key figures that highlight the progress we've made together in empowering communities and changing lives.") }}</textarea></div></div>
									<div class="col-sm-3"><div class="form-group"><label>Fact Counter 1</label><input type="text" name="fact_counter1_number" class="form-control" value="{{ old('fact_counter1_number', $aboutPage['fact_counter1_number'] ?? '25') }}"></div></div>
									<div class="col-sm-3"><div class="form-group"><label>Fact Label 1</label><input type="text" name="fact_counter1_label" class="form-control" value="{{ old('fact_counter1_label', $aboutPage['fact_counter1_label'] ?? 'years of experience') }}"></div></div>
									<div class="col-sm-3"><div class="form-group"><label>Fact Counter 2</label><input type="text" name="fact_counter2_number" class="form-control" value="{{ old('fact_counter2_number', $aboutPage['fact_counter2_number'] ?? '95') }}"></div></div>
									<div class="col-sm-3"><div class="form-group"><label>Fact Label 2</label><input type="text" name="fact_counter2_label" class="form-control" value="{{ old('fact_counter2_label', $aboutPage['fact_counter2_label'] ?? 'food support') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>Fact Main Image</label><input type="file" name="fact_image" class="form-control" accept="image/*"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>Fact Body Image</label><input type="file" name="fact_body_image" class="form-control" accept="image/*"></div></div>

									<div class="col-sm-4"><div class="form-group"><label>Testimonials Subtitle</label><input type="text" name="testimonials_subtitle" class="form-control" value="{{ old('testimonials_subtitle', $aboutPage['testimonials_subtitle'] ?? 'testimonials') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>Testimonials Title</label><input type="text" name="testimonials_title" class="form-control" value="{{ old('testimonials_title', $aboutPage['testimonials_title'] ?? 'What people say about us') }}"></div></div>
									<div class="col-sm-6"><div class="form-group"><label>Testimonials Side Image</label><input type="file" name="testimonials_image" class="form-control" accept="image/*"></div></div>
									<div class="col-sm-3"><div class="form-group"><label>Review Count</label><input type="text" name="testimonials_review_count" class="form-control" value="{{ old('testimonials_review_count', $aboutPage['testimonials_review_count'] ?? '20') }}"></div></div>
									<div class="col-sm-3"><div class="form-group"><label>Review Label</label><input type="text" name="testimonials_review_label" class="form-control" value="{{ old('testimonials_review_label', $aboutPage['testimonials_review_label'] ?? 'customer review') }}"></div></div>

									<div class="col-sm-4"><div class="form-group"><label>Testimonial 1 Name</label><input type="text" name="testimonials_item1_name" class="form-control" value="{{ old('testimonials_item1_name', $aboutPage['testimonials_item1_name'] ?? 'eleanor pena') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Designation</label><input type="text" name="testimonials_item1_designation" class="form-control" value="{{ old('testimonials_item1_designation', $aboutPage['testimonials_item1_designation'] ?? 'volunteer coordinator') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Image</label><input type="file" name="testimonials_item1_image" class="form-control" accept="image/*"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Testimonial 1 Quote</label><textarea name="testimonials_item1_quote" rows="2" class="form-control no-resize">{{ old('testimonials_item1_quote', $aboutPage['testimonials_item1_quote'] ?? "Working with our team has been a truly inspiring experience.") }}</textarea></div></div>

									<div class="col-sm-4"><div class="form-group"><label>Testimonial 2 Name</label><input type="text" name="testimonials_item2_name" class="form-control" value="{{ old('testimonials_item2_name', $aboutPage['testimonials_item2_name'] ?? 'michael carter') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Designation</label><input type="text" name="testimonials_item2_designation" class="form-control" value="{{ old('testimonials_item2_designation', $aboutPage['testimonials_item2_designation'] ?? 'program manager') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Image</label><input type="file" name="testimonials_item2_image" class="form-control" accept="image/*"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Testimonial 2 Quote</label><textarea name="testimonials_item2_quote" rows="2" class="form-control no-resize">{{ old('testimonials_item2_quote', $aboutPage['testimonials_item2_quote'] ?? "Their dedication to uplifting communities and creating sustainable change is unmatched.") }}</textarea></div></div>

									<div class="col-sm-4"><div class="form-group"><label>Testimonial 3 Name</label><input type="text" name="testimonials_item3_name" class="form-control" value="{{ old('testimonials_item3_name', $aboutPage['testimonials_item3_name'] ?? 'sophi martinez') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Designation</label><input type="text" name="testimonials_item3_designation" class="form-control" value="{{ old('testimonials_item3_designation', $aboutPage['testimonials_item3_designation'] ?? 'communications director') }}"></div></div>
									<div class="col-sm-4"><div class="form-group"><label>Image</label><input type="file" name="testimonials_item3_image" class="form-control" accept="image/*"></div></div>
									<div class="col-sm-12"><div class="form-group"><label>Testimonial 3 Quote</label><textarea name="testimonials_item3_quote" rows="2" class="form-control no-resize">{{ old('testimonials_item3_quote', $aboutPage['testimonials_item3_quote'] ?? "Through their programs, I've seen lives transformed and hope restored.") }}</textarea></div></div>

									<div class="col-sm-4"><div class="form-group"><label>FAQ Subtitle</label><input type="text" name="faq_subtitle" class="form-control" value="{{ old('faq_subtitle', $aboutPage['faq_subtitle'] ?? 'faq') }}"></div></div>
									<div class="col-sm-8"><div class="form-group"><label>FAQ Title</label><input type="text" name="faq_title" class="form-control" value="{{ old('faq_title', $aboutPage['faq_title'] ?? 'Answers to important your questions') }}"></div></div>
									@for($faqNo = 1; $faqNo <= 5; $faqNo++)
										<div class="col-sm-6"><div class="form-group"><label>FAQ {{ $faqNo }} Question</label><input type="text" name="faq{{ $faqNo }}_question" class="form-control" value="{{ old('faq'.$faqNo.'_question', $aboutPage['faq'.$faqNo.'_question'] ?? '') }}"></div></div>
										<div class="col-sm-6"><div class="form-group"><label>FAQ {{ $faqNo }} Answer</label><input type="text" name="faq{{ $faqNo }}_answer" class="form-control" value="{{ old('faq'.$faqNo.'_answer', $aboutPage['faq'.$faqNo.'_answer'] ?? '') }}"></div></div>
									@endfor
								</div>

								<div class="row clearfix">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-primary btn-round">Save About Page</button>
										<button type="reset" class="btn btn-default btn-round btn-simple">Reset</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>



@endsection
