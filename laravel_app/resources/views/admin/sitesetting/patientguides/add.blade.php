@extends('admin.layouts.app')
@section('title','Add Patient Guide')
@section('content')

    <section class="content">
        <div class="container-fluid mt-5">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2><strong>Patient Guide</strong></h2>
                        </div>
                        <div class="body">
                            <form id="patientGuideForm">
                                @csrf

                                <div class="row clearfix">
                                    <div class="col-sm-6 d-none" id="guideParentWrapper">
                                        <select name="parentid" id="guideParentSelect" class="form-control">
                                            <option value="">— Loading Main Guides —</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6 d-none" id="guideChildWrapper">
                                        <select name="childid" id="guideChildSelect" class="form-control">
                                            <option value="">— Select Child Guide —</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-12 mt-3">
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control" placeholder="Patient Guide Name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix mt-3">
                                    <div class="col-sm-12">
                                        <button type="button" onclick="submitPatientGuide()" class="btn btn-primary btn-round">Submit</button>
                                        <button type="reset" class="btn btn-default btn-round btn-simple">Cancel</button>
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
        function fetchParentGuides() {
            $.ajax({
                url: "{{ route('admin.patient-guides.parents') }}",
                type: 'GET',
                success: function(data){
                    $('#guideParentWrapper').removeClass('d-none');
                    let options = '<option value="">— No Parent (Top Level Guide) —</option>';
                    data.forEach(function(item){
                        options += '<option value="' + item.id + '">' + item.name + '</option>';
                    });
                    $('#guideParentSelect').html(options);
                },
                error: function(){
                    $('#guideParentSelect').html('<option value="">Failed to load guides</option>');
                }
            });
        }

        $(document).ready(function(){
            fetchParentGuides();
        });

        function submitPatientGuide() {
            let form = $('#patientGuideForm');
            let formData = new FormData(form[0]);

            $.ajax({
                url: "{{ route('admin.patient-guides.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Patient Guide Added!',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });

                    form.trigger('reset');
                    $('#guideChildWrapper').addClass('d-none');
                    fetchParentGuides();
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
                            text: 'Something went wrong!'
                        });
                    }
                }
            });
        }

        $('#guideParentSelect').on('change', function () {
            let parentId = $(this).val();

            if (parentId !== '') {
                $.ajax({
                    url: "{{ url('admin/patient-guides') }}/" + parentId + "/children",
                    type: 'GET',
                    success: function (data) {
                        let options = '<option value="">— Select Child Guide —</option>';

                        if (data.length > 0) {
                            $('#guideChildWrapper').removeClass('d-none');
                            data.forEach(function (item) {
                                options += '<option value="' + item.id + '">' + item.name + '</option>';
                            });
                        } else {
                            options += '<option value="">No Child Found</option>';
                        }

                        $('#guideChildSelect').html(options);
                    }
                });
            } else {
                $('#guideChildWrapper').addClass('d-none');
                $('#guideChildSelect').html('<option value="">— Select Child Guide —</option>');
            }
        });
    </script>
@endpush
