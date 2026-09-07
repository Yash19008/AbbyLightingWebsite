@extends('admin.page')

@section('title', 'News Items')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header">News Items</div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.news-items.add') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add News Item
            </a>
        </div>

        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($newsItems as $item)
                                    <tr>
                                        <td>
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="width: 80px; height: 60px; object-fit: cover;">
                                            @else
                                                <div style="width: 80px; height: 60px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                                    <small>No image</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->subtitle ?? '-' }}</td>
                                        <td>
                                            @if($item->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.news-items.edit', $item->id) }}" class="btn btn-sm btn-info">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.news-items.delete', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this news item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No news items found. <a href="{{ route('admin.news-items.add') }}">Add your first news item</a></td>
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
@endsection
