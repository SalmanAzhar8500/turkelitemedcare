<x-app-layout>
    <x-slot name="header">
        <div class="admin-toolbar">
            <div>
                <p class="auth-kicker">Overview</p>
                <h1>Good morning, {{ auth()->user()?->name ?? 'Admin' }}.</h1>
                <p class="auth-help">Keep the patient journey current, consistent and ready to publish.</p>
            </div>

            <div class="admin-toolbar">
                <a class="auth-button secondary" href="{{ route('home') }}">Open public site</a>
            <a class="auth-button secondary" href="{{ route('admin.pages.index') }}">Manage pages</a>
                <a class="auth-button secondary" href="{{ route('admin.inquiries.index') }}">View inquiries</a>
            </div>
        </div>
    </x-slot>

    <div class="admin-grid">
        <section class="admin-card admin-stat admin-stat-pages">
            <span class="admin-stat-label">Site pages</span>
            <b>{{ $pageCount ?? 0 }}</b>
            <span>Database-backed pages ready to refine.</span>
        </section>

        <section class="admin-card admin-stat admin-stat-inquiries">
            <span class="admin-stat-label">Form submissions</span>
            <b>{{ $inquiryCount ?? 0 }}</b>
            <span>Contact and callback requests captured from the frontend.</span>
        </section>

        <section class="admin-card admin-stat admin-stat-specialties">
            <span class="admin-stat-label">Active specialties</span>
            <b>{{ count(site_specialties()) }}</b>
            <span>Public treatment categories currently published.</span>
        </section>

        <section class="admin-card span-6 admin-priority-card">
            <p class="admin-overline">Next action</p><h3>Publish with confidence</h3>
            <p class="admin-meta">Review your core pages, then manage treatments and procedures in order.</p>
            <div class="admin-toolbar">
                <a class="auth-button" href="{{ route('admin.pages.index') }}">Edit pages</a>
                <a class="auth-button secondary" href="{{ route('admin.inquiries.index') }}">Open inbox</a>
                <a class="auth-button secondary" href="{{ route('contact') }}">Preview contact form</a>
            </div>
        </section>

        <section class="admin-card span-6 admin-inbox-card">
            <div class="admin-section-heading"><div><p class="admin-overline">Patient inbox</p><h3>Latest inquiries</h3></div><a href="{{ route('admin.inquiries.index') }}">View all</a></div>
            <ul class="admin-list">
                @forelse(($latestInquiries ?? []) as $inquiry)
                    <li>
                        <a href="{{ route('admin.inquiries.show', $inquiry) }}">{{ $inquiry->name ?? 'Unnamed inquiry' }}</a>
                        <span> - {{ $inquiry->type }}</span>
                    </li>
                @empty
                    <li>No submissions yet.</li>
                @endforelse
            </ul>
        </section>

        <section class="admin-card span-12 admin-pages-card">
            <div class="admin-section-heading"><div><p class="admin-overline">Website content</p><h3>Managed pages</h3></div><a href="{{ route('admin.pages.index') }}">Manage pages</a></div>
            <div class="admin-toolbar">
                @foreach(($pages ?? []) as $page)
                    <a class="auth-button secondary" href="{{ route('admin.pages.edit', $page) }}">{{ $page->title }}</a>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
