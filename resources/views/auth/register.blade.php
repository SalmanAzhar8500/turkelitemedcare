<x-guest-layout>
    <div class="auth-card-inner">
        <div>
            <p class="auth-kicker">Account setup</p>
            <h2>Create account</h2>
            <p class="auth-help">Set up a secure account for the admin dashboard.</p>
        </div>

        @if ($errors->any())
            <div class="auth-alert error">
                <ul class="auth-error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="name">Name</label>
                <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            </div>

            <div class="auth-field">
                <label for="email">Email</label>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            </div>

            <div class="auth-field">
                <label for="password">Password</label>
                <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password">
            </div>

            <div class="auth-field">
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button class="auth-button" type="submit">Create account</button>

            <div class="auth-footer auth-links">
                <a href="{{ route('login') }}">Already have an account?</a>
                <a href="{{ route('home') }}">Return to public site</a>
            </div>
        </form>
    </div>
</x-guest-layout>
