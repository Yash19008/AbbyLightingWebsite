@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4><i class="ft-file-text mr-2"></i>{{ $title }}</h4>
            <a href="{{ route('admin.blogs.add') }}" class="btn btn-premium">
                <i class="ft-plus mr-1"></i> Add Blog Article
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
<div class="row">
    <div class="col-12">
        <div class="premium-card">
            <div class="premium-card-body">
                <div class="table-responsive">
                    <table class="table premium-table table-hover" id="blogs-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th style="width: 100px;">Listing Image</th>
                                <th>Title &amp; Slug</th>
                                <th>Category</th>
                            
                                
                                <th style="width: 90px;">Status</th>
                                <th style="width: 140px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blogs as $blog)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($blog->featured_image)
                                        <img src="{{ asset('uploads/blogs/' . $blog->featured_image) }}" alt="{{ $blog->title }}" style="width: 60px; height: 42px; object-fit: cover; border-radius: 4px; border: 1px solid #e0e0e0;">
                                    @else
                                        <span class="badge badge-light border">No image</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark d-block">{{ $blog->title }}</strong>
                                    <small class="text-muted font-italic">/blogs/{{ $blog->slug }}</small>
                                </td>
                                <td>
                                    @if($blog->category)
                                        <span class="badge badge-info">{{ $blog->category->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                               
                              
                                <td>
                                    @if($blog->status == 'published')
                                        <span class="badge badge-premium-active">Published</span>
                                    @else
                                        <span class="badge badge-premium-inactive">Draft</span>
                                    @endif
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <div class="table-action-btns">
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn-action btn-action-edit" title="Edit Article">
                                            <i class="ft-edit-2"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete \'{{ $blog->title }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="Delete Article">
                                                <i class="ft-trash-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="ft-inbox fa-2x mb-2 d-block"></i>
                                    No blog articles found. Click "Add Blog Article" to create your first story.
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
@stop

@section('extra_js')
<script>
    $(document).ready(function() {
        if ($('#blogs-table tbody tr').length > 1 || !$('#blogs-table tbody tr td[colspan]').length) {
            $('#blogs-table').DataTable({
                "pageLength": 25,
                "columnDefs": [
                    { "orderable": false, "targets": [1, 7] }
                ]
            });
        }
    });
</script>
@stop
