<x-app-layout>
    <x-slot name="header">
        <div class="admin-toolbar">
            <div>
                <p class="auth-kicker">Inbox</p>
                <h1>Inquiries</h1>
                <p class="auth-help">Search, sort and open public contact, callback and treatment-plan requests.</p>
            </div>
            <a class="auth-button secondary" href="{{ route('admin.dashboard') }}">Back to dashboard</a>
        </div>
    </x-slot>

    <div class="admin-card admin-table-card data-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table data-table" id="inquiries-table">
                <thead>
                    <tr><th>Name</th><th>Type</th><th>Status</th><th>Received</th><th>Action</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <x-admin.data-table-assets />
    @push('scripts')
        <script>
            $(function () {
                $('#inquiries-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('admin.inquiries.data') }}',
                    order: [[3, 'desc']],
                    pageLength: 25,
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'type', name: 'type' },
                        { data: 'status', name: 'status', searchable: false },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: { search: 'Search inquiries:', emptyTable: 'No inquiries found.' }
                });
            });
        </script>
    @endpush
</x-app-layout>
