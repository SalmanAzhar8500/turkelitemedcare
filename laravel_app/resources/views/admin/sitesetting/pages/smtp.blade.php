@extends('admin.layouts.app')
@section('title', 'SMTP Settings')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>SMTP</strong> Settings</h2>
                    </div>
                    <div class="body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('admin.site.smtp.store') }}" method="POST">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-4"><div class="form-group"><label>SMTP Host</label><input type="text" name="mail_host" class="form-control" value="{{ old('mail_host', $mailData['host'] ?? '') }}"></div></div>
                                <div class="col-md-2"><div class="form-group"><label>Port</label><input type="number" name="mail_port" class="form-control" value="{{ old('mail_port', $mailData['port'] ?? '') }}"></div></div>
                                <div class="col-md-3"><div class="form-group"><label>Encryption</label><select name="mail_encryption" class="form-control"><option value="" {{ old('mail_encryption', $mailData['encryption'] ?? '') === '' ? 'selected' : '' }}>None</option><option value="tls" {{ old('mail_encryption', $mailData['encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option><option value="ssl" {{ old('mail_encryption', $mailData['encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option></select></div></div>
                                <div class="col-md-3"><div class="form-group"><label>Admin Email</label><input type="email" name="mail_admin_email" class="form-control" value="{{ old('mail_admin_email', $mailData['admin_email'] ?? '') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>SMTP Username</label><input type="text" name="mail_username" class="form-control" value="{{ old('mail_username', $mailData['username'] ?? '') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>SMTP Password</label><input type="password" name="mail_password" class="form-control" value="{{ old('mail_password', $mailData['password'] ?? '') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>From Email</label><input type="email" name="mail_from_address" class="form-control" value="{{ old('mail_from_address', $mailData['from_address'] ?? '') }}"></div></div>
                                <div class="col-md-4"><div class="form-group"><label>From Name</label><input type="text" name="mail_from_name" class="form-control" value="{{ old('mail_from_name', $mailData['from_name'] ?? '') }}"></div></div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-round">Save SMTP Settings</button>
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
