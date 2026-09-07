@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4><i class="ft-folder mr-2"></i>{{ $title }}</h4>
            <a href="{{ route('admin.blog-categories.add') }}" class="btn btn-premium">
                <i class="ft-plus mr-1"></i> Add Blog Category
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
                    <table class="table premium-table table-hover" id="blog-categories-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 80px;">Image</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th style="width: 100px;">Sort Order</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 120px;">Created Date</th>
                                <th style="width: 140px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset('uploads/blog_categories/' . $category->image) }}" alt="{{ $category->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #e0e0e0;">
                                    @else
                                        <span class="badge badge-light border">No image</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $category->name }}</strong>
                                </td>
                                <td>
                                    <code class="text-muted">{{ $category->slug }}</code>
                                </td>
                                <td>
                                    {{ Str::limit($category->description, 50, '...') ?: '-' }}
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $category->sort_order }}</span>
                                </td>
                                <td>
                                    @if($category->status == 'active')
                                        <span class="badge badge-premium-active">Active</span>
                                    @else
                                        <span class="badge badge-premium-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $category->created_at ? $category->created_at->format('d M, Y') : '-' }}</small>
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <div class="table-action-btns">
                                        <a href="{{ route('admin.blog-categories.edit', $category->id) }}" class="btn-action btn-action-edit" title="Edit Category">
                                            <i class="ft-edit-2"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.blog-categories.destroy', $category->id) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete category \'{{ $category->name }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="Delete Category">
                                                <i class="ft-trash-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="ft-inbox fa-2x mb-2 d-block"></i>
                                    No blog categories found. Click "Add Blog Category" to create one.
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
        if ($('#blog-categories-table tbody tr').length > 1 || !$('#blog-categories-table tbody tr td[colspan]').length) {
            $('#blog-categories-table').DataTable({
                "pageLength": 25,
                "order": [[5, "asc"]],
                "columnDefs": [
                    { "orderable": false, "targets": [1, 8] }
                ]
            });
        }
    });
</script>
@stop
