@extends('admin.layouts.app')
@section('title', 'Contact Page Settings')

@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="header">
						<h2><strong>Contact</strong> Page Settings</h2>
					</div>
					<div class="body">
						@if(session('success'))
							<div class="alert alert-success">{{ session('success') }}</div>
						@endif

						<form action="{{ route('admin.site.contact.store') }}" method="POST">
							@csrf
							<div class="row clearfix">
								<div class="col-md-12 mt-2"><h5>Page Content</h5></div>
								<div class="col-md-6"><div class="form-group"><label>Page Title</label><input type="text" name="contact_page_title" class="form-control" value="{{ old('contact_page_title', $contactData['contact_page_title'] ?? 'Contact') }}"></div></div>
								<div class="col-md-6"><div class="form-group"><label>Page Heading</label><input type="text" name="contact_page_heading" class="form-control" value="{{ old('contact_page_heading', $contactData['contact_page_heading'] ?? 'Contact us') }}"></div></div>
								<div class="col-md-6"><div class="form-group"><label>Form Small Title</label><input type="text" name="contact_form_subtitle" class="form-control" value="{{ old('contact_form_subtitle', $contactData['contact_form_subtitle'] ?? 'contact us') }}"></div></div>
								<div class="col-md-6"><div class="form-group"><label>Form Main Title</label><input type="text" name="contact_form_title" class="form-control" value="{{ old('contact_form_title', $contactData['contact_form_title'] ?? 'Get in to touch') }}"></div></div>

								<div class="col-md-12 mt-2"><h5>Contact Details</h5></div>
								<div class="col-md-6"><div class="form-group"><label>Primary Phone</label><input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $contactData['contact_phone'] ?? '+123 456 789') }}"></div></div>
								<div class="col-md-6"><div class="form-group"><label>Secondary Phone</label><input type="text" name="contact_phone_alt" class="form-control" value="{{ old('contact_phone_alt', $contactData['contact_phone_alt'] ?? '+123 456 789') }}"></div></div>
								<div class="col-md-6"><div class="form-group"><label>Primary Email</label><input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $contactData['contact_email'] ?? 'example@mail.com') }}"></div></div>
								<div class="col-md-6"><div class="form-group"><label>Secondary Email</label><input type="email" name="contact_email_alt" class="form-control" value="{{ old('contact_email_alt', $contactData['contact_email_alt'] ?? 'domainname@gmail.com') }}"></div></div>
								<div class="col-md-12"><div class="form-group"><label>Address</label><textarea name="contact_address" class="form-control" rows="3">{{ old('contact_address', $contactData['contact_address'] ?? '12345 Unity Avenue Suite 100 Springfield, USA 54321') }}</textarea></div></div>

								<div class="col-md-12 mt-2"><h5>Map</h5></div>
								<div class="col-md-12"><div class="form-group"><label>Google Map Embed URL</label><input type="text" name="contact_map_iframe" class="form-control" value="{{ old('contact_map_iframe', $contactData['contact_map_iframe'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d96737.10562045308!2d-74.08535042841811!3d40.739265258395164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1703158537552!5m2!1sen!2sin') }}"></div></div>

								<div class="col-md-12 mt-2"><h5>Social Links</h5></div>
								<div class="col-md-4"><div class="form-group"><label>Facebook</label><input type="text" name="social_facebook" class="form-control" value="{{ old('social_facebook', $contactData['social_facebook'] ?? '') }}"></div></div>
								<div class="col-md-4"><div class="form-group"><label>Twitter / X</label><input type="text" name="social_twitter" class="form-control" value="{{ old('social_twitter', $contactData['social_twitter'] ?? '') }}"></div></div>
								<div class="col-md-4"><div class="form-group"><label>Instagram</label><input type="text" name="social_instagram" class="form-control" value="{{ old('social_instagram', $contactData['social_instagram'] ?? '') }}"></div></div>
								<div class="col-md-4"><div class="form-group"><label>LinkedIn</label><input type="text" name="social_linkedin" class="form-control" value="{{ old('social_linkedin', $contactData['social_linkedin'] ?? '') }}"></div></div>
								<div class="col-md-4"><div class="form-group"><label>YouTube</label><input type="text" name="social_youtube" class="form-control" value="{{ old('social_youtube', $contactData['social_youtube'] ?? '') }}"></div></div>
								<div class="col-md-4"><div class="form-group"><label>WhatsApp</label><input type="text" name="social_whatsapp" class="form-control" value="{{ old('social_whatsapp', $contactData['social_whatsapp'] ?? '') }}"></div></div>

								<div class="col-md-12">
									<button type="submit" class="btn btn-primary btn-round">Save Contact Page</button>
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
