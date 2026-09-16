@extends('admin.page')

@section('title', $title ?? 'Blog Articles')

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Blog Articles</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('admin.blogs.add') }}" class="buttons"><span>Add Blog Article</span></a>
                </span>
            </button>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('admin.include.notification')
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <table class="table data-table table-bordered" data-order='[[ 5, "asc" ]]' id="blogs-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">IMAGE</th>
                                    <th>TITLE</th>
                                    <th class="text-center" style="width: 140px;">CATEGORY</th>
                                    <th class="text-center" style="width: 100px;">FEATURED</th>
                                    <th class="text-center" style="width: 100px;">STATUS</th>
                                    <th class="text-center" style="width: 90px;">SEQUENCE</th>
                                    <th class="text-center" style="width: 130px; min-width: 130px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blogs as $blog)
                                <tr class="data module-list" id="data-{{ $blog->id }}">
                                    <td class="img-td text-center align-middle">
                                        @if($blog->featured_image)
                                            <img src="{{ asset('uploads/blogs/' . $blog->featured_image) }}" alt="{{ $blog->title }}" style="width: 75px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                        @else
                                            <div style="width: 75px; height: 50px; background: #f8f9fa; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin: 0 auto;">
                                                <small class="text-muted">No image</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <strong class="text-dark d-block">{{ $blog->title }}</strong>
                                        <small class="text-muted font-italic">/blogs/{{ $blog->slug }}</small>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($blog->category)
                                            <span class="badge badge-info">{{ $blog->category->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch text-center">
                                            <input type="checkbox" class="custom-control-input knob switch" data-col="{{ \App\Helpers\Common_function::encrypt('is_featured') }}" id="customSwitchFeatured{{ $blog->id }}" {{ $blog->is_featured ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitchFeatured{{ $blog->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch text-center">
                                            <input type="checkbox" class="custom-control-input knob switch" data-col="{{ \App\Helpers\Common_function::encrypt('status') }}" id="customSwitchStatus{{ $blog->id }}" {{ $blog->status === 'published' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitchStatus{{ $blog->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle font-weight-bold">{{ $blog->sort_order ?? 0 }}</td>
                                    <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="mx-1 text-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3"></i></a>
                                        <a href="/blogs/{{ $blog->slug }}" target="_blank" class="mx-1 text-info" data-toggle="tooltip" title="Preview"><i class="ft-eye font-medium-3"></i></a>
                                        <a href="javascript:;" class="delete-blog-btn mx-1 text-danger" data-id="{{ $blog->id }}" data-toggle="tooltip" title="Delete"><i class="icon ft-trash-2 font-medium-3"></i></a>
                                        <form id="delete-form-{{ $blog->id }}" action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Image</th>
                                    <th>Title</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Featured</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Sequence</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="hdn" value="{{ $tbl }}">
@stop

@section('extra_js')
<style>
   .dataTables_filter,
   .dataTables_info {
       display: none;
   }
   tfoot input {
       width: 100%;
       padding: 4px 8px;
       box-sizing: border-box;
       border: 1px solid #ced4da;
       border-radius: 4px;
       font-size: 13px;
   }
   table.dataTable thead th, table.dataTable tfoot th {
       font-size: 13px;
       font-weight: 600;
       letter-spacing: 0.5px;
       vertical-align: middle;
   }
   table.dataTable tbody td {
       vertical-align: middle;
       font-size: 14px;
   }
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('#blogs-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'Action' && title !== 'ACTION' && title !== 'Image' && title !== 'IMAGE') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#blogs-table').DataTable({
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[5, "asc"]],
        columnDefs: [
            { targets: [0, 6], orderable: false }
        ]
    });

    // Apply the per-column search
    table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change clear', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $(document).on('click', '.delete-blog-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this blog article?')) {
            $('#delete-form-' + id).submit();
        }
    });
});
</script>
@stop
