<x-app-layout>
    <x-slot name="header">
        <div class="admin-toolbar">
            <div>
                <p class="auth-kicker">Inquiry</p>
                <h1>{{ $inquiry->name ?? 'Unnamed inquiry' }}</h1>
                <p class="auth-help">{{ $inquiry->type }} request received on {{ $inquiry->created_at?->format('Y-m-d H:i') }}</p>
            </div>
            <a class="auth-button secondary" href="{{ route('admin.inquiries.index') }}">Back to inbox</a>
        </div>
    </x-slot>

    <div class="admin-grid">
        <section class="admin-card span-6">
            <h3>Details</h3>
            <p class="admin-meta"><strong>Email:</strong> {{ $inquiry->email }}</p>
            <p class="admin-meta"><strong>Phone:</strong> {{ $inquiry->phone ?: 'N/A' }}</p>
            <p class="admin-meta"><strong>Subject:</strong> {{ $inquiry->subject ?: 'N/A' }}</p>
            <p class="admin-meta"><strong>Language:</strong> {{ $inquiry->preferred_language ?: 'N/A' }}</p>
            <p class="admin-meta"><strong>Preferred time:</strong> {{ $inquiry->preferred_time ?: 'N/A' }}</p>
            <p class="admin-meta"><strong>Page:</strong> {{ $inquiry->page_slug ?: 'N/A' }}</p>
        </section>

        <section class="admin-card span-6">
            <h3>Update status</h3>
            @if(session('status'))
                <div class="auth-alert success">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('admin.inquiries.update', $inquiry) }}" class="auth-form">
                @csrf
                @method('PATCH')
                <div class="auth-field">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="auth-select" required>
                        @foreach(['new' => 'New', 'reviewing' => 'Reviewing', 'contacted' => 'Contacted', 'closed' => 'Closed'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $inquiry->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="auth-button" type="submit">Save status</button>
            </form>
        </section>

        <section class="admin-card span-12">
            <h3>Message</h3>
            <p class="admin-meta">{{ $inquiry->message }}</p>
            @if(! empty($inquiry->metadata))
                <pre class="admin-meta" style="white-space:pre-wrap">{{ json_encode($inquiry->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            @endif
        </section>
    </div>
</x-app-layout>
