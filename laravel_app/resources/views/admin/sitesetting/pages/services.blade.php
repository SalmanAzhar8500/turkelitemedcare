@extends('admin.layouts.app')
@section('title', 'Services Page Settings')
@section('content')
	<section class="content">
		<div class="container-fluid">
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card">
						<div class="header">
							<h2><strong>Services</strong> Page Data</h2>
						</div>
						<div class="body">
							<div class="row clearfix">
								<div class="col-md-6">
									<label for="mainServiceSelect">Select Service (Main)</label>
									<select id="mainServiceSelect" class="form-control">
										<option value="">-- Select Service --</option>
										@foreach($mainServices as $service)
											<option value="{{ $service->id }}">{{ $service->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-6 d-none" id="childServiceWrapper">
									<label for="childServiceSelect">Select Service (Child)</label>
									<select id="childServiceSelect" class="form-control">
										<option value="">-- Select Service --</option>

									</select>
								</div>
								<div class="col-md-6 d-none mt-3" id="preChildServiceWrapper">
									<label for="preChildServiceSelect">Select Service (Pre Child)</label>
									<select id="preChildServiceSelect" class="form-control">
										<option value="">-- Select Service --</option>
									</select>
								</div>
							</div>

							<form id="serviceDataForm" class="mt-4 d-none" enctype="multipart/form-data">
								@csrf
								<input type="hidden" id="selectedServiceId" value="">
								<input type="hidden" name="remove_image" id="removeServiceImage" value="0">
								<input type="hidden" name="remove_detail_cta_image" id="removeDetailCtaImage" value="0">

								<div class="row clearfix">
									<div class="col-md-6">
										<div class="form-group">
											<label>Service Name</label>
											<input type="text" name="name" id="serviceName" class="form-control" required>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>Service Image</label>
											<input type="file" name="image" id="serviceImage" class="form-control" accept=".jpg,.jpeg,.png,.webp">
										</div>
									</div>

									<div class="col-md-12">
										<img id="serviceImagePreview" src="" alt="Service Preview" style="max-width:180px;display:none;margin-bottom:15px;">
										<button type="button" id="removeServiceImageBtn" class="btn btn-sm btn-danger mb-3 d-none">Remove Service Image</button>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label>Description</label>
											<textarea name="description" id="serviceDescription" class="form-control" rows="5" placeholder="Enter service description"></textarea>
											<small class="text-muted">Max 200 words.</small>
										</div>
									</div>

									<div class="col-md-12 mt-3">
										<h5>Service Detail Dynamic Content</h5>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>Sidebar Title</label>
											<input type="text" name="detail_sidebar_title" id="detailSidebarTitle" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>CTA Small Text</label>
											<input type="text" name="detail_cta_text" id="detailCtaText" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>CTA Title</label>
											<input type="text" name="detail_cta_title" id="detailCtaTitle" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>CTA Button Text</label>
											<input type="text" name="detail_cta_button_text" id="detailCtaButtonText" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>CTA Image</label>
											<input type="file" name="detail_cta_image" id="detailCtaImage" class="form-control" accept=".jpg,.jpeg,.png,.webp">
										</div>
									</div>
									<div class="col-md-12">
										<img id="detailCtaImagePreview" src="" alt="CTA Preview" style="max-width:180px;display:none;margin-bottom:15px;">
										<button type="button" id="removeDetailCtaImageBtn" class="btn btn-sm btn-danger mb-3 d-none">Remove CTA Image</button>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>Highlights Title</label>
											<input type="text" name="detail_highlights_title" id="detailHighlightsTitle" class="form-control">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Highlights Text</label>
											<textarea name="detail_highlights_text" id="detailHighlightsText" class="form-control" rows="3"></textarea>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Highlights Items (one per line)</label>
											<textarea name="detail_highlights_items" id="detailHighlightsItems" class="form-control" rows="4"></textarea>
										</div>
									</div>

									<div class="col-md-12 mt-2">
										<div class="form-group">
											<label>Features Section Title</label>
											<input type="text" name="detail_features_title" id="detailFeaturesTitle" class="form-control">
										</div>
									</div>
									<div class="col-md-12 mt-1">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<label class="mb-0">Features</label>
											<button type="button" id="addMoreFeatureBtn" class="btn btn-sm btn-outline-primary">Add More Feature</button>
										</div>
										<div id="featureItemsWrapper"></div>
									</div>

									<div class="col-md-6 mt-2">
										<div class="form-group">
											<label>Steps Heading</label>
											<input type="text" name="detail_steps_heading" id="detailStepsHeading" class="form-control">
										</div>
									</div>
									<div class="col-md-6 mt-2">
										<div class="form-group">
											<label>FAQ Heading</label>
											<input type="text" name="detail_faq_heading" id="detailFaqHeading" class="form-control">
										</div>
									</div>

									<div class="col-md-6 mt-2">
										<div class="form-group">
											<label>Steps Section Title</label>
											<input type="text" name="detail_steps_title" id="detailStepsTitle" class="form-control">
										</div>
									</div>
									<div class="col-md-6 mt-2">
										<div class="form-group">
											<label>FAQ Section Title</label>
											<input type="text" name="detail_faq_title" id="detailFaqTitle" class="form-control">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Steps Intro Text</label>
											<textarea name="detail_steps_text" id="detailStepsText" class="form-control" rows="3"></textarea>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>Step 1 Title</label>
											<input type="text" name="detail_step1_title" id="detailStep1Title" class="form-control">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Step 2 Title</label>
											<input type="text" name="detail_step2_title" id="detailStep2Title" class="form-control">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Step 3 Title</label>
											<input type="text" name="detail_step3_title" id="detailStep3Title" class="form-control">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Step 1 Icon (Text)</label>
											<input type="text" name="detail_step1_icon" id="detailStep1Icon" class="form-control" placeholder="e.g. fas fa-heart or <svg>...</svg>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Step 2 Icon (Text)</label>
											<input type="text" name="detail_step2_icon" id="detailStep2Icon" class="form-control" placeholder="e.g. fas fa-heart or <svg>...</svg>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Step 3 Icon (Text)</label>
											<input type="text" name="detail_step3_icon" id="detailStep3Icon" class="form-control" placeholder="e.g. fas fa-heart or <svg>...</svg>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Step 1 Description</label>
											<textarea name="detail_step1_description" id="detailStep1Description" class="form-control" rows="3"></textarea>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Step 2 Description</label>
											<textarea name="detail_step2_description" id="detailStep2Description" class="form-control" rows="3"></textarea>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Step 3 Description</label>
											<textarea name="detail_step3_description" id="detailStep3Description" class="form-control" rows="3"></textarea>
										</div>
									</div>

									<div class="col-md-12 mt-2">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<label class="mb-0">FAQs</label>
											<button type="button" id="addMoreFaqBtn" class="btn btn-sm btn-outline-primary">Add More FAQ</button>
										</div>
										<div id="faqItemsWrapper"></div>
									</div>
								</div>

								<button type="submit" class="btn btn-primary btn-round">Save</button>
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
		let serviceDescriptionEditor = null;
		let faqAnswerEditors = {};

		$(document).ready(function () {
			if (typeof CKEDITOR !== 'undefined' && document.getElementById('serviceDescription')) {
				serviceDescriptionEditor = CKEDITOR.replace('serviceDescription');
			}
		});

		function setDescriptionContent(content) {
			if (serviceDescriptionEditor) {
				serviceDescriptionEditor.setData(content ?? '');
				return;
			}

			$('#serviceDescription').val(content ?? '');
		}

		function syncDescriptionForSubmit() {
			if (serviceDescriptionEditor) {
				$('#serviceDescription').val(serviceDescriptionEditor.getData());
			}
		}

		function getFaqAnswerEditorId(index) {
			return 'detailFaqAnswerEditor_' + index + '_' + Date.now() + '_' + Math.floor(Math.random() * 100000);
		}

		function initFaqAnswerEditor(textarea) {
			if (typeof CKEDITOR === 'undefined' || !textarea) {
				return;
			}

			if (!textarea.id) {
				textarea.id = getFaqAnswerEditorId($(textarea).closest('.faq-item').data('index') || 0);
			}

			if (CKEDITOR.instances[textarea.id]) {
				faqAnswerEditors[textarea.id] = CKEDITOR.instances[textarea.id];
				return;
			}

			faqAnswerEditors[textarea.id] = CKEDITOR.replace(textarea.id);
		}

		function initFaqAnswerEditors(scope) {
			const $scope = scope ? $(scope) : $(document);
			$scope.find('.faq-answer').each(function () {
				initFaqAnswerEditor(this);
			});
		}

		function syncFaqAnswersForSubmit() {
			Object.keys(faqAnswerEditors).forEach(function (editorId) {
				const editor = faqAnswerEditors[editorId];
				if (!editor) {
					return;
				}

				const textarea = document.getElementById(editorId);
				if (textarea) {
					textarea.value = editor.getData();
				}
			});
		}

		function destroyFaqAnswerEditor(textarea) {
			if (!textarea || !textarea.id) {
				return;
			}

			if (faqAnswerEditors[textarea.id]) {
				faqAnswerEditors[textarea.id].destroy(true);
				delete faqAnswerEditors[textarea.id];
				return;
			}

			if (CKEDITOR.instances[textarea.id]) {
				CKEDITOR.instances[textarea.id].destroy(true);
			}
		}

		function destroyAllFaqAnswerEditors() {
			Object.keys(faqAnswerEditors).forEach(function (editorId) {
				if (faqAnswerEditors[editorId]) {
					faqAnswerEditors[editorId].destroy(true);
				}
			});

			faqAnswerEditors = {};
		}

        $('#mainServiceSelect').on('change', function () {

            let parentId = $(this).val();

            if (parentId != '') {



                $.ajax({
                    url: "{{ url('admin/site-services') }}/" + parentId + "/children",
                    type: 'GET',
                    success: function (data) {

                        let options = '<option value="">— Select Child Service —</option>';

                        if (data.length > 0) {
                            $('#childServiceWrapper').removeClass('d-none'); // Show child select
                            $('#childSelect').html('<option>Loading...</option>');
                            data.forEach(function (item) {
                                options += '<option value="' + item.id + '">' + item.name + '</option>';
                            });
                        } else {
                            options += '<option value="">No Child Found</option>';
                        }

                        $('#childServiceSelect').html(options);
                    }
                });

            } else {

                $('#childWrapper').addClass('d-none'); // Hide again
                $('#childSelect').html('<option value="">— Select Child Service —</option>');
            }

        });

		function resetSelect(selectId) {
			$(selectId).html('<option value="">-- Select Service --</option>');
		}

		function hideForm() {
			$('#serviceDataForm').addClass('d-none');
			$('#selectedServiceId').val('');
		}

		function createFaqItem(index, question, answer) {
			const q = question ?? '';
			const a = answer ?? '';
			const answerEditorId = getFaqAnswerEditorId(index);

			return `
				<div class="border rounded p-3 mb-3 faq-item" data-index="${index}">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<strong>FAQ ${index + 1}</strong>
						<button type="button" class="btn btn-sm btn-danger remove-faq-btn">Remove</button>
					</div>
					<div class="form-group">
						<label>Question</label>
						<input type="text" name="detail_faqs[${index}][question]" class="form-control" value="${$('<div>').text(q).html()}">
					</div>
					<div class="form-group mb-0">
						<label>Answer</label>
						<textarea id="${answerEditorId}" name="detail_faqs[${index}][answer]" class="form-control faq-answer" rows="3">${$('<div>').text(a).html()}</textarea>
					</div>
				</div>
			`;
		}

		function createFeatureItem(index, title, description, imageUrl, existingImagePath, iconValue) {
			const t = title ?? '';
			const d = description ?? '';
			const existingPath = existingImagePath ?? '';
			const icon = iconValue ?? '';
			const previewStyle = imageUrl ? '' : 'display:none;';
			const iconLooksLikeImage = icon && (/^(https?:\/\/|\/)/i.test(icon) || /\.(svg|png|jpe?g|webp)$/i.test(icon));
			const iconPreviewUrl = iconLooksLikeImage ? icon : '';
			const iconPreviewStyle = iconPreviewUrl ? '' : 'display:none;';

			return `
				<div class="border rounded p-3 mb-3 feature-item" data-index="${index}">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<strong>Feature ${index + 1}</strong>
						<button type="button" class="btn btn-sm btn-danger remove-feature-btn">Remove</button>
					</div>
					<input type="hidden" name="detail_features[${index}][existing_image]" class="feature-existing-image" value="${$('<div>').text(existingPath).html()}">
					<input type="hidden" name="detail_features[${index}][existing_icon]" class="feature-existing-icon" value="${$('<div>').text(icon).html()}">
					<input type="hidden" name="detail_features[${index}][remove_image]" class="feature-remove-image" value="0">
					<input type="hidden" name="detail_features[${index}][remove_icon]" class="feature-remove-icon" value="0">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Feature Title</label>
								<input type="text" name="detail_features[${index}][title]" class="form-control" value="${$('<div>').text(t).html()}">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Feature Icon (Text)</label>
								<input type="text" name="detail_features[${index}][icon]" class="form-control feature-icon-input" value="${$('<div>').text(icon).html()}" placeholder="e.g. fas fa-heart or /images/icon.svg">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Feature Icon Image</label>
								<input type="file" name="detail_features[${index}][icon_image]" class="form-control feature-icon-image-input" accept=".jpg,.jpeg,.png,.webp,.svg">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Feature Image</label>
								<input type="file" name="detail_features[${index}][image]" class="form-control feature-image-input" accept=".jpg,.jpeg,.png,.webp">
							</div>
						</div>
						<div class="col-md-6">
							<img src="${iconPreviewUrl}" alt="Feature Icon Preview" class="feature-icon-preview" style="max-width:80px;margin-bottom:12px;${iconPreviewStyle}">
							<button type="button" class="btn btn-sm btn-danger mb-3 feature-remove-icon-btn ${iconPreviewUrl || icon ? '' : 'd-none'}">Remove Icon</button>
						</div>
						<div class="col-md-6">
							<img src="${imageUrl || ''}" alt="Feature Preview" class="feature-image-preview" style="max-width:180px;margin-bottom:12px;${previewStyle}">
							<button type="button" class="btn btn-sm btn-danger mb-3 feature-remove-image-btn ${imageUrl ? '' : 'd-none'}">Remove Image</button>
						</div>
						<div class="col-md-12">
							<div class="form-group mb-0">
								<label>Feature Description</label>
								<textarea name="detail_features[${index}][description]" class="form-control" rows="3">${$('<div>').text(d).html()}</textarea>
							</div>
						</div>
					</div>
				</div>
			`;
		}

		function normalizeFaqIndexes() {
			$('#faqItemsWrapper .faq-item').each(function (index) {
				$(this).attr('data-index', index);
				$(this).find('strong').text('FAQ ' + (index + 1));
				$(this).find('input[name*="[question]"]').attr('name', `detail_faqs[${index}][question]`);
				$(this).find('textarea[name*="[answer]"]').attr('name', `detail_faqs[${index}][answer]`);
			});
		}

		function normalizeFeatureIndexes() {
			$('#featureItemsWrapper .feature-item').each(function (index) {
				$(this).attr('data-index', index);
				$(this).find('strong').text('Feature ' + (index + 1));
				$(this).find('input[name*="[title]"]').attr('name', `detail_features[${index}][title]`);
				$(this).find('textarea[name*="[description]"]').attr('name', `detail_features[${index}][description]`);
				$(this).find('input.feature-image-input').attr('name', `detail_features[${index}][image]`);
				$(this).find('input.feature-icon-input').attr('name', `detail_features[${index}][icon]`);
				$(this).find('input.feature-icon-image-input').attr('name', `detail_features[${index}][icon_image]`);
				$(this).find('input.feature-existing-image').attr('name', `detail_features[${index}][existing_image]`);
				$(this).find('input.feature-existing-icon').attr('name', `detail_features[${index}][existing_icon]`);
				$(this).find('input.feature-remove-image').attr('name', `detail_features[${index}][remove_image]`);
				$(this).find('input.feature-remove-icon').attr('name', `detail_features[${index}][remove_icon]`);
			});
		}

		function toggleSinglePreview(previewSelector, buttonSelector, url) {
			if (url) {
				$(previewSelector).attr('src', url).show();
				$(buttonSelector).removeClass('d-none');
				return;
			}

			$(previewSelector).attr('src', '').hide();
			$(buttonSelector).addClass('d-none');
		}

		function renderFaqItems(faqs) {
			const items = Array.isArray(faqs) ? faqs : [];
			const $wrapper = $('#faqItemsWrapper');
			destroyAllFaqAnswerEditors();
			$wrapper.html('');

			if (items.length === 0) {
				$wrapper.append(createFaqItem(0, '', ''));
				initFaqAnswerEditors($wrapper);
				return;
			}

			items.forEach(function (faq, index) {
				$wrapper.append(createFaqItem(index, faq.question, faq.answer));
			});

			initFaqAnswerEditors($wrapper);
		}

		function renderFeatureItems(features) {
			const items = Array.isArray(features) ? features : [];
			const $wrapper = $('#featureItemsWrapper');
			$wrapper.html('');

			if (items.length === 0) {
				$wrapper.append(createFeatureItem(0, '', '', '', '', ''));
				return;
			}

			items.forEach(function (feature, index) {
				$wrapper.append(createFeatureItem(index, feature.title, feature.description, feature.image_url, feature.image, feature.icon));
			});
		}

		function loadServiceData(serviceId) {
			$.get('/admin/service/edit/' + serviceId, function (res) {
				const detail = res.detail_content || {};
				const setValue = function (selector, value) {
					$(selector).val(value ?? '');
				};

				$('#selectedServiceId').val(res.id);
				$('#serviceName').val(res.name ?? '');
				$('#removeServiceImage').val('0');
				$('#removeDetailCtaImage').val('0');
				$('#serviceImage').val('');
				setDescriptionContent(res.description ?? '');
				setValue('#detailSidebarTitle', detail.sidebar_title);
				setValue('#detailCtaText', detail.cta_text);
				setValue('#detailCtaTitle', detail.cta_title);
				setValue('#detailCtaButtonText', detail.cta_button_text);
				toggleSinglePreview('#detailCtaImagePreview', '#removeDetailCtaImageBtn', detail.cta_image_url || '');
				$('#detailCtaImage').val('');
				setValue('#detailHighlightsTitle', detail.highlights_title);
				setValue('#detailHighlightsText', detail.highlights_text);
				setValue('#detailHighlightsItems', detail.highlights_items);
				setValue('#detailFeaturesTitle', detail.features_title);
				renderFeatureItems(detail.features || []);
				setValue('#detailStepsHeading', detail.steps_heading);
				setValue('#detailFaqHeading', detail.faq_heading);
				setValue('#detailStepsTitle', detail.steps_title);
				setValue('#detailStepsText', detail.steps_text);
				setValue('#detailStep1Title', detail.step1_title);
				setValue('#detailStep1Icon', detail.step1_icon);
				setValue('#detailStep1Description', detail.step1_description);
				setValue('#detailStep2Title', detail.step2_title);
				setValue('#detailStep2Icon', detail.step2_icon);
				setValue('#detailStep2Description', detail.step2_description);
				setValue('#detailStep3Title', detail.step3_title);
				setValue('#detailStep3Icon', detail.step3_icon);
				setValue('#detailStep3Description', detail.step3_description);
				setValue('#detailFaqTitle', detail.faq_title);
				renderFaqItems(detail.faqs || []);

				toggleSinglePreview('#serviceImagePreview', '#removeServiceImageBtn', res.image_url || '');

				$('#serviceDataForm').removeClass('d-none');
			});
		}

		$('#removeServiceImageBtn').on('click', function () {
			$('#removeServiceImage').val('1');
			$('#serviceImage').val('');
			toggleSinglePreview('#serviceImagePreview', '#removeServiceImageBtn', '');
		});

		$('#removeDetailCtaImageBtn').on('click', function () {
			$('#removeDetailCtaImage').val('1');
			$('#detailCtaImage').val('');
			toggleSinglePreview('#detailCtaImagePreview', '#removeDetailCtaImageBtn', '');
		});

		$(document).on('click', '#addMoreFaqBtn', function () {
			const index = $('#faqItemsWrapper .faq-item').length;
			const $item = $(createFaqItem(index, '', ''));
			$('#faqItemsWrapper').append($item);
			initFaqAnswerEditors($item);
		});

		$(document).on('click', '#addMoreFeatureBtn', function () {
			const index = $('#featureItemsWrapper .feature-item').length;
			$('#featureItemsWrapper').append(createFeatureItem(index, '', '', '', '', ''));
		});

		$(document).on('click', '.remove-faq-btn', function () {
			const total = $('#faqItemsWrapper .faq-item').length;
			const $item = $(this).closest('.faq-item');
			const answerTextarea = $item.find('.faq-answer').get(0);
			if (total <= 1) {
				$item.find('input').val('');
				if (answerTextarea && answerTextarea.id && faqAnswerEditors[answerTextarea.id]) {
					faqAnswerEditors[answerTextarea.id].setData('');
				} else {
					$item.find('textarea').val('');
				}
				return;
			}

			destroyFaqAnswerEditor(answerTextarea);
			$item.remove();
			normalizeFaqIndexes();
		});

		$(document).on('click', '.remove-feature-btn', function () {
			const total = $('#featureItemsWrapper .feature-item').length;
			const $item = $(this).closest('.feature-item');
			if (total <= 1) {
				$item.find('input[type="text"], textarea, input.feature-existing-image').val('');
				$item.find('input.feature-existing-icon, input.feature-remove-image, input.feature-remove-icon').val('0');
				$item.find('input[type="file"]').val('');
				$item.find('.feature-image-preview').attr('src', '').hide();
				$item.find('.feature-icon-preview').attr('src', '').hide();
				$item.find('.feature-remove-image-btn, .feature-remove-icon-btn').addClass('d-none');
				return;
			}

			$item.remove();
			normalizeFeatureIndexes();
		});

		$(document).on('change', '.feature-image-input', function () {
			const file = this.files && this.files[0] ? this.files[0] : null;
			const $item = $(this).closest('.feature-item');
			const $preview = $item.find('.feature-image-preview');
			if (!file) {
				return;
			}

			$item.find('.feature-existing-image').val('');
			$item.find('.feature-remove-image').val('0');
			$preview.attr('src', URL.createObjectURL(file)).show();
			$item.find('.feature-remove-image-btn').removeClass('d-none');
		});

		$(document).on('change', '.feature-icon-image-input', function () {
			const file = this.files && this.files[0] ? this.files[0] : null;
			const $item = $(this).closest('.feature-item');
			const $preview = $item.find('.feature-icon-preview');
			if (!file) {
				return;
			}

			$item.find('.feature-remove-icon').val('0');
			$item.find('.feature-icon-input').val('');
			$preview.attr('src', URL.createObjectURL(file)).show();
			$item.find('.feature-remove-icon-btn').removeClass('d-none');
		});

		$(document).on('click', '.feature-remove-image-btn', function () {
			const $item = $(this).closest('.feature-item');
			$item.find('.feature-remove-image').val('1');
			$item.find('.feature-existing-image').val('');
			$item.find('.feature-image-input').val('');
			$item.find('.feature-image-preview').attr('src', '').hide();
			$(this).addClass('d-none');
		});

		$(document).on('click', '.feature-remove-icon-btn', function () {
			const $item = $(this).closest('.feature-item');
			$item.find('.feature-remove-icon').val('1');
			$item.find('.feature-existing-icon').val('');
			$item.find('.feature-icon-input').val('');
			$item.find('.feature-icon-image-input').val('');
			$item.find('.feature-icon-preview').attr('src', '').hide();
			$(this).addClass('d-none');
		});

		$(document).on('input', '.feature-icon-input', function () {
			const $item = $(this).closest('.feature-item');
			const value = ($(this).val() || '').trim();
			const isImagePath = /^(https?:\/\/|\/)/i.test(value) || /\.(svg|png|jpe?g|webp)$/i.test(value);
			$item.find('.feature-remove-icon').val('0');
			$item.find('.feature-existing-icon').val(value);

			if (isImagePath) {
				$item.find('.feature-icon-preview').attr('src', value).show();
				$item.find('.feature-remove-icon-btn').removeClass('d-none');
				return;
			}

			$item.find('.feature-icon-preview').attr('src', '').hide();
			$item.find('.feature-remove-icon-btn').toggleClass('d-none', value === '');
		});

		$('#detailCtaImage').on('change', function () {
			const file = this.files && this.files[0] ? this.files[0] : null;
			if (!file) {
				return;
			}

			const previewUrl = URL.createObjectURL(file);
			$('#removeDetailCtaImage').val('0');
			toggleSinglePreview('#detailCtaImagePreview', '#removeDetailCtaImageBtn', previewUrl);
		});

		$('#serviceImage').on('change', function () {
			const file = this.files && this.files[0] ? this.files[0] : null;
			if (!file) {
				return;
			}

			$('#removeServiceImage').val('0');
			toggleSinglePreview('#serviceImagePreview', '#removeServiceImageBtn', URL.createObjectURL(file));
		});


		function loadChildren(parentId, targetSelectId, targetWrapperId, callback) {
			$.get('/admin/site-services/' + parentId + '/children', function (items) {
				if (items.length > 0) {
					let options = '<option value="">-- Select Service --</option>';
					items.forEach(function (item) {
						options += '<option value="' + item.id + '">' + item.name + '</option>';
					});

					$(targetSelectId).html(options);
					$(targetWrapperId).removeClass('d-none');
					callback(true);
				} else {
					resetSelect(targetSelectId);
					$(targetWrapperId).addClass('d-none');
					callback(false);
				}
			});
		}

		$('#mainServiceSelect').on('change', function () {
			let serviceId = $(this).val();
			resetSelect('#childServiceSelect');
			resetSelect('#preChildServiceSelect');
			$('#childServiceWrapper').addClass('d-none');
			$('#preChildServiceWrapper').addClass('d-none');
			hideForm();

			if (!serviceId) {
				return;
			}

			loadChildren(serviceId, '#childServiceSelect', '#childServiceWrapper', function (hasChild) {
				if (!hasChild) {
					loadServiceData(serviceId);
				}
			});
		});

		$('#childServiceSelect').on('change', function () {
			let childId = $(this).val();
			resetSelect('#preChildServiceSelect');
			$('#preChildServiceWrapper').addClass('d-none');
			hideForm();

			if (!childId) {
				return;
			}

			loadChildren(childId, '#preChildServiceSelect', '#preChildServiceWrapper', function (hasPreChild) {
				if (!hasPreChild) {
					loadServiceData(childId);
				}
			});
		});

		$('#preChildServiceSelect').on('change', function () {
			let preChildId = $(this).val();
			hideForm();

			if (!preChildId) {
				return;
			}

			loadServiceData(preChildId);
		});

		$('#serviceDataForm').on('submit', function (e) {
			e.preventDefault();

			let serviceId = $('#selectedServiceId').val();
			if (!serviceId) {
				return;
			}

			syncDescriptionForSubmit();
			syncFaqAnswersForSubmit();
			let formData = new FormData(this);

			$.ajax({
				url: '/admin/service/update/' + serviceId,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function (res) {
					const responseMessage = (res && typeof res.message === 'string') ? res.message : '';
					const hasSuccessMessage = responseMessage.toLowerCase().includes('success');
					const isSuccessful = Boolean(res && (res.success === true || hasSuccessMessage));

					Swal.fire({
						icon: 'success',
						title: 'Saved',
						text: res.message || 'Service updated successfully'
					});

					if (isSuccessful) {
						$('#mainServiceSelect').val('');
						$('#childServiceSelect').val('');
						$('#preChildServiceSelect').val('');
						$('#mainServiceSelect').trigger('change');
					}
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
	</script>
@endpush
