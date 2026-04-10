@extends('admin.layouts.app')
@section('title', 'Patient Guide Page Settings')

@section('content')
	<section class="content">
		<div class="container-fluid">
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card">
						<div class="header">
							<h2><strong>Patient Guide</strong> Page Header</h2>
						</div>
						<div class="body">
							@if(session('success'))
								<div class="alert alert-success">{{ session('success') }}</div>
							@endif
							<form action="{{ route('admin.site.pages.patientguide.header.store') }}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="row clearfix">
									<div class="col-md-12">
										<div class="form-group">
											<label>Page Header Background Image</label>
											<input type="file" name="patient_guide_page_header_image" class="form-control" accept="image/*">
											@php $patientGuidePageData = is_array($homeSetting->patient_guide_page_data ?? null) ? $homeSetting->patient_guide_page_data : []; @endphp
											@if(!empty($patientGuidePageData['page_header_image']))
												<div class="mt-2">
													<img src="{{ asset('storage/' . $patientGuidePageData['page_header_image']) }}" alt="Current Header Image" style="max-height: 100px; border-radius: 4px;">
													<small class="text-muted d-block">Current header image. Upload a new one to replace it.</small>
												</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<button type="submit" class="btn btn-primary btn-round">Save Header Image</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card">
						<div class="header">
							<h2><strong>Patient Guide</strong> Page Settings</h2>
						</div>
						<div class="body">
							<div class="row clearfix">
								<div class="col-md-6">
									<label for="mainPatientGuideSelect">Select Patient Guide (Main)</label>
									<select id="mainPatientGuideSelect" class="form-control">
										<option value="">-- Select Patient Guide --</option>
										@foreach(($mainPatientGuides ?? collect()) as $guide)
											<option value="{{ $guide->id }}">{{ $guide->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-6 d-none" id="childPatientGuideWrapper">
									<label for="childPatientGuideSelect">Select Patient Guide (Child)</label>
									<select id="childPatientGuideSelect" class="form-control">
										<option value="">-- Select Patient Guide --</option>
									</select>
								</div>
								<div class="col-md-6 d-none mt-3" id="preChildPatientGuideWrapper">
									<label for="preChildPatientGuideSelect">Select Patient Guide (Pre Child)</label>
									<select id="preChildPatientGuideSelect" class="form-control">
										<option value="">-- Select Patient Guide --</option>
									</select>
								</div>
							</div>

							<form id="patientGuideDataForm" class="mt-4 d-none">
								@csrf
								<input type="hidden" id="selectedPatientGuideId" value="">

								<div class="row clearfix">
									<div class="col-md-12">
										<div class="form-group">
											<label>Title</label>
											<input type="text" id="patientGuideName" name="name" class="form-control" required>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Description</label>
											<textarea id="patientGuideDescription" name="description" class="form-control" rows="4"></textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Frontend Form Mode</label>
											<select id="detailFormMode" name="detail_form_mode" class="form-control">
												<option value="tabs">Tabs (Analysis + Booking)</option>
												<option value="analysis_only">Analysis Only (No Tabs)</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Tab 1 Label</label>
											<input type="text" id="detailTabOneLabel" name="detail_tab_one_label" class="form-control" placeholder="Online Hair Analysis">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Tab 2 Label</label>
											<input type="text" id="detailTabTwoLabel" name="detail_tab_two_label" class="form-control" placeholder="Booking">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Tab 1 Form Title</label>
											<input type="text" id="detailTabOneTitle" name="detail_tab_one_title" class="form-control" placeholder="Get a Free Online Hair Analysis">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Tab 2 Form Title</label>
											<input type="text" id="detailTabTwoTitle" name="detail_tab_two_title" class="form-control" placeholder="Book Consultation">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Head Image</label>
											<input type="file" id="detailHeadImage" name="detail_head_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
										</div>
										<img id="detailHeadImagePreview" src="" alt="Head Image Preview" style="max-width:180px;display:none;margin-bottom:15px;">
									</div>

									<div class="col-md-12">
										<button type="submit" class="btn btn-primary btn-round">Save Data</button>
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
	<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
	<script>
		let patientGuideDescriptionEditor = null;

		$(function () {
			if (typeof CKEDITOR !== 'undefined' && document.getElementById('patientGuideDescription')) {
				patientGuideDescriptionEditor = CKEDITOR.replace('patientGuideDescription');
			}

			function setDescriptionContent(content) {
				if (patientGuideDescriptionEditor) {
					patientGuideDescriptionEditor.setData(content ?? '');
					return;
				}

				$('#patientGuideDescription').val(content ?? '');
			}

			function syncDescriptionForSubmit() {
				if (patientGuideDescriptionEditor) {
					$('#patientGuideDescription').val(patientGuideDescriptionEditor.getData());
				}
			}

			function resetSelect(selector) {
				$(selector).html('<option value="">-- Select Patient Guide --</option>').val('');
			}

			function hideForm() {
				$('#patientGuideDataForm').addClass('d-none');
				$('#selectedPatientGuideId').val('');
				$('#patientGuideName').val('');
				setDescriptionContent('');
				$('#detailFormMode').val('tabs');
				$('#detailTabOneLabel').val('');
				$('#detailTabTwoLabel').val('');
				$('#detailTabOneTitle').val('');
				$('#detailTabTwoTitle').val('');
				$('#detailHeadImage').val('');
				$('#detailHeadImagePreview').attr('src', '').hide();
			}

			function loadChildren(parentId, targetSelect, targetWrapper, callback) {
				$.get('/admin/patient-guides/' + parentId + '/children', function (items) {
					if (items.length > 0) {
						let options = '<option value="">-- Select Patient Guide --</option>';
						items.forEach(function (item) {
							options += '<option value="' + item.id + '">' + item.name + '</option>';
						});
						$(targetSelect).html(options);
						$(targetWrapper).removeClass('d-none');
						callback(true);
					} else {
						resetSelect(targetSelect);
						$(targetWrapper).addClass('d-none');
						callback(false);
					}
				});
			}

			function loadPatientGuideData(guideId) {
				$.get('/admin/site-patient-guide-page/data/' + guideId, function (res) {
					const detail = res.detail_content || {};
					$('#selectedPatientGuideId').val(res.id || '');
					$('#patientGuideName').val(res.name || '');
					setDescriptionContent(res.description || '');
					$('#detailFormMode').val(detail.form_mode || 'tabs');
					$('#detailTabOneLabel').val(detail.tab_one_label || '');
					$('#detailTabTwoLabel').val(detail.tab_two_label || '');
					$('#detailTabOneTitle').val(detail.tab_one_title || '');
					$('#detailTabTwoTitle').val(detail.tab_two_title || '');
					$('#detailHeadImage').val('');
					if (detail.head_image_url) {
						$('#detailHeadImagePreview').attr('src', detail.head_image_url).show();
					} else {
						$('#detailHeadImagePreview').attr('src', '').hide();
					}
				});
			}

			$('#detailHeadImage').on('change', function () {
				const file = this.files && this.files[0] ? this.files[0] : null;
				if (!file) {
					return;
				}

				$('#detailHeadImagePreview').attr('src', URL.createObjectURL(file)).show();
			});

			$('#mainPatientGuideSelect').on('change', function () {
				let guideId = $(this).val();
				resetSelect('#childPatientGuideSelect');
				resetSelect('#preChildPatientGuideSelect');
				$('#childPatientGuideWrapper').addClass('d-none');
				$('#preChildPatientGuideWrapper').addClass('d-none');
				hideForm();

				if (!guideId) {
					return;
				}

				$('#patientGuideDataForm').removeClass('d-none');
				loadPatientGuideData(guideId);

				loadChildren(guideId, '#childPatientGuideSelect', '#childPatientGuideWrapper', function (hasChild) {
					return hasChild;
				});
			});

			$('#childPatientGuideSelect').on('change', function () {
				let childId = $(this).val();
				resetSelect('#preChildPatientGuideSelect');
				$('#preChildPatientGuideWrapper').addClass('d-none');
				hideForm();

				if (!childId) {
					return;
				}

				loadChildren(childId, '#preChildPatientGuideSelect', '#preChildPatientGuideWrapper', function (hasPreChild) {
					return hasPreChild;
				});
			});

			$('#preChildPatientGuideSelect').on('change', function () {
				let preChildId = $(this).val();
				hideForm();

				if (!preChildId) {
					return;
				}
			});

			$('#patientGuideDataForm').on('submit', function (e) {
				e.preventDefault();

				let guideId = $('#selectedPatientGuideId').val();
				if (!guideId) {
					Swal.fire({
						icon: 'warning',
						title: 'Select Patient Guide',
						text: 'Please select a patient guide first.'
					});
					return;
				}

				syncDescriptionForSubmit();
				let formData = new FormData(this);

				$.ajax({
					url: '/admin/site-patient-guide-page/update/' + guideId,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function (res) {
						Swal.fire({
							icon: 'success',
							title: 'Saved',
							text: res.message || 'Patient guide updated successfully'
						});

						$('#mainPatientGuideSelect').val('');
						$('#mainPatientGuideSelect').trigger('change');
					},
					error: function (xhr) {
						let errorMessage = 'Something went wrong!';

						if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
							errorMessage = Object.values(xhr.responseJSON.errors).map(function (msg) {
								return msg[0];
							}).join('\n');
						}

						Swal.fire({
							icon: 'error',
							title: 'Validation Error',
							text: errorMessage
						});
					}
				});
			});
		});
	</script>
@endpush
