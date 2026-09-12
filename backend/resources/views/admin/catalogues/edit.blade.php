@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4><i class="ft-edit-2 mr-2"></i>{{ $title }}</h4>
            <a href="{{ route('admin.catalogues.index') }}" class="btn btn-secondary">
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

                <form action="{{ route('admin.catalogues.update', $catalogue->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="catalogue_category_id" class="font-weight-bold">Catalogue Category <span class="text-danger">*</span></label>
                            <select name="catalogue_category_id" id="catalogue_category_id" class="form-control" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('catalogue_category_id', $catalogue->catalogue_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="title" class="font-weight-bold">Catalogue Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Architectural Master Catalogue" required value="{{ old('title', $catalogue->title) }}">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="slug" class="font-weight-bold">Slug (URL)</label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="auto-generated from title if empty" value="{{ old('slug', $catalogue->slug) }}">
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="sort_order" class="font-weight-bold">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $catalogue->sort_order) }}" min="0">
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="status" class="font-weight-bold">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ old('status', $catalogue->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $catalogue->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="description" class="font-weight-bold">Description <small class="text-muted">(Optional)</small></label>
                            <textarea name="description" id="description" rows="3" class="form-control" placeholder="Brief overview or description of this catalogue...">{{ old('description', $catalogue->description) }}</textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="cover_image" class="font-weight-bold">Cover Image</label>
                            <input type="file" name="cover_image" id="cover_image" class="form-control-file" accept="image/*" onchange="previewImage(this, 'catalogue-cover-preview')">
                            <small class="form-text text-muted">Leave empty to keep existing image. Recommended vertical ratio (e.g. 600 × 800 px).</small>
                            <div class="mt-2" id="catalogue-cover-preview">
                                @if($catalogue->cover_image)
                                    <img src="{{ asset('uploads/catalogues/images/' . $catalogue->cover_image) }}" style="max-height: 180px; border-radius: 4px; border: 1px solid #ddd; padding: 3px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                @else
                                    <img src="" style="max-height: 180px; display: none; border-radius: 4px; border: 1px solid #ddd; padding: 3px;">
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="pdf_file" class="font-weight-bold">PDF Document File</label>
                            <input type="file" name="pdf_file" id="pdf_file" class="form-control-file" accept="application/pdf">
                            <small class="form-text text-muted">Leave empty to keep current PDF file.</small>
                            @if($catalogue->pdf_file)
                                <div class="mt-2 p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="ft-file-text text-danger mr-1"></i>
                                        <strong>Current File:</strong> {{ $catalogue->pdf_file }}
                                        <small class="text-muted">({{ $catalogue->file_size ?? 'Unknown size' }})</small>
                                    </span>
                                    <a href="{{ asset('uploads/catalogues/pdfs/' . $catalogue->pdf_file) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="ft-external-link"></i> View
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12 form-group mt-2">
                            <div class="custom-control custom-checkbox p-2 border rounded bg-light" style="max-width: 380px;">
                                <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $catalogue->is_featured) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold text-dark" for="is_featured" style="cursor: pointer;">
                                    <i class="ft-star text-warning mr-1"></i> Mark as Featured Catalogue
                                </label>
                                <small class="d-block text-muted pl-4">Highlighted as prominent item on the downloads page</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-premium">
                            <i class="ft-save mr-1"></i> Update Catalogue
                        </button>
                        <a href="{{ route('admin.catalogues.index') }}" class="btn btn-light ml-2">Cancel</a>
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
        const img = previewContainer.querySelector('img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@stop
