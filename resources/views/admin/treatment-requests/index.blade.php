<x-app-layout>
    <x-slot name="header">
        <div class="admin-toolbar">
            <div>
                <p class="auth-kicker">Patient intake</p>
                <h1>Treatment requests</h1>
                <p class="auth-help">Review treatment-plan submissions separately from general contact enquiries.</p>
            </div>
            <a class="auth-button secondary" href="{{ route('admin.dashboard') }}">Back to dashboard</a>
        </div>
    </x-slot>

    @if(session('status'))<div class="auth-alert success">{{ session('status') }}</div>@endif

    <div class="admin-card admin-table-card data-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table data-table" id="treatment-requests-table">
                <thead>
                    <tr><th>Patient</th><th>Treatment</th><th>Country</th><th>Status</th><th>Received</th><th>Action</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <x-admin.data-table-assets />
    @push('scripts')
        <script>
            $(function () {
                $('#treatment-requests-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('admin.treatment-requests.data') }}',
                    order: [[4, 'desc']],
                    pageLength: 25,
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'specialty', orderable: false, searchable: false },
                        { data: 'country', orderable: false, searchable: false },
                        { data: 'status', name: 'status', searchable: false },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: { search: 'Search treatment requests:', emptyTable: 'No treatment requests found.' }
                });
            });
        </script>
    @endpush
</x-app-layout>
