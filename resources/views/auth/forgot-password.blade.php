<x-guest-layout>
    <div class="auth-card-inner">
        <div>
            <p class="auth-kicker">Password help</p>
            <h2>Reset your password</h2>
            <p class="auth-help">Enter your account email and we will send a password reset link if the address exists in the system.</p>
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

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="email">Email</label>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <button class="auth-button" type="submit">Email password reset link</button>

            <div class="auth-footer auth-links">
                <a href="{{ route('login') }}">Back to login</a>
                <a href="{{ route('home') }}">Return to public site</a>
            </div>
        </form>
    </div>
</x-guest-layout>
