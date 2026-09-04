<x-app-layout>
    @push("head")<style>.catalog-hero-toolbar{justify-content:space-between;gap:24px}.catalog-label-link{display:grid;gap:2px;min-width:138px;padding:9px 12px;border:1px solid rgba(24,73,75,.14);border-radius:10px;background:rgba(255,255,252,.72);color:#174749;line-height:1.15}.catalog-label-link:hover{border-color:rgba(189,134,77,.52);background:#fffefa;text-decoration:none}.catalog-label-link span,.catalog-label-link small{color:#7b8883;font-size:.62rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.catalog-label-link strong{font-family:Georgia,"Times New Roman",serif;font-size:.98rem;font-weight:600}.catalog-hero-toolbar>.catalog-label-link{margin-left:auto}@media(max-width:640px){.catalog-hero-toolbar{align-items:flex-start}.catalog-label-link{margin-left:0!important}}</style>@endpush
    <x-slot name="header">
        <div class="admin-toolbar catalog-hero-toolbar">
            <div>
                <p class="auth-kicker">Content catalog</p>
                <h1>{{ $definition['plural'] }}</h1>
                <p class="auth-help">Search, sort and maintain the content shown on the public website.</p>
            </div>
            @if($type === "treatments")<a class="catalog-label-link" href="/admin/settings"><span>Public label</span><strong>{{ site_ui("treatments") }}</strong><small>Edit EN / DE</small></a>@endif
            <a class="auth-button" href="{{ route('admin.catalog.create', $type) }}">Add {{ $definition['singular'] }}</a>
        </div>
    </x-slot>

    @if(session('status'))
        <div class="auth-alert success">{{ session('status') }}</div>
    @endif

    <div class="admin-card admin-table-card data-table-card">
        <div class="admin-table-wrap">
            <table class="admin-table data-table" id="catalog-table">
                <thead>
                    <tr><th>Name</th><th>Parent / location</th><th>Status</th><th>Order</th><th>Action</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <x-admin.data-table-assets />
    @push('scripts')
        <script>
            $(function () {
                $('#catalog-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('admin.catalog.data', $type) }}',
                    order: [[3, 'asc']],
                    pageLength: 25,
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'parent', name: 'parent', orderable: false },
                        { data: 'is_active', name: 'is_active', searchable: false },
                        { data: 'sort_order', name: 'sort_order' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: { search: 'Search {{ $definition['plural'] }}:', emptyTable: 'No {{ strtolower($definition['plural']) }} found.' }
                });
            });
        </script>
    @endpush
</x-app-layout>
