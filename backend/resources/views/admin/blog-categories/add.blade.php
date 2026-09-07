@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4><i class="ft-plus-circle mr-2"></i>{{ $title }}</h4>
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                <i class="ft-arrow-left mr-1"></i> Back to List
            </a>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="premium-card">
            <div class="premium-card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="ft-alert-circle mr-1"></i> Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('admin.blog-categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name" class="font-weight-bold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Trends, Guides, Behind the Scenes" required value="{{ old('name') }}">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="slug" class="font-weight-bold">Slug (URL)</label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="auto-generated from name if left empty" value="{{ old('slug') }}">
                            <small class="form-text text-muted">Unique identifier for URLs (e.g. <code>trends</code>, <code>behind-the-scenes</code>)</small>
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="description" class="font-weight-bold">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control" placeholder="Short description about this blog category...">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="status" class="font-weight-bold">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="sort_order" class="font-weight-bold">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                            <small class="form-text text-muted">Lower numbers appear first (e.g. 0, 1, 2...)</small>
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="image" class="font-weight-bold">Category Feature Image <small class="text-muted">(Optional)</small></label>
                            <input type="file" name="image" id="image" class="form-control-file" accept="image/*" onchange="previewImage(this, 'category-image-preview')">
                            <small class="form-text text-info"><i class="fa fa-info-circle"></i> Recommended size: 600 × 600 px or 800 × 500 px. Supports PNG, JPG, JPEG, WEBP.</small>
                            <div class="mt-2" id="category-image-preview" style="display: none;">
                                <img src="" style="max-height: 140px; border-radius: 4px; border: 1px solid #ddd; padding: 3px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions mt-3">
                        <button type="submit" class="btn btn-premium">
                            <i class="ft-save mr-1"></i> Save Category
                        </button>
                        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-light ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('extra_js')
<script>
    // Live slug generation from name if slug not manually edited
    let isSlugManual = false;
    $('#slug').on('input', function() {
        isSlugManual = $(this).val().length > 0;
    });

    $('#name').on('input', function() {
        if (!isSlugManual) {
            let slug = $(this).val().toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-');
            $('#slug').val(slug);
        }
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
