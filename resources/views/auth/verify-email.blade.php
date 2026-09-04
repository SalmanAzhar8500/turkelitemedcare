<x-guest-layout>
    <div class="auth-card-inner">
        <div>
            <p class="auth-kicker">Verify email</p>
            <h2>Check your inbox</h2>
            <p class="auth-help">A verification link was sent to your email address. Follow the link to finish enabling your account.</p>
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="auth-alert success">A new verification link has been sent to the email address you used during registration.</div>
        @endif

        <div class="auth-form">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="auth-button" type="submit">Resend verification email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="auth-button secondary" type="submit">Log out</button>
            </form>
        </div>
    </div>
</x-guest-layout>
