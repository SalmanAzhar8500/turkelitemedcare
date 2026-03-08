@extends('admin.layouts.app')
@section('title', 'Patient Guides')
@section('content')

    <section class="content home">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12">
                    <div class="card patient_list">
                        <div class="header">
                            <h2><strong>Main</strong> Patient Guide List</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="guidesTable" class="table table-striped m-b-0">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="dynamicGuideModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Patient Guide Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Hierarchy</h6>
                    <p id="guideBreadcrumbArea"></p>
                    <hr>
                    <table id="dynamicGuideChildTable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editGuideModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editGuideForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Patient Guide</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="editGuideId">
                        <div class="mb-3">
                            <label>Guide Name</label>
                            <input type="text" id="editGuideName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Parent Guide</label>
                            <input type="text" id="editGuideParentName" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Type</label>
                            <input type="text" id="editGuideType" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#guidesTable').DataTable({
                processing:true,
                serverSide:true,
                ajax:'{{ route("admin.patient-guides.data") }}',
                columns:[
                    {data:'DT_RowIndex'},
                    {data:'name'},
                    {data:'slug'},
                    {data:'action'}
                ]
            });
        });

        $(document).on('click','.viewGuideBtn',function(){
            let slug = $(this).data('slug');
            $('#dynamicGuideModal').modal('show');

            $.get('/admin/patient-guide/' + slug, function(data){
                $('#guideBreadcrumbArea').html(data.breadcrumb.join(' → '));

                if($.fn.DataTable.isDataTable('#dynamicGuideChildTable')){
                    $('#dynamicGuideChildTable').DataTable().destroy();
                }

                $('#dynamicGuideChildTable').DataTable({
                    processing:true,
                    serverSide:true,
                    ajax:'/admin/patient-guide/' + slug + '/children',
                    columns:[
                        {data:'DT_RowIndex'},
                        {data:'name'},
                        {data:'slug'},
                        {data:'action'}
                    ]
                });
            });
        });

        $(document).on('click', '.deleteGuideBtn', function(){
            let id = $(this).data('id');
            if(!id){
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        url: '/admin/patient-guide/' + id,
                        type: 'DELETE',
                        data: {_token: '{{ csrf_token() }}'},
                        success: function(response){
                            if(response.success){
                                Swal.fire('Deleted!', response.message, 'success');
                                if ($.fn.DataTable.isDataTable('#dynamicGuideChildTable')) {
                                    $('#dynamicGuideChildTable').DataTable().ajax.reload(null, false);
                                }
                                if ($.fn.DataTable.isDataTable('#guidesTable')) {
                                    $('#guidesTable').DataTable().ajax.reload(null, false);
                                }
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click','.editGuideBtn',function(){
            let id = $(this).data('id');

            $.get('/admin/patient-guide/edit/' + id, function(res){
                $('#editGuideId').val(res.id);
                $('#editGuideName').val(res.name);
                $('#editGuideParentName').val(res.parent_name ?? 'Main Guide');
                $('#editGuideType').val(res.type);
                $('#editGuideModal').modal('show');
            });
        });

        $('#editGuideForm').submit(function(e){
            e.preventDefault();
            let id = $('#editGuideId').val();
            let name = $('#editGuideName').val();

            $.ajax({
                url:'/admin/patient-guide/update/' + id,
                type:'POST',
                data:{
                    _token:'{{ csrf_token() }}',
                    name:name
                },
                success:function(res){
                    if(res.success){
                        $('#editGuideModal').modal('hide');
                        if ($.fn.DataTable.isDataTable('#dynamicGuideChildTable')) {
                            $('#dynamicGuideChildTable').DataTable().ajax.reload(null, false);
                        }
                        if ($.fn.DataTable.isDataTable('#guidesTable')) {
                            $('#guidesTable').DataTable().ajax.reload(null, false);
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            });
        });
    </script>
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush
