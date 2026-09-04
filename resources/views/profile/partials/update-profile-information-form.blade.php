<section class="space-y-6">
    <header>
        <p class="auth-kicker">Profile information</p>
        <h2>Identity and email</h2>
        <p>Keep your name and email address current so the dashboard and site messages stay in sync.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="auth-form">
        @csrf
        @method('patch')

        @if (session('status') === 'profile-updated')
            <div class="auth-alert success">
                {{ __('Your profile information has been updated.') }}
            </div>
        @endif

        <div class="auth-field">
            <label for="name">Name</label>
            <input
                id="name"
                name="name"
                type="text"
                class="auth-input"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            >
            @error('name')
                <p class="auth-help" style="color:#9a1c1c;">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-field">
            <label for="email">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                class="auth-input"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            >
            @error('email')
                <p class="auth-help" style="color:#9a1c1c;">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="auth-alert" style="background:#fff7e6;border-color:#f0d59b;color:#8a5a00;">
                    <p class="auth-help" style="margin:0;color:inherit;">
                        {{ __('Your email address is unverified.') }}
                    </p>
                    <div class="auth-row" style="margin-top:12px;">
                        <button form="send-verification" type="submit" class="auth-button secondary">
                            {{ __('Resend verification email') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <span class="auth-help" style="color:#0e5a39;">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="auth-row">
            <button type="submit" class="auth-button">{{ __('Save changes') }}</button>
        </div>
    </form>
</section>
