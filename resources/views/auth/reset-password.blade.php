<x-guest-layout>
    <div class="auth-card-inner">
        <div>
            <p class="auth-kicker">Set a new password</p>
            <h2>Reset password</h2>
            <p class="auth-help">Choose a new password for your Turkelite Medcare account.</p>
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

        <form method="POST" action="{{ route('password.store') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="auth-field">
                <label for="email">Email</label>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email', request('email')) }}" required autofocus autocomplete="username">
            </div>

            <div class="auth-field">
                <label for="password">Password</label>
                <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password">
            </div>

            <div class="auth-field">
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button class="auth-button" type="submit">Reset password</button>
        </form>
    </div>
</x-guest-layout>
