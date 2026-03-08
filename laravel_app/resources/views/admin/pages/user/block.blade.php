@extends('admin.layouts.app')
@section('title','Users')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Users </h1>
                    <!--end::Title-->
                    <!--begin::Separator-->
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <!--end::Separator-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">

                        <li class="breadcrumb-item text-muted">All</li>
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>

                        <li class="breadcrumb-item text-muted">Block</li>

                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-fluid">
                <div class="layout-px-spacing">

                    <div class="middle-content container-fluid p-0">
                        <div id="call-ringing"></div>
                        <div class="row layout-spacing">
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <div class="widget-content widget-content-area card">
                                    <div class="card-header border-0 pt-6 w-100">
                                        <div class="col-md-6 mt-3 text-md-start text-center">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
														<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
													</svg>
												</span>
                                                <!--end::Svg Icon-->
                                                <input type="text" data-kt-customer-table-filter="search" id="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search " />
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table"  class="table table-hover table-row-dashed fs-6 gy-5 my-0 dataTable no-footer" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th class="min-w-125px">#</th>
                                                    <th class="min-w-125px">Profile</th>
                                                    <th class="min-w-125px">Status</th>
                                                    <th class="min-w-125px">Action</th>
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

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('block_users') }}",
                    type: 'GET'
                },
                columns: [
                    {data: 'id', name: 'id'},
                    { data: 'ProfileView', name: 'ProfileView' },
                    { data: 'statusView', name: 'statusView' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                "createdRow": function(row, data, dataIndex) {
                    var start = table.page.info().start; // Get the starting index of the current page
                    var incrementedId = start + dataIndex + 1; // Increment the ID based on the page start and index
                    $('td', row).eq(0).html(incrementedId); // Replace the content of the ID cell
                },
                responsive: true,
                pageLength: 10,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });

            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
        function changeStatus(newStatus, id) {
            // Create an object to hold the data
            const data = {
                status: newStatus, // Use newStatus here
                id: id
            };

            $.ajax({
                url: "{{ route('status_users') }}", // Ensure this is correctly formatted in Blade
                type: "GET", // Correct the method to POST
                data: {
                    status: newStatus, // Use newStatus instead of status
                    id: id,
                },
                cache: false,
                success: function(dataResult) {
                    console.log(dataResult);
                    if (dataResult == 1) {
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.success('User Block Successfully.');
                    } else if (dataResult == 2) {
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.success('User Active Successfully.');
                    }
                    else {
                        toastr.error('Something Went Wrong.');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle any error from the AJAX call
                    console.error(error);
                    toastr.error('An error occurred while updating the user.');
                }
            });
        }

        function deleteItem(deleteid) {
// add spinner to button
            $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i> loading...');
            var csrf_token = $("input[name=csrf]").val();
            $.ajax({
                url: '{{route('delete_users')}}',
                type: 'GET', data: {
                    id: deleteid,
                }, success: function (data) {
                    if (data == 1) {
                        toastr.info('Successfully deleted.');
                        $('#table').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error('Something went wrong.');

                    }
                }
            });
        }
    </script>
@endpush
