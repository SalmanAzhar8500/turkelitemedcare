<x-guest-layout>
    <div class="auth-card-inner">
        <div>
            <p class="auth-kicker">Admin login</p>
            <h2>Sign in</h2>
            <p class="auth-help">Use your account to open the dashboard and manage the site.</p>
        </div>

        @if (session('status'))
            <div class="auth-alert success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="auth-alert error">
                <ul class="auth-error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="email">Email</label>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <div class="auth-field">
                <label for="password">Password</label>
                <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password">
            </div>

            <div class="auth-row">
                <label class="auth-check" for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <button class="auth-button" type="submit">Log in</button>

            <div class="auth-footer auth-links">
                <a href="{{ route('home') }}">Return to public site</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Create an account</a>
                @endif
            </div>
        </form>
    </div>
</x-guest-layout>
