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
                            <label for="thumbnail"><strong>Cover / Thumbnail Image</strong> <small class="text-muted">(Optional)</small></label>
                            
                            <input type="hidden" id="remove_thumbnail" name="remove_thumbnail" value="0">

                            @if($item->thumbnail)
                                @php
                                    $thumbUrl = (str_starts_with($item->thumbnail, 'http') || str_starts_with($item->thumbnail, '/images') || str_starts_with($item->thumbnail, 'images/'))
                                        ? $item->thumbnail
                                        : asset('storage/' . $item->thumbnail);
                                @endphp
                                <div id="existing_thumbnail_box" class="mb-2">
                                    <div style="position: relative; display: inline-block;">
                                        <img src="{{ $thumbUrl }}" alt="Current Thumbnail"
                                            style="width: 120px; height: 180px; object-fit: cover; border: 1px solid #ddd; border-radius: 6px; display: block; box-shadow: 0 2px 8px rgba(0,0,0,0.12);">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeExistingThumbnail()"
                                            title="Remove this image"
                                            style="position: absolute; top: -10px; right: -10px; width: 28px; height: 28px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold; border: 2px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.3); z-index: 5; cursor: pointer;">
                                            &times;
                                        </button>
                                    </div>
                                    <p class="text-muted mt-1 mb-1"><small>Current cover poster. Click the &times; button to remove it or choose a new file below to replace it.</small></p>
                                </div>
                                <div id="thumbnail_removed_alert" class="alert alert-warning py-1 px-2 mb-2" style="display: none; max-width: 320px; font-size: 13px;">
                                    <i class="ft-info mr-1"></i> Cover image marked for removal.
                                    <button type="button" class="btn btn-link btn-sm p-0 text-primary font-weight-bold ml-2" onclick="undoRemoveExistingThumbnail()">Undo</button>
                                </div>
                            @endif

                            <div id="new_thumbnail_preview_wrap" class="mb-2" style="display: none;">
                                <div style="position: relative; display: inline-block;">
                                    <img id="new_thumbnail_preview" src="#" alt="New Preview"
                                        style="width: 120px; height: 180px; object-fit: cover; border: 2px solid #28d094; border-radius: 6px; display: block; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="clearNewThumbnail()"
                                        title="Remove selected image"
                                        style="position: absolute; top: -10px; right: -10px; width: 28px; height: 28px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold; border: 2px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.3); z-index: 5; cursor: pointer;">
                                        &times;
                                    </button>
                                </div>
                                <p class="text-success mt-1 mb-1"><small><i class="ft-check mr-1"></i>New image selected. Click &times; to cancel selection.</small></p>
                            </div>

                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*" onchange="previewNewThumbnail(this)">
                            <small class="form-text text-info"><i class="ft-info mr-1"></i><strong>Recommended Size:</strong> 9:16 vertical ratio (e.g., 600 × 1067 px or 1080 × 1920 px). Max 10MB. If no image is uploaded, the paused video player will be shown.</small>
                        </div>

                        <div class="form-group">
                            <label for="video_type"><strong>Video Source Type</strong> <span class="text-danger">*</span></label>
                            <select class="form-control" id="video_type" name="video_type" required onchange="toggleVideoTypeInputs()">
                                <option value="upload" {{ old('video_type', $item->video_type ?? 'upload') === 'upload' ? 'selected' : '' }}>Direct Video File Upload (MP4 / WebM)</option>
                                <option value="url" {{ old('video_type', $item->video_type) === 'url' ? 'selected' : '' }}>External Video URL (Direct MP4 link)</option>
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
                            <label for="video_url"><strong>Video Link URL</strong> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="video_url" name="video_url"
                                value="{{ old('video_url', $item->video_url) }}"
                                placeholder="https://youtube.com/shorts/... or https://example.com/video.mp4">
                            <small class="form-text text-muted">Paste the direct video link or YouTube embed/shorts link.</small>
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

    function removeExistingThumbnail() {
        document.getElementById('remove_thumbnail').value = '1';
        const existingBox = document.getElementById('existing_thumbnail_box');
        if (existingBox) existingBox.style.display = 'none';
        const removedAlert = document.getElementById('thumbnail_removed_alert');
        if (removedAlert) removedAlert.style.display = 'block';
    }

    function undoRemoveExistingThumbnail() {
        document.getElementById('remove_thumbnail').value = '0';
        const existingBox = document.getElementById('existing_thumbnail_box');
        if (existingBox) existingBox.style.display = 'block';
        const removedAlert = document.getElementById('thumbnail_removed_alert');
        if (removedAlert) removedAlert.style.display = 'none';
    }

    function previewNewThumbnail(input) {
        const wrap = document.getElementById('new_thumbnail_preview_wrap');
        const preview = document.getElementById('new_thumbnail_preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                wrap.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            wrap.style.display = 'none';
        }
    }

    function clearNewThumbnail() {
        const input = document.getElementById('thumbnail');
        if (input) input.value = '';
        const wrap = document.getElementById('new_thumbnail_preview_wrap');
        if (wrap) wrap.style.display = 'none';
        const preview = document.getElementById('new_thumbnail_preview');
        if (preview) preview.src = '#';
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleVideoTypeInputs();
    });
</script>
@endsection
