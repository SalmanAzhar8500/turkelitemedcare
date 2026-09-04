<x-app-layout>
    <x-slot name="header">
        <div class="admin-toolbar">
            <div><p class="auth-kicker">Publishing</p><h1>Legal &amp; Privacy documents</h1><p class="auth-help">Create and manage each legal document separately. Draft documents stay hidden until you publish them.</p></div>
            <div class="auth-row"><a class="auth-button secondary" href="{{ route('admin.pages.index') }}">Page settings</a><a class="auth-button" href="{{ route('admin.legal.create') }}">Add document</a></div>
        </div>
    </x-slot>
    @if(session('status'))<div class="auth-alert success">{{ session('status') }}</div>@endif
    <div class="admin-card admin-table-card"><div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Document</th><th>Public URL</th><th>Status</th><th>Order</th><th>Action</th></tr></thead><tbody>
        @foreach($documents as $document)
            <tr><td><strong>{{ $document->title }}</strong><small>{{ $document->summary }}</small></td><td><a href="{{ route('legal.documents.show', $document) }}" target="_blank" rel="noreferrer">/legal/{{ $document->slug }}</a></td><td><span class="admin-status {{ $document->is_active ? 'published' : 'draft' }}">{{ $document->is_active ? 'Published' : 'Draft' }}</span></td><td>{{ $document->sort_order }}</td><td><div class="auth-row"><a class="auth-button secondary" href="{{ route('admin.legal.edit', $document) }}">Edit</a><form method="POST" action="{{ route('admin.legal.destroy', $document) }}" onsubmit="return confirm('Delete this legal document?');">@csrf @method('DELETE')<button class="auth-button danger" type="submit">Delete</button></form></div></td></tr>
        @endforeach
    </tbody></table></div></div>
</x-app-layout>
