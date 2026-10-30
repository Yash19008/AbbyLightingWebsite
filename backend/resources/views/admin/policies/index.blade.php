@extends('admin.page')

@section('title', 'Legal & Policy Settings')

@section('extra_css')
<style>
    .policy-tabs .nav-link {
        font-weight: 500;
        font-size: 15px;
        padding: 12px 24px;
        color: #495057;
        border-radius: 6px 6px 0 0;
    }
    .policy-tabs .nav-link.active {
        color: #000;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: 600;
    }
    .tox-tinymce {
        border-radius: 6px;
        border: 1px solid #d9d9d9 !important;
    }
    .banner-preview-box {
        max-width: 420px;
        background: #f8f9fa;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        margin-top: 8px;
    }
    .banner-preview-box img {
        width: 100%;
        height: auto;
        border-radius: 4px;
        display: block;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0 font-weight-bold">Legal & Policy Pages Settings</h3>
                <small class="text-muted">Manage dynamic content, meta titles, descriptions, and styling for Privacy Policy and Terms of Use.</small>
            </div>
            <div>
                @if($activeTab === 'terms')
                    <a href="{{ url('/terms-and-conditions') }}" target="_blank" class="btn btn-outline-dark btn-sm">
                        <i class="ft-external-link mr-1"></i> View Live Terms
                    </a>
                @else
                    <a href="{{ url('/privacy-policy') }}" target="_blank" class="btn btn-outline-dark btn-sm">
                        <i class="ft-external-link mr-1"></i> View Live Privacy Policy
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ft-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ft-alert-triangle mr-2"></i> <strong>Please check the errors below:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs policy-tabs mb-0" id="policyTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'privacy' ? 'active' : '' }}" id="privacy-tab" data-bs-toggle="tab" href="#privacy" role="tab" aria-controls="privacy" aria-selected="{{ $activeTab === 'privacy' ? 'true' : 'false' }}">
                    <i class="ft-shield mr-2"></i> Privacy Policy
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'terms' ? 'active' : '' }}" id="terms-tab" data-bs-toggle="tab" href="#terms" role="tab" aria-controls="terms" aria-selected="{{ $activeTab === 'terms' ? 'true' : 'false' }}">
                    <i class="ft-file-text mr-2"></i> Terms of Use & Conditions
                </a>
            </li>
        </ul>

        <div class="card" style="border-top-left-radius: 0;">
            <div class="card-body p-4">
                <div class="tab-content" id="policyTabContent">
                    
                    {{-- TAB 1: PRIVACY POLICY --}}
                    <div class="tab-pane fade {{ $activeTab === 'privacy' ? 'show active' : '' }}" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                        <form action="{{ route('admin.policies.update') }}" method="POST" id="form-privacy">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tab" value="privacy">

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold" for="privacy_title">Page Meta Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="privacy_title" name="privacy_title" value="{{ old('privacy_title', $privacyTitle) }}" required>
                                    <small class="form-text text-muted">Displayed in browser tab and search engines.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold" for="privacy_description">Meta Description</label>
                                    <input type="text" class="form-control" id="privacy_description" name="privacy_description" value="{{ old('privacy_description', $privacyDescription) }}">
                                    <small class="form-text text-muted">Brief overview for search engines and social cards.</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-weight-bold mb-2" for="privacy_content">
                                    Privacy Policy Content (Rich Text) <span class="text-danger">*</span>
                                </label>
                                <textarea id="privacy_content" name="privacy_content" class="form-control tinymce-editor" rows="22">{!! old('privacy_content', $privacyContent) !!}</textarea>
                                <small class="form-text text-muted mt-2">
                                    Full formatting support: Headings (H1-H4), paragraphs, lists, bold text, links, and tables. Default Abby Lighting typography and styling apply automatically.
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ url('/privacy-policy') }}" target="_blank" class="btn btn-outline-secondary">
                                    <i class="ft-eye mr-1"></i> Open Live Privacy Policy
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ft-save mr-1"></i> Save Privacy Policy
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- TAB 2: TERMS OF USE & CONDITIONS --}}
                    <div class="tab-pane fade {{ $activeTab === 'terms' ? 'show active' : '' }}" id="terms" role="tabpanel" aria-labelledby="terms-tab">
                        <form action="{{ route('admin.policies.update') }}" method="POST" enctype="multipart/form-data" id="form-terms">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tab" value="terms">

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold" for="terms_title">Page Meta Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="terms_title" name="terms_title" value="{{ old('terms_title', $termsTitle) }}" required>
                                    <small class="form-text text-muted">Displayed in browser tab and search engines.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold" for="terms_description">Meta Description</label>
                                    <input type="text" class="form-control" id="terms_description" name="terms_description" value="{{ old('terms_description', $termsDescription) }}">
                                    <small class="form-text text-muted">Brief overview for search engines and social cards.</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-weight-bold" for="terms_banner">Header Banner Image</label>
                                <input type="file" class="form-control-file" id="terms_banner" name="terms_banner" accept="image/*">
                                <small class="form-text text-muted">Optional top banner image displayed across the top of the terms page (recommended: 1920x400).</small>
                                
                                @if(!empty($termsBanner))
                                    <div class="banner-preview-box">
                                        <div class="text-muted small mb-1">Current Banner:</div>
                                        <img src="{{ asset($termsBanner) }}" alt="Terms Banner Preview">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-weight-bold mb-2" for="terms_content">
                                    Terms & Conditions Content (Rich Text) <span class="text-danger">*</span>
                                </label>
                                <textarea id="terms_content" name="terms_content" class="form-control tinymce-editor" rows="22">{!! old('terms_content', $termsContent) !!}</textarea>
                                <small class="form-text text-muted mt-2">
                                    Full formatting support: Headings (H1-H4), numbered clauses, warranty specifications, lists, and links.
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ url('/terms-and-conditions') }}" target="_blank" class="btn btn-outline-secondary">
                                    <i class="ft-eye mr-1"></i> Open Live Terms Page
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ft-save mr-1"></i> Save Terms & Conditions
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('extra_js')
<!-- TinyMCE Rich Text Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
    $(document).ready(function() {
        // Shared TinyMCE configuration tailored with Abby Lighting default typography & styling
        var tinymceConfig = {
            height: 600,
            convert_urls: false,
            relative_urls: false,
            remove_script_host: false,
            plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons help',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | table link image | removeformat code fullscreen',
            menubar: 'file edit view insert format tools table help',
            font_family_formats: 'Poppins=Poppins,sans-serif; Inter=Inter,sans-serif; Libre Baskerville=Libre Baskerville,serif; Arial=arial,helvetica,sans-serif',
            automatic_uploads: true,
            content_style: `
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@300;400;500;600&display=swap');
                body {
                    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                    font-size: 13px;
                    line-height: 1.65;
                    color: black;
                    padding: 24px;
                    max-width: 900px;
                    margin: 0 auto;
                    font-weight: 300;
                }
                h1, .header {
                    color: black;
                    font-weight: 500;
                    font-size: 39px;
                    line-height: 1.25;
                    font-family: 'Poppins', sans-serif;
                    margin-bottom: 20px;
                }
                h2, h3, .sub-header {
                    color: black;
                    font-size: 26px;
                    line-height: 1.35;
                    font-family: 'Poppins', sans-serif;
                    font-weight: 600;
                    margin-top: 28px;
                    margin-bottom: 12px;
                }
                h4, h5, h6 {
                    color: black;
                    font-size: 18px;
                    font-family: 'Poppins', sans-serif;
                    font-weight: 600;
                    margin-top: 20px;
                    margin-bottom: 8px;
                }
                p, .text-content {
                    color: black;
                    font-size: 13px;
                    font-family: 'Inter', sans-serif;
                    line-height: 1.65;
                    margin-top: 14px;
                    margin-bottom: 14px;
                    font-weight: 300;
                }
                ul, ol {
                    color: black;
                    padding-left: 28px;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }
                li {
                    margin-bottom: 6px;
                    color: black;
                    font-size: 13px;
                    font-family: 'Inter', sans-serif;
                    line-height: 1.65;
                    font-weight: 300;
                }
                strong, b {
                    color: black;
                    font-weight: 600;
                }
                a {
                    color: black;
                    text-decoration: underline;
                }
            `,
            images_upload_handler: function (blobInfo, progress) {
                return new Promise(function(resolve, reject) {
                    var xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', "{{ route('admin.policies.upload_image') }}");
                    
                    var token = $('meta[name="csrf-token"]').attr('content');
                    if (token) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }

                    xhr.upload.onprogress = function (e) {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = function() {
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status);
                            return;
                        }
                        var json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.url !== 'string') {
                            reject('Invalid JSON response');
                            return;
                        }
                        resolve(json.url);
                    };

                    xhr.onerror = function() {
                        reject('Image upload failed due to network error.');
                    };

                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                });
            }
        };

        // Initialize for Privacy Policy
        tinymce.init(Object.assign({}, tinymceConfig, { selector: '#privacy_content' }));

        // Initialize for Terms & Conditions
        tinymce.init(Object.assign({}, tinymceConfig, { selector: '#terms_content' }));

        // Trigger save before form submit
        $('#form-privacy').on('submit', function() {
            if (tinymce.get('privacy_content')) {
                tinymce.get('privacy_content').save();
            }
        });

        $('#form-terms').on('submit', function() {
            if (tinymce.get('terms_content')) {
                tinymce.get('terms_content').save();
            }
        });

        // Tab sync with URL parameter
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var targetId = $(e.target).attr('href').replace('#', '');
            if (history.pushState) {
                var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + targetId;
                window.history.pushState({path: newUrl}, '', newUrl);
            }
        });
    });
</script>
@stop
