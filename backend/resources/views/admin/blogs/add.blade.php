@extends('admin.page')

@section('title', $title)

@section('extra_css')
<style>
    .form-group label {
        color: #2c2c2c;
        font-size: 13px;
    }
    .section-box {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .section-box-title {
        font-size: 16px;
        font-weight: 600;
        color: #1a202c;
        border-bottom: 1px solid #edf2f7;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }
</style>
@stop

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('admin.blogs.index') }}" class="text-muted small">
                    <i class="ft-arrow-left mr-1"></i> Back to Blog Articles
                </a>
                <h4 class="mt-1"><i class="ft-plus-circle mr-2"></i>{{ $title }}</h4>
            </div>
            <button type="submit" form="blog-form" class="btn btn-primary px-4">
                <i class="ft-save mr-1"></i> Publish / Save Article
            </button>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong class="d-block mb-1"><i class="ft-alert-triangle mr-1"></i> Please fix the following errors:</strong>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</div>
@stop

@section('content')
<form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blog-form">
    @csrf

    <div class="row">
        <div class="col-12 col-xl-9">

            {{-- 1. ARTICLE HEADER & METADATA --}}
            <div class="section-box">
                <div class="section-box-title">
                    <i class="ft-info mr-1 text-primary"></i> 1. Article Header &amp; Metadata
                </div>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label for="title" class="font-weight-bold">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Made in India, designed for the world" required value="{{ old('title') }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="category_id" class="font-weight-bold">Blog Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="slug" class="font-weight-bold">Slug (URL)</label>
                        <input type="text" name="slug" id="slug" class="form-control" placeholder="auto-generated from title if empty" value="{{ old('slug') }}">
                        <small class="form-text text-muted">URL identifier: <code>/blogs/your-slug</code></small>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="author" class="font-weight-bold">Author</label>
                        <input type="text" name="author" id="author" class="form-control" value="{{ old('author', 'Abby Studio') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="published_at" class="font-weight-bold">Published Date</label>
                        <input type="date" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', date('Y-m-d')) }}">
                    </div>

                    <div class="col-md-12 form-group">
                        <label for="dek" class="font-weight-bold">Dek (Summary / Sub-headline)</label>
                        <textarea name="dek" id="dek" rows="3" class="form-control" placeholder="Brief summary paragraph displayed directly under the title...">{{ old('dek') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. ARTICLE IMAGES --}}
            <div class="section-box">
                <div class="section-box-title">
                    <i class="ft-image mr-1 text-primary"></i> 2. Listing &amp; Detail Page Images
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="featured_image" class="font-weight-bold">Listing Image <span class="badge badge-info ml-1">Inspiration &amp; Blog Cards</span></label>
                        <input type="file" name="featured_image" id="featured_image" class="form-control-file" accept="image/*" onchange="previewImage(this, 'featured-preview')">
                        <small class="form-text text-muted">Thumbnail card image displayed on the Inspiration page and Blog listing. (Recommended: 600 × 400 px or 1200 × 800 px).</small>
                        <div class="mt-2" id="featured-preview" style="display: none;">
                            <img src="" style="max-height: 120px; border-radius: 4px; border: 1px solid #ddd; padding: 2px;">
                        </div>
                        <div class="mt-2">
                            <label for="featured_image_caption" class="small font-weight-bold">Listing Image Caption (Optional)</label>
                            <input type="text" name="featured_image_caption" id="featured_image_caption" class="form-control form-control-sm" placeholder="e.g. Card preview caption." value="{{ old('featured_image_caption') }}">
                        </div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="secondary_image" class="font-weight-bold">Detail Page Image <span class="badge badge-primary ml-1">Blog Details Banner</span></label>
                        <input type="file" name="secondary_image" id="secondary_image" class="form-control-file" accept="image/*" onchange="previewImage(this, 'secondary-preview')">
                        <small class="form-text text-muted">Large banner image displayed on the full Blog Details page (/blogs/slug). (Recommended: 1200 × 540 px).</small>
                        <div class="mt-2" id="secondary-preview" style="display: none;">
                            <img src="" style="max-height: 120px; border-radius: 4px; border: 1px solid #ddd; padding: 2px;">
                        </div>
                        <div class="mt-2">
                            <label for="secondary_image_caption" class="small font-weight-bold">Detail Image Caption (Optional)</label>
                            <input type="text" name="secondary_image_caption" id="secondary_image_caption" class="form-control form-control-sm" placeholder="e.g. Precision at every stage, from casting to the final beam of light." value="{{ old('secondary_image_caption') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. BODY CONTENT --}}
            <div class="section-box">
                <div class="section-box-title">
                    <i class="ft-edit-3 mr-1 text-primary"></i> 3. Body Content
                </div>
                <div class="form-group mb-0">
                    <label for="content" class="font-weight-bold">Article Story Body</label>
                    <textarea name="content" id="content" class="form-control">{{ old('content') }}</textarea>
                </div>
            </div>

            {{-- 4. PUBLISHING & SEO --}}
            <div class="section-box">
                <div class="section-box-title">
                    <i class="ft-settings mr-1 text-primary"></i> 4. Publishing &amp; SEO
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="status" class="font-weight-bold">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Live on site)</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="sort_order" class="font-weight-bold">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="meta_title" class="font-weight-bold">SEO Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Custom SEO title..." value="{{ old('meta_title') }}">
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="meta_description" class="font-weight-bold">SEO Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" class="form-control" placeholder="Search engine description...">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary px-4">
                    <i class="ft-x mr-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary px-5 btn-lg">
                    <i class="ft-save mr-1"></i> Publish / Save Article
                </button>
            </div>

        </div>
    </div>
