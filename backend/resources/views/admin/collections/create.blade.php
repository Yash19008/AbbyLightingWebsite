@extends('admin.page')

@section('title', 'Create Collection')

@php
    $main_module = 'Collections';
@endphp

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create New Collection</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
            <form action="{{ route('admin.collections.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Collection Details</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Collection Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="e.g., Symphony Collection" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="slug">URL Slug <small class="text-muted">(auto-generated)</small></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug') }}" 
                                           placeholder="Auto-generated from collection name">
                                    <small class="form-text text-muted">Will be used in URL: /collections/<strong id="slug-preview">your-slug</strong></small>
                                    @error('slug')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="short_description">Short Description</label>
                                    <input type="text" class="form-control @error('short_description') is-invalid @enderror" 
                                           id="short_description" name="short_description" value="{{ old('short_description') }}" 
                                           placeholder="Short description (optional)">
                                    @error('short_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">SEO Settings</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" 
                                           name="meta_title" value="{{ old('meta_title') }}" 
                                           placeholder="e.g., Symphony Collection | Abby Lighting">
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" 
                                              name="meta_description" rows="2" 
                                              placeholder="SEO description for search engines">{{ old('meta_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Status & Display</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active', false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active (Visible on Frontend)</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="show_in_menu" 
                                               name="show_in_menu" value="1" {{ old('show_in_menu', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_in_menu">Show in Menu</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="order">Display Order</label>
                                    <input type="number" class="form-control" id="order" 
                                           name="order" value="{{ old('order', 0) }}" min="0">
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('admin.collections.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Create Collection
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@section('extra_js')
<script>
// Auto-generate slug from name in real-time
$(document).ready(function() {
    let manualEdit = false;
    
    // Auto-generate slug as user types collection name
    $('#name').on('input', function() {
        // Only auto-generate if user hasn't manually edited slug
        if (!manualEdit) {
            const slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')  // Replace non-alphanumeric with dash
                .replace(/^-|-$/g, '');        // Remove leading/trailing dashes
            
            $('#slug').val(slug);
            $('#slug-preview').text(slug || 'your-slug');
        }
    });
    
    // If user manually edits slug, stop auto-generation
    $('#slug').on('input', function() {
        manualEdit = true;
        $('#slug-preview').text($(this).val() || 'your-slug');
    });
    
    // Update preview on page load if slug has value
    if ($('#slug').val()) {
        $('#slug-preview').text($('#slug').val());
    }
});
</script>
@endsection
@endsection
