@extends('admin.page')

@section('title', 'Edit News Item')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header">Edit News Item</div>
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.news-items.update', $newsItem->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $newsItem->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="subtitle">Subtitle</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $newsItem->subtitle) }}">
                        </div>

                        <div class="form-group">
                            <label for="link">Link URL</label>
                            <input type="text" class="form-control" id="link" name="link" value="{{ old('link', $newsItem->link) }}" placeholder="https://example.com/article">
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            @if($newsItem->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $newsItem->image) }}" alt="Current Image" style="max-width: 300px; max-height: 200px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 800x600px. Max 5MB.</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ $newsItem->is_active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update News Item
                            </button>
                            <a href="{{ route('admin.news-items.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
