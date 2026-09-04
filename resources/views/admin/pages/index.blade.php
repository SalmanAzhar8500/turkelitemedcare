<x-app-layout>
    <x-slot name="header">
        <div class="admin-toolbar">
            <div>
                <p class="auth-kicker">Content</p>
                <h1>Pages</h1>
                <p class="auth-help">Choose a public page, then edit the same named sections visitors see on the website. The Home page keeps the full front-page layout together in one editor.</p>
            </div>
            <a class="auth-button secondary" href="{{ route('admin.dashboard') }}">Back to dashboard</a>
        </div>
    </x-slot>

    <div class="admin-card admin-table-card data-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table data-table" id="pages-table">
                <thead>
                    <tr><th>Page</th><th>Public URL</th><th>Editable sections</th><th>Status</th><th>Action</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <x-admin.data-table-assets />
    @push('scripts')
        <script>
            $(function () {
                $('#pages-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('admin.pages.data') }}',
                    order: [[0, 'asc']],
                    pageLength: 25,
                    columns: [
                        { data: 'title', name: 'title' },
                        { data: 'public_url', name: 'slug', orderable: false },
                        { data: 'content_sections', name: 'content', orderable: false, searchable: false },
                        { data: 'is_active', name: 'is_active', searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: { search: 'Search pages:', emptyTable: 'No pages found.' }
                });
            });
        </script>
    @endpush
</x-app-layout>
