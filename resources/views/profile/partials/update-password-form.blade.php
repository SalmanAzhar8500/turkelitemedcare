<section class="space-y-6">
    <header>
        <p class="auth-kicker">Security</p>
        <h2>Update password</h2>
        <p>Use a long, unique password to keep the dashboard account secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="auth-form">
        @csrf
        @method('put')

        @if (session('status') === 'password-updated')
            <div class="auth-alert success">
                {{ __('Your password has been updated.') }}
            </div>
        @endif

        <div class="auth-field">
            <label for="update_password_current_password">Current password</label>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="auth-input"
                autocomplete="current-password"
            >
            @error('current_password', 'updatePassword')
                <p class="auth-help" style="color:#9a1c1c;">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-field">
            <label for="update_password_password">New password</label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                class="auth-input"
                autocomplete="new-password"
            >
            @error('password', 'updatePassword')
                <p class="auth-help" style="color:#9a1c1c;">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-field">
            <label for="update_password_password_confirmation">Confirm password</label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="auth-input"
                autocomplete="new-password"
            >
            @error('password_confirmation', 'updatePassword')
                <p class="auth-help" style="color:#9a1c1c;">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-row">
            <button type="submit" class="auth-button">{{ __('Save password') }}</button>
        </div>
    </form>
</section>
