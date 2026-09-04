@once
    @push('head')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).on('submit', 'form[data-confirm-delete]', function (event) {
                if (! window.confirm('Delete this record permanently? This action cannot be undone.')) {
                    event.preventDefault();
                }
            });
        </script>
    @endpush
@endonce
