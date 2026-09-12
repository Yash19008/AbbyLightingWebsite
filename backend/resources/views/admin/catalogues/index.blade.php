@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4><i class="ft-book mr-2"></i>{{ $title }}</h4>
            <div>
                <a href="{{ route('admin.catalogue-categories.index') }}" class="btn btn-outline-secondary mr-2">
                    <i class="ft-grid mr-1"></i> Manage Categories
                </a>
                <a href="{{ route('admin.catalogues.add') }}" class="btn btn-premium">
                    <i class="ft-plus mr-1"></i> Add Catalogue
                </a>
            </div>
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
                    <table class="table premium-table table-hover" id="catalogues-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th style="width: 80px;">Cover</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>PDF & Size</th>
                                <th style="width: 110px; text-align: center;">Downloads</th>
                                <th style="width: 90px; text-align: center;">Featured</th>
                                <th style="width: 70px; text-align: center;">Sort</th>
                                <th style="width: 80px; text-align: center;">Status</th>
                                <th style="width: 140px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($catalogues as $cat)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($cat->cover_image)
                                        <img src="{{ asset('uploads/catalogues/images/' . $cat->cover_image) }}" alt="{{ $cat->title }}" style="width: 55px; height: 75px; object-fit: cover; border-radius: 4px; border: 1px solid #e0e0e0; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                                    @else
                                        <span class="badge badge-light border">No image</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark d-block">{{ $cat->title }}</strong>
                                    <small class="text-muted"><code>/{{ $cat->slug }}</code></small>
                                </td>
                                <td>
                                    @if($cat->category)
                                        <span class="badge badge-info" style="font-size: 11px; padding: 4px 8px; font-weight: 500;">
                                             {{ $cat->category->name }}
                                        </span>
                                    @else
                                        <span class="badge badge-light text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($cat->pdf_file)
                                        <a href="{{ asset('uploads/catalogues/pdfs/' . $cat->pdf_file) }}" target="_blank" class="text-danger font-weight-bold d-inline-flex align-items-center" style="gap: 4px;">
                                            <i class="ft-file-text"></i> PDF
                                        </a>
                                        <small class="text-muted d-block">({{ $cat->file_size ?? 'N/A' }})</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($cat->downloads_count > 0)
                                        <a href="{{ route('catalog_admin') }}" class="badge badge-success" style="font-size: 12px; padding: 5px 10px; font-weight: 700; text-decoration: none;" title="View {{ $cat->downloads_count }} download leads">
                                            <i class="ft-download mr-1"></i> {{ $cat->downloads_count }}
                                        </a>
                                    @else
                                        <span class="badge badge-light border text-muted" style="font-size: 11px; padding: 4px 8px;">0</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if($cat->is_featured)
                                        <span class="badge badge-warning" style="background-color: #f59e0b; color: #fff; font-size: 11px; padding: 4px 8px;">
                                            <i class="ft-star mr-1"></i> Featured
                                        </span>
                                    @else
                                        <span class="badge badge-light border text-muted" style="font-size: 11px;">Standard</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-secondary">{{ $cat->sort_order }}</span>
                                </td>
                                <td style="text-align: center;">
                                    @if($cat->status == 'active')
                                        <span class="badge badge-premium-active">Active</span>
                                    @else
                                        <span class="badge badge-premium-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <div class="table-action-btns">
                                        @if($cat->pdf_file)
                                        <a href="{{ asset('uploads/catalogues/pdfs/' . $cat->pdf_file) }}" target="_blank" class="btn-action btn-action-view" title="View PDF">
                                            <i class="ft-eye"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('admin.catalogues.edit', $cat->id) }}" class="btn-action btn-action-edit" title="Edit Catalogue">
                                            <i class="ft-edit-2"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.catalogues.destroy', $cat->id) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete \'{{ $cat->title }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="Delete Catalogue">
                                                <i class="ft-trash-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="ft-book fa-2x mb-2 d-block"></i>
                                    No catalogues found. Click "Add Catalogue" to upload one.
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
        if ($('#catalogues-table tbody tr').length > 1 || !$('#catalogues-table tbody tr td[colspan]').length) {
            $('#catalogues-table').DataTable({
                "pageLength": 25,
                "order": [[7, "asc"]],
                "columnDefs": [
                    { "orderable": false, "targets": [1, 4, 9] }
                ]
            });
        }
    });
</script>
@stop
