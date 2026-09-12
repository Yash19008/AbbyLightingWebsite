@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4><i class="ft-edit-2 mr-2"></i>{{ $title }}</h4>
            <a href="{{ route('admin.catalogue-categories.index') }}" class="btn btn-secondary">
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

                <form action="{{ route('admin.catalogue-categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name" class="font-weight-bold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $category->name) }}">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="slug" class="font-weight-bold">Slug (URL)</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $category->slug) }}">
                            <small class="form-text text-muted">Unique identifier for URLs & filters (e.g. <code>architectural</code>, <code>decorative</code>, <code>outdoor</code>)</small>
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="description" class="font-weight-bold">Description <small class="text-muted">(Optional)</small></label>
                            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $category->description) }}</textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="status" class="font-weight-bold">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="sort_order" class="font-weight-bold">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                            <small class="form-text text-muted">Lower numbers appear first on frontend tabs (e.g. 0, 1, 2...)</small>
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="image" class="font-weight-bold">Category Image / Icon <small class="text-muted">(Optional)</small></label>
                            <input type="file" name="image" id="image" class="form-control-file" accept="image/*" onchange="previewImage(this, 'category-image-preview')">
                            <small class="form-text text-info"><i class="fa fa-info-circle"></i> Recommended size: 600 × 600 px. Supports PNG, JPG, JPEG, WEBP.</small>
                            
                            <div class="mt-2" id="category-image-preview">
                                @if($category->image)
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">Current Image:</small>
                                        <img src="{{ asset('uploads/catalogue_categories/' . $category->image) }}" style="max-height: 140px; border-radius: 4px; border: 1px solid #ddd; padding: 3px;">
                                    </div>
                                @endif
                                <img src="" style="max-height: 140px; border-radius: 4px; border: 1px solid #ddd; padding: 3px; display: none;">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions mt-3">
                        <button type="submit" class="btn btn-premium">
                            <i class="ft-save mr-1"></i> Update Category
                        </button>
                        <a href="{{ route('admin.catalogue-categories.index') }}" class="btn btn-light ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('extra_js')
<script>
    function previewImage(input, previewId) {
        const previewContainer = document.getElementById(previewId);
        const newImg = previewContainer.querySelector('img:last-child') || previewContainer.querySelector('img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                newImg.src = e.target.result;
                newImg.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@stop
