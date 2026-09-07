@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4><i class="ft-video mr-2"></i>{{ $title }}</h4>
            <a href="{{ route('admin.watch_and_shops.add') }}" class="btn btn-primary">
                <i class="ft-plus mr-1"></i> Add Video / Reel
            </a>
        </div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ft-check mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ft-alert-triangle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</div>
@stop

@section('content')
<style>
    #reels-table th,
    #reels-table td {
        vertical-align: middle !important;
    }
    #reels-table th {
        white-space: nowrap;
        font-weight: 600;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" id="reels-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;">#</th>
                                    <th style="width: 80px; text-align: center;">Thumbnail</th>
                                    <th>Title / Caption</th>
                                    <th style="width: 110px;">Type</th>
                                    <th>Video Source</th>
                                    <th style="width: 70px; text-align: center;">Order</th>
                                    <th style="width: 90px; text-align: center;">Status</th>
                                    <th style="width: 130px; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                <tr>
                                    <td style="text-align: center; font-weight: 600; color: #777;">{{ $loop->iteration }}</td>
                                    <td style="text-align: center;">
                                        @php
                                            $thumbUrl = (str_starts_with($item->thumbnail, 'http') || str_starts_with($item->thumbnail, '/images') || str_starts_with($item->thumbnail, 'images/'))
                                                ? $item->thumbnail
                                                : asset('storage/' . $item->thumbnail);
                                        @endphp
                                        <img src="{{ $thumbUrl }}" alt="{{ $item->title }}" style="width: 44px; height: 58px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; display: inline-block; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                    </td>
                                    <td>
                                        <strong class="text-dark d-block">{{ $item->title ?: 'Untitled Reel' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->video_type === 'upload' ? 'success' : ($item->video_type === 'instagram' ? 'warning' : 'info') }}" style="font-size: 11px; padding: 4px 8px;">
                                            {{ ucfirst($item->video_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->video_type === 'upload')
                                            <small class="text-muted d-block text-truncate" style="max-width: 180px;">
                                                <i class="ft-file mr-1"></i>{{ basename($item->video_url) }}
                                            </small>
                                        @else
                                            <a href="{{ $item->video_url }}" target="_blank" class="text-primary small d-block text-truncate" style="max-width: 180px;" title="{{ $item->video_url }}">
                                                <i class="ft-external-link mr-1"></i>{{ $item->video_url }}
                                            </a>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge badge-secondary" style="font-size: 11px; padding: 3px 7px;">{{ $item->display_order }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if($item->is_active)
                                            <span class="badge badge-success" style="font-size: 11px; padding: 4px 8px;">Active</span>
                                        @else
                                            <span class="badge badge-secondary" style="font-size: 11px; padding: 4px 8px;">Inactive</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        <a href="{{ route('admin.watch_and_shops.edit', $item->id) }}" class="btn btn-sm btn-info py-1 px-2" title="Edit">
                                            <i class="ft-edit-2"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.watch_and_shops.delete', $item->id) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this reel?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger py-1 px-2" title="Delete">
                                                <i class="ft-trash-2"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="ft-film fa-2x mb-2 d-block"></i>
                                        No videos or reels found. Click "Add Video / Reel" to add your first one.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
