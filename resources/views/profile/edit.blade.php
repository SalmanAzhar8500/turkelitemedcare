<x-app-layout>
    <x-slot name="header">
        <div class="admin-toolbar">
            <div>
                <p class="auth-kicker">Profile</p>
                <h1>Account settings</h1>
                <p class="auth-help">Keep your account information, password and deletion controls in one place.</p>
            </div>
            <a class="auth-button secondary" href="{{ route('admin.dashboard') }}">Back to dashboard</a>
        </div>
    </x-slot>

    <div class="admin-grid">
        <section class="admin-card span-6">
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="admin-card span-6">
            @include('profile.partials.update-password-form')
        </section>

        <section class="admin-card span-12">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
</x-app-layout>
