<x-app-layout>
    <x-slot name="header">
        <div class="admin-toolbar">
            <div>
               
                <p class="auth-kicker">Treatment request</p>
                <h1>{{ $inquiry->name }}</h1>
                <p class="auth-help">Received {{ $inquiry->created_at?->format('d M Y, H:i') }} from the treatment-plan form.</p>
            </div>
            <a class="auth-button secondary" href="{{ route('admin.treatment-requests.index') }}">Back to requests</a>
        </div>
    </x-slot>

    @php($metadata = $inquiry->metadata ?? [])
    <div class="admin-grid">
        <section class="admin-card span-6">
            <p class="admin-overline">Patient details</p>
            <h3>Contact information</h3>
            <p class="admin-meta"><strong>Email:</strong> {{ $inquiry->email }}</p>
            <p class="admin-meta"><strong>Phone:</strong> {{ $inquiry->phone ?: 'Not provided' }}</p>
            <p class="admin-meta"><strong>Country:</strong> {{ $metadata['country'] ?? 'Not provided' }}</p>
            <p class="admin-meta"><strong>Language:</strong> {{ $inquiry->preferred_language ?: 'Not provided' }}</p>
            <p class="admin-meta"><strong>Preferred timing:</strong> {{ $inquiry->preferred_time ?: 'Not provided' }}</p>
        </section>

        <section class="admin-card span-6">
            <p class="admin-overline">Requested care</p>
            <h3>Treatment information</h3>
            <p class="admin-meta"><strong>Specialty:</strong> {{ $metadata['specialty'] ?? 'Not provided' }}</p>
            <p class="admin-meta"><strong>Procedure:</strong> {{ $metadata['procedure'] ?? 'Not specified' }}</p>
            <p class="admin-meta"><strong>Source page:</strong> {{ $inquiry->page_slug }}</p>
        </section>

        <section class="admin-card span-12">
            <p class="admin-overline">Patient message</p>
            <h3>Information shared with the team</h3>
            <p class="admin-meta" style="white-space: pre-wrap">{{ $inquiry->message }}</p>
        </section>

        <section class="admin-card span-12">
            <p class="admin-overline">Workflow</p>
            <h3>Update request status</h3>
            @if(session('status'))<div class="auth-alert success">{{ session('status') }}</div>@endif
            <form method="POST" action="{{ route('admin.treatment-requests.update', $inquiry) }}" class="auth-form">
                @csrf
                @method('PATCH')
                <label class="auth-field" for="status"><span>Status</span>
                    <select id="status" name="status" class="auth-select" required>
                        @foreach(['new' => 'New', 'reviewing' => 'Reviewing', 'contacted' => 'Contacted', 'closed' => 'Closed'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $inquiry->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="auth-button" type="submit">Save status</button>
            </form>
        </section>
    </div>
</x-app-layout>
