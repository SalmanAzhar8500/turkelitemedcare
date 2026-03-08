@extends('admin.layouts.app')
@section('title', 'Site Services')
@section('content')

    <section class="content home">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12">
                    <div class="card patient_list">
                        <div class="header">
                            <h2><strong>Main</strong> Services List</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="servicesTable" class="table table-striped m-b-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="dynamicServiceModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Service Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <h6>Hierarchy</h6>
                    <p id="breadcrumbArea"></p>

                    <hr>

                    <table id="dynamicChildTable" class="table table-bordered">
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

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editServiceForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="editServiceId">

                        <div class="mb-3">
                            <label>Service Name</label>
                            <input type="text" id="editServiceName" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Parent Service</label>
                            <input type="text" id="editParentName" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Type</label>
                            <input type="text" id="editServiceType" class="form-control" readonly>
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

{{--<script>--}}
{{--    $(document).ready(function() {--}}
{{--        $('#servicesTable').DataTable({--}}
{{--            processing: true,--}}
{{--            serverSide: true,--}}
{{--            ajax: '{{ route("admin.site.services.data") }}',--}}
{{--            columns: [--}}
{{--                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },--}}
{{--                { data: 'name', name: 'name' },--}}
{{--                { data: 'slug', name: 'slug' },--}}
{{--                { data: 'action', name: 'action', orderable: false, searchable: false }--}}
{{--            ]--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
<script>
    $(document).ready(function(){

        $('#servicesTable').DataTable({
            processing:true,
            serverSide:true,
            ajax:'{{ route("admin.site.services.data") }}',
            columns:[
                {data:'DT_RowIndex'},
                {data:'name'},
                {data:'slug'},
                {data:'action'}
            ]
        });

    });

    $(document).on('click','.viewServiceBtn',function(){

        let slug = $(this).data('slug');

        $('#dynamicServiceModal').modal('show');

        $.get('/admin/service/'+slug,function(data){

            $('#breadcrumbArea').html(data.breadcrumb.join(' → '));

            if($.fn.DataTable.isDataTable('#dynamicChildTable')){
                $('#dynamicChildTable').DataTable().destroy();
            }

            $('#dynamicChildTable').DataTable({
                processing:true,
                serverSide:true,
                ajax:'/admin/service/'+slug+'/children',
                columns:[
                    {data:'DT_RowIndex'},
                    {data:'name'},
                    {data:'slug'},
                    {data:'action'}
                ]
            });

        });

    });

    {{--$(document).on('click','.deleteServiceBtn',function(){--}}

    {{--    let id = $(this).data('id');--}}

    {{--    if(confirm('Delete this service?')){--}}
    {{--        $.ajax({--}}
    {{--            url:'/admin/service/'+id,--}}
    {{--            type:'DELETE',--}}
    {{--            data:{_token:'{{ csrf_token() }}'},--}}
    {{--            success:function(){--}}
    {{--                $('#servicesTable').DataTable().ajax.reload();--}}
    {{--                if($.fn.DataTable.isDataTable('#dynamicChildTable')){--}}
    {{--                    $('#dynamicChildTable').DataTable().ajax.reload();--}}
    {{--                }--}}
    {{--            }--}}
    {{--        });--}}
    {{--    }--}}

    {{--});--}}
</script>
{{--    delete--}}
<script>
    $(document).on('click', '.deleteServiceBtn', function(){
        let id = $(this).data('id'); // button me data-id hona chahiye
        if(!id){
            alert('Service ID missing!');
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
                    url: '/admin/service/'+id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response){
                        if(response.success){
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );

                            // Reload tables by name after deletion
                            if($.fn.DataTable.isDataTable('#preChildServicesTable')){
                                $('#preChildServicesTable').DataTable().ajax.reload();
                            }
                            if($.fn.DataTable.isDataTable('#childServicesTable')){
                                $('#childServicesTable').DataTable().ajax.reload();
                            }
                            if($.fn.DataTable.isDataTable('#mainServicesTable')){
                                $('#mainServicesTable').DataTable().ajax.reload();
                            }
                        }
                    },
                    error: function(){
                        Swal.fire(
                            'Error!',
                            'Something went wrong!',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).on('click','.editServiceBtn',function(){

        let id = $(this).data('id');

        $.get('/admin/service/edit/'+id,function(res){

            $('#editServiceId').val(res.id);
            $('#editServiceName').val(res.name);
            $('#editParentName').val(res.parent_name ?? 'Main Service');
            $('#editServiceType').val(res.type);

            $('#editServiceModal').modal('show');
        });
    });


    $('#editServiceForm').submit(function(e){
        e.preventDefault();

        let id = $('#editServiceId').val();
        let name = $('#editServiceName').val();

        $.ajax({
            url:'/admin/service/update/'+id,
            type:'POST',
            data:{
                _token:'{{ csrf_token() }}',
                name:name
            },
            success:function(res){
                if(res.success){

                    $('#editServiceModal').modal('hide');

                    // ✅ Safe reload
                    if ($.fn.DataTable.isDataTable('#dynamicChildTable')) {
                        $('#dynamicChildTable').DataTable().ajax.reload(null, false);
                    }

                    if ($.fn.DataTable.isDataTable('#servicesTable')) {
                        $('#servicesTable').DataTable().ajax.reload(null, false);
                    }

                    // ✅ SweetAlert Success
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error:function(xhr){
                alert(xhr.responseJSON?.message ?? 'Something went wrong!');
            }
        });
    });
</script>
@endpush

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush




