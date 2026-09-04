<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', website_setting('brand.name').' | Account')</title>
    <meta name="description" content="@yield('description', 'Secure account access for Turkelite Medcare.')">
    <meta name="theme-color" content="#0f5273">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    @stack('head')
</head>
<body class="auth-page">
    <div class="auth-shell">
        <aside class="auth-hero">
            <div>
                <a class="auth-hero-brand" href="{{ route('home') }}">
                    <img alt="{{ website_setting('brand.name') }}" src="{{ website_logo_url() }}">
                </a>
                <p class="auth-kicker">Secure access</p>
                <h1>Admin access, styled like the public site.</h1>
                <p>Sign in to manage requests, profile details and operational pages from a dashboard that carries the same Turkelite Medcare brand system.</p>
            </div>

            <div>
                <div class="auth-badge">International patient coordination</div>
                <ul class="auth-bullets">
                    <li>
                        <span>01</span>
                        <div>
                            <strong>Branded workflow</strong>
                            <span>Login, reset and dashboard pages use the same colors, type and spacing language as the public site.</span>
                        </div>
                    </li>
                    <li>
                        <span>02</span>
                        <div>
                            <strong>Focused admin area</strong>
                            <span>Keep the login surface clean while the authenticated area stays practical and easy to scan.</span>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="auth-panel">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
