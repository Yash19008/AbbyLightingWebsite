@extends('admin.page')
@section('title', 'Edit Category')
@php $main_module = 'Decorative Product'; @endphp
@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Edit Category: {{ $category->name }}</h3>
        </div>
        <div class="content-header-right text-md-right col-md-6 col-12">
            <a href="{{ route('decorative_category_admin') }}" class="btn btn-outline-secondary">
                <i class="ft-arrow-left"></i> Back to Categories
            </a>
        </div>
    </div>
    
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('decorative_category_admin.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $category->name) }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control" required value="{{ old('slug', $category->slug) }}">
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary"><i class="ft-check"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
$(document).ready(function() {
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
});
</script>
@endsection
