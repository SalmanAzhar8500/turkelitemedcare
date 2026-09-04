<section class="space-y-6">
    <header>
        <p class="auth-kicker">Danger zone</p>
        <h2>Delete account</h2>
        <p>Once deleted, your account and its data cannot be restored. Make sure you really want to continue.</p>
    </header>

    <div class="auth-alert error">
        {{ __('This action is permanent and will remove the account from the system.') }}
    </div>

    <form method="post" action="{{ route('profile.destroy') }}" class="auth-form">
        @csrf
        @method('delete')

        <div class="auth-field">
            <label for="password">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                class="auth-input"
                placeholder="{{ __('Password') }}"
                autocomplete="current-password"
                required
            >
            @error('password', 'userDeletion')
                <p class="auth-help" style="color:#9a1c1c;">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-modal-actions" style="justify-content:flex-start;">
            <button type="submit" class="auth-button danger">
                {{ __('Delete account') }}
            </button>
        </div>
    </form>
</section>
