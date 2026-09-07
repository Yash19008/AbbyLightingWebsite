@extends('admin.page')

@section('title', $title)

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12 col-lg-9">
        <div class="content-header mb-3">
            <h4><i class="ft-video mr-2"></i>{{ $title }}</h4>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($method === 'Edit')
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="title"><strong>Title / Caption</strong></label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title', $item->title) }}"
                                placeholder="e.g. Architectural Track Lights Showcase">
                            <small class="form-text text-muted">Short descriptive title for this reel (optional).</small>
                        </div>

                        <div class="form-group">
                            <label for="thumbnail"><strong>Cover / Thumbnail Image</strong> <span class="text-danger">*</span></label>
                            @if($item->thumbnail)
                                @php
                                    $thumbUrl = (str_starts_with($item->thumbnail, 'http') || str_starts_with($item->thumbnail, '/images') || str_starts_with($item->thumbnail, 'images/'))
                                        ? $item->thumbnail
                                        : asset('storage/' . $item->thumbnail);
                                @endphp
                                <div class="mb-2">
                                    <img src="{{ $thumbUrl }}" alt="Current Thumbnail"
                                        style="width: 120px; height: 180px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                    <p class="text-muted mt-1"><small>Current cover poster. Upload a new image to replace it.</small></p>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*" {{ $method === 'Add' ? 'required' : '' }}>
                            <small class="form-text text-info"><i class="ft-info mr-1"></i><strong>Recommended Size:</strong> 9:16 vertical ratio (e.g., 600 × 1067 px or 1080 × 1920 px). Max 10MB.</small>
                        </div>

                        <div class="form-group">
                            <label for="video_type"><strong>Video Source Type</strong> <span class="text-danger">*</span></label>
                            <select class="form-control" id="video_type" name="video_type" required onchange="toggleVideoTypeInputs()">
                                <option value="upload" {{ old('video_type', $item->video_type ?? 'upload') === 'upload' ? 'selected' : '' }}>Direct Video File Upload (MP4 / WebM)</option>
                                <option value="url" {{ old('video_type', $item->video_type) === 'url' ? 'selected' : '' }}>External Video URL (Direct MP4 link)</option>
                                <option value="instagram" {{ old('video_type', $item->video_type) === 'instagram' ? 'selected' : '' }}>Instagram Reel URL</option>
                                <option value="youtube" {{ old('video_type', $item->video_type) === 'youtube' ? 'selected' : '' }}>YouTube Shorts / Video URL</option>
                            </select>
                        </div>

                        <div class="form-group" id="video_file_group">
                            <label for="video_file"><strong>Upload Video File</strong> <span class="text-danger" id="video_file_required">*</span></label>
                            @if($item->video_type === 'upload' && $item->video_url)
                                <div class="mb-2">
                                    <video src="{{ asset('storage/' . $item->video_url) }}" controls style="max-height: 220px; border-radius: 4px; background: #000;"></video>
                                    <p class="text-muted mt-1"><small>Current video file. Upload a new one to replace.</small></p>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="video_file" name="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime">
                            <small class="form-text text-muted">Supported formats: MP4, WebM, MOV. Max size: 100MB.</small>
                        </div>

                        <div class="form-group" id="video_url_group" style="display: none;">
                            <label for="video_url"><strong>Video / Reel Link URL</strong> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="video_url" name="video_url"
                                value="{{ old('video_url', $item->video_url) }}"
                                placeholder="https://www.instagram.com/reel/... or https://youtube.com/shorts/...">
                            <small class="form-text text-muted">Paste the direct video link, Instagram reel link, or YouTube embed link.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="display_order">Display Order / Sequence</label>
                                <input type="number" class="form-control" id="display_order" name="display_order"
                                    value="{{ old('display_order', $item->display_order ?? 0) }}" style="max-width: 150px;">
                                <small class="form-text text-muted">Lower numbers appear first in the horizontal slider.</small>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="d-block">Status</label>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="is_active">Active (Visible on Inspiration page)</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ft-save mr-1"></i> {{ $method === 'Edit' ? 'Update Video' : 'Save Video' }}
                            </button>
                            <a href="{{ route('admin.watch_and_shops.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
    function toggleVideoTypeInputs() {
        const videoType = document.getElementById('video_type').value;
        const fileGroup = document.getElementById('video_file_group');
        const urlGroup = document.getElementById('video_url_group');
        const fileRequired = document.getElementById('video_file_required');

        if (videoType === 'upload') {
            fileGroup.style.display = 'block';
            urlGroup.style.display = 'none';
            if (fileRequired) fileRequired.style.display = '{{ $method === "Add" ? "inline" : "none" }}';
        } else {
            fileGroup.style.display = 'none';
            urlGroup.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleVideoTypeInputs();
    });
</script>
@endsection
