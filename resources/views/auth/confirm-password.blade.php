<x-guest-layout>
    <div class="auth-card-inner">
        <div>
            <p class="auth-kicker">Security check</p>
            <h2>Confirm password</h2>
            <p class="auth-help">This area needs a quick password confirmation before you continue.</p>
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

        <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="password">Password</label>
                <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password">
            </div>

            <button class="auth-button" type="submit">Confirm password</button>
        </form>
    </div>
</x-guest-layout>