</form>
@stop

@section('extra_js')
<!-- TinyMCE Advanced WYSIWYG Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
    $(document).ready(function() {
        tinymce.init({
            selector: '#content',
            height: 500,
            convert_urls: false,
            relative_urls: false,
            remove_script_host: false,
            plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons template help',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile image media template link anchor codesample | ltr rtl',
            menubar: 'file edit view insert format tools table help',
            font_family_formats: 'Poppins=Poppins,sans-serif; Inter=Inter,sans-serif',
            image_title: true,
            automatic_uploads: true,
            content_style: "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap'); body { font-family: 'Poppins', 'Inter', sans-serif; font-size:16px } img { max-width: 100%; height: auto; }",
            images_upload_handler: function (blobInfo, progress) {
                return new Promise(function(resolve, reject) {
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', "{{ route('admin.blogs.upload_image') }}");
                    
                    xhr.upload.onprogress = function (e) {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = function() {
                        var json;
                        if (xhr.status === 403) {
                            reject('HTTP Error: ' + xhr.status, { remove: true });
                            return;
                        }
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status);
                            return;
                        }

                        json = JSON.parse(xhr.responseText);

                        if (!json || (typeof json.url !== 'string' && typeof json.location !== 'string')) {
                            reject('Invalid JSON: ' + xhr.responseText);
                            return;
                        }

                        resolve(json.url || json.location);
                    };

                    xhr.onerror = function () {
                        reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                    };

                    formData = new FormData();
                    formData.append('upload', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', '{{ csrf_token() }}');

                    xhr.send(formData);
                });
            },
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });

        let isSlugManual = false;
        $('#slug').on('input', function() {
            isSlugManual = $(this).val().length > 0;
        });

        $('#title').on('input', function() {
            if (!isSlugManual) {
                let slug = $(this).val().toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/[\s-]+/g, '-');
                $('#slug').val(slug);
            }
        });
    });

    function previewImage(input, previewId) {
        const previewContainer = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.querySelector('img').src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.style.display = 'none';
            previewContainer.querySelector('img').src = '';
        }
    }
</script>
@stop
