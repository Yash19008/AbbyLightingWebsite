@extends('admin.page')

@section('title', 'News Section Settings')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header">Homepage Settings - News Section</div>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.news-section.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="{{ old('title', $section->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="subtitle">Subtitle</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle" 
                                   value="{{ old('subtitle', $section->subtitle) }}">
                        </div>

                        <div class="form-group">
                            <label for="link">Link URL</label>
                            <input type="text" class="form-control" id="link" name="link" 
                                   value="{{ old('link', $section->link) }}" 
                                   placeholder="/news">
                            <small class="form-text text-muted">Enter the URL path (e.g., /news or https://example.com)</small>
                        </div>

                        <div class="form-group">
                            <label for="image">Background Image</label>
                            @if($section->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $section->image) }}" 
                                         alt="Current Image" 
                                         style="max-width: 300px; max-height: 200px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 1920x1080px. Max 5MB. Formats: JPG, PNG, WEBP</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" 
                                       name="is_active" {{ $section->is_active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active (Show on homepage)</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
