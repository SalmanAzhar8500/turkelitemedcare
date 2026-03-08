@extends('admin.layouts.app')
@section('title','Dashboard')
@section('content')
@push('css')
    <style>
        .mt-5 {
            margin-top: 6rem !important;
        }
    </style>


@endpush

    <section class="content">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-5 col-sm-12">
                    <h2>Add Services
                        <small class="text-muted">Welcome to Oreo</small>
                    </h2>
                </div>
                <div class="col-lg-5 col-md-7 col-sm-12">
                    <button class="btn btn-white btn-icon btn-round d-none d-md-inline-block float-right m-l-10" type="button">
                        <i class="zmdi zmdi-plus"></i>
                    </button>
                    <ul class="breadcrumb float-md-right">
                        <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i> Oreo</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Doctors</a></li>
                        <li class="breadcrumb-item active">Add Doctors</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-5">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Services</strong> </h2>
                            <ul class="header-dropdown">
                                <li class="remove">
                                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <form id="serviceForm" enctype="multipart/form-data">
                                @csrf

                                <div class="row clearfix">
                                    <div class="col-sm-6 d-none" id="parentWrapper">

                                            <select name="parentid" id="parentSelect" class="form-control">
                                                <option value="">— Loading Parent Services —</option>
                                            </select>

                                    </div>

                                    <div class="col-sm-6 d-none" id="childWrapper">
                                        <select name="childid" id="childSelect" class="form-control">
                                            <option value="">— Select Child Service —</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text"
                                                   name="name"
                                                   class="form-control"
                                                   placeholder="Service Name"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="file"
                                                   name="image"
                                                   class="form-control"
                                                   accept=".jpg,.jpeg,.png,.webp"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <textarea name="description"
                                                      class="form-control"
                                                      rows="4"
                                                      placeholder="Service Description"
                                                      required></textarea>
                                            <small class="text-muted">Note: Description is required and must be maximum 200 words.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix mt-3">
                                    <div class="col-sm-12">
                                        <button type="button"
                                                onclick="submitService()"
                                                class="btn btn-primary btn-round">
                                            Submit
                                        </button>

                                        <button type="reset"
                                                class="btn btn-default btn-round btn-simple">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection
@push('js')
    <script>
        // Function to fetch parent services
        function fetchParentServices() {
            $.ajax({
                url: "{{ route('admin.get.parent.services') }}",
                type: 'GET',
                success: function(data){
                    $('#parentWrapper').removeClass('d-none'); // Show parent select wrapper
                    let options = '<option value="">— No Parent (Top Level Service) —</option>';
                    data.forEach(function(item){
                        options += '<option value="'+item.id+'">'+item.name+'</option>';
                    });
                    $('#parentSelect').html(options);
                },
                error: function(){
                    $('#parentSelect').html('<option value="">Failed to load parents</option>');
                }
            });
        }

        $(document).ready(function(){
            // Initial fetch on page load
            fetchParentServices();
        });

        // Submit service via AJAX
        function submitService() {

            let form = $('#serviceForm');
            let formData = new FormData(form[0]);

            $.ajax({
                url: "{{ route('admin.site.services.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    // SweetAlert auto-close
                    Swal.fire({
                        icon: 'success',
                        title: 'Service Added!',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });

                    // Reset the form
                    form.trigger("reset");

                    // Hide child dropdown if using parent-child dependent select
                    $('#childWrapper').addClass('d-none');

                    // Re-fetch parent services to include the newly added service
                    fetchParentServices();

                },
                error: function(xhr) {

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';

                        $.each(errors, function(key, value) {
                            errorMsg += value[0] + "\n";
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: errorMsg
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "Something went wrong!"
                        });
                    }
                }
            });
        }
    </script>
    <script>
        $('#parentSelect').on('change', function () {

            let parentId = $(this).val();

            if (parentId != '') {



                $.ajax({
                    url: "{{ url('admin/site-services') }}/" + parentId + "/children",
                    type: 'GET',
                    success: function (data) {

                        let options = '<option value="">— Select Child Service —</option>';

                        if (data.length > 0) {
                            $('#childWrapper').removeClass('d-none'); // Show child select
                            $('#childSelect').html('<option>Loading...</option>');
                            data.forEach(function (item) {
                                options += '<option value="' + item.id + '">' + item.name + '</option>';
                            });
                        } else {
                            options += '<option value="">No Child Found</option>';
                        }

                        $('#childSelect').html(options);
                    }
                });

            } else {

                $('#childWrapper').addClass('d-none'); // Hide again
                $('#childSelect').html('<option value="">— Select Child Service —</option>');
            }

        });
    </script>
@endpush
