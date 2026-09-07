@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3">
            <h4>{{ $title }}</h4>
            <a href="{{ route('decorative_category_admin') }}" class="btn btn-secondary mt-2">Back to List</a>
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
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('decorative_category_admin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">Name *</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="slug">Slug (Optional - auto generated if empty)</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="parent_id">Parent Category</label>
                            <select name="parent_id" class="form-control">
                                <option value="">None (Main Category)</option>
                                @foreach($parent_categories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="show_in_mega_dropdown">Show in Mega Dropdown</label>
                            <select name="show_in_mega_dropdown" class="form-control">
                                <option value="1" {{ old('show_in_mega_dropdown', '1') == '1' ? 'selected' : '' }}>Yes (Show in Mega Dropdown)</option>
                                <option value="0" {{ old('show_in_mega_dropdown') == '0' ? 'selected' : '' }}>No (Hide from Dropdown)</option>
                            </select>
                            <small class="form-text text-muted">Choose whether this category appears in the main navigation menu</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="image">Category Image <small class="text-primary font-weight-bold">(Recommended: 600×600px Square or 600×750px Portrait)</small></label>
                            <input type="file" name="image" class="form-control-file" accept="image/*" onchange="previewImage(this, 'category-image-preview')">
                            <small class="form-text text-info"><i class="fa fa-info-circle"></i> <strong>Recommended Size:</strong> 600 × 600 px (1:1) or 600 × 750 px (4:5). Max 5MB.</small>
                            <div class="mt-2" id="category-image-preview" style="display: none;">
                                <img src="" style="max-height: 150px; border: 1px solid #ccc; padding: 2px;">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-premium mt-3">Save Category</button>
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
