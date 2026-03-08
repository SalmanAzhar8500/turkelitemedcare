@extends('admin.layouts.app')
@section('title', 'Header & Footer Settings')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>Header & Footer</strong> Settings</h2>
                    </div>
                    <div class="body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @php
                            $headerFooterData = is_array($headerFooterData ?? null) ? $headerFooterData : [];
                            $faviconPreview = filled($headerFooterData['favicon'] ?? null)
                                ? asset('storage/' . ltrim((string) $headerFooterData['favicon'], '/'))
                                : asset('favicon.ico');
                            $headerLogoPreview = filled($headerFooterData['header_logo'] ?? null)
                                ? asset('storage/' . ltrim((string) $headerFooterData['header_logo'], '/'))
                                : null;
                            $footerLogoPreview = filled($headerFooterData['footer_logo'] ?? null)
                                ? asset('storage/' . ltrim((string) $headerFooterData['footer_logo'], '/'))
                                : null;
                        @endphp

                        <form action="{{ route('admin.site.header-footer.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-12 mt-2"><h5>Website Identity</h5></div>
                                <div class="col-md-6"><div class="form-group"><label>Website Name</label><input type="text" name="website_name" class="form-control" value="{{ old('website_name', $headerFooterData['website_name'] ?? config('app.name')) }}"></div></div>
                                <div class="col-md-6"><div class="form-group"><label>Website Tagline</label><input type="text" name="website_tagline" class="form-control" value="{{ old('website_tagline', $headerFooterData['website_tagline'] ?? 'Trusted care for every patient.') }}"></div></div>
                                <div class="col-md-6"><div class="form-group"><label>Favicon</label><input type="file" name="favicon" class="form-control" accept=".ico,.png,.svg"><div class="mt-2"><img src="{{ $faviconPreview }}" alt="Favicon" style="max-height:40px; width:auto;"></div></div></div>

                                <div class="col-md-12 mt-2"><h5>Header</h5></div>
                                <div class="col-md-4"><div class="form-group"><label>Header Logo</label><input type="file" name="header_logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">@if($headerLogoPreview)<div class="mt-2"><img src="{{ $headerLogoPreview }}" alt="Header Logo" style="max-height:60px; width:auto;"></div>@endif</div></div>
                                <div class="col-md-4"><div class="form-group"><label>Help Text</label><input type="text" name="header_help_text" class="form-control" value="{{ old('header_help_text', $headerFooterData['header_help_text'] ?? 'need help !') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>Phone</label><input type="text" name="header_phone" class="form-control" value="{{ old('header_phone', $headerFooterData['header_phone'] ?? '(+01) 789 987 645') }}"></div></div>

                                <div class="col-md-12 mt-2"><h5>Footer</h5></div>
                                <div class="col-md-4"><div class="form-group"><label>Footer Logo</label><input type="file" name="footer_logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">@if($footerLogoPreview)<div class="mt-2"><img src="{{ $footerLogoPreview }}" alt="Footer Logo" style="max-height:60px; width:auto;"></div>@endif</div></div>
                                <div class="col-md-8"><div class="form-group"><label>Footer About Text</label><input type="text" name="footer_about_text" class="form-control" value="{{ old('footer_about_text', $headerFooterData['footer_about_text'] ?? 'Committed to compassionate care and better outcomes.') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>Phone Label</label><input type="text" name="footer_phone_label" class="form-control" value="{{ old('footer_phone_label', $headerFooterData['footer_phone_label'] ?? 'Toll free customer care') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>Phone</label><input type="text" name="footer_phone" class="form-control" value="{{ old('footer_phone', $headerFooterData['footer_phone'] ?? '+123 456 789') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>Support Label</label><input type="text" name="footer_support_label" class="form-control" value="{{ old('footer_support_label', $headerFooterData['footer_support_label'] ?? 'Need live support!') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>Support Email</label><input type="email" name="footer_email" class="form-control" value="{{ old('footer_email', $headerFooterData['footer_email'] ?? 'info@domainname.com') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>Copyright Text</label><input type="text" name="footer_copyright" class="form-control" value="{{ old('footer_copyright', $headerFooterData['footer_copyright'] ?? 'Copyright © 2025 All Rights Reserved.') }}"></div></div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-round">Save Header & Footer</button>
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
