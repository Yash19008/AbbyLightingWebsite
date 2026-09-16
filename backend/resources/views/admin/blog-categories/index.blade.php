@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12 col-md-6">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Blog Categories</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('admin.blog-categories.add') }}" class="buttons"><span>Add Blog Category</span></a>
                </span>
            </button>
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                <i class="ft-file-text mr-1"></i> All Blogs
            </a>
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
                        <table class="table data-table table-bordered" data-order='[[ 0, "asc" ]]' id="blog-categories-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 70px;">ORDER</th>
                                    <th class="text-center" style="width: 90px;">IMAGE</th>
                                    <th>NAME</th>
                                    <th>SLUG</th>
                                    <th>DESCRIPTION</th>
                                    <th class="text-center" style="width: 90px;">BLOGS</th>
                                    <th class="text-center" style="width: 100px;">STATUS</th>
                                    <th class="text-center" style="width: 120px;">CREATED AT</th>
                                    <th class="text-center" style="width: 110px; min-width: 110px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr class="data module-list" id="data-{{ $category->id }}">
                                    <td class="text-center align-middle font-weight-bold">{{ $category->sort_order ?? 0 }}</td>
                                    <td class="img-td text-center align-middle">
                                        @if($category->image)
                                            <img style="width:50px;height:45px;object-fit:cover;border-radius:4px;border:1px solid #eee;"
                                                src="{{ asset('uploads/blog_categories/' . $category->image) }}"
                                                class="list-image-prof" alt="{{ $category->name }}">
                                        @else
                                            <div style="width:50px;height:45px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border:1px solid #eee;border-radius:4px;margin:0 auto;color:#aaa;font-size:10px;">
                                                No Img
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle font-weight-bold text-dark">{{ $category->name }}</td>
                                    <td class="align-middle"><code class="font-weight-bold">{{ $category->slug }}</code></td>
                                    <td class="align-middle text-muted">{{ Str::limit($category->description, 50, '...') ?: '-' }}</td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-info" style="font-size: 11px; padding: 4px 8px;">{{ $category->blogs_count ?? 0 }} Blogs</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox"
                                                class="custom-control-input knob switch"
                                                data-col="{{ Common_function::encrypt('status') }}"
                                                id="customSwitch{{ $category->id }}"
                                                {{ $category->status === 'active' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitch{{ $category->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle text-muted">
                                        {{ $category->created_at ? $category->created_at->format('d M, Y') : '-' }}
                                    </td>
                                    <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                        <a href="{{ route('admin.blog-categories.edit', $category->id) }}" class="mx-1 text-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3"></i></a>
                                        <form action="{{ route('admin.blog-categories.destroy', $category->id) }}" method="POST" style="display:inline-block;" class="delete-form" onsubmit="return confirm('Are you sure you want to delete category \'{{ $category->name }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 mx-1 text-danger" data-toggle="tooltip" title="Delete" style="border:none;background:none;"><i class="icon ft-trash-2 font-medium-3"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Order</th>
                                    <th class="text-center">Image</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th class="text-center">Blogs</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Created At</th>
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
    $('#blog-categories-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'Action' && title !== 'ACTION' && title !== 'Image' && title !== 'IMAGE') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#blog-categories-table').DataTable({
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, "asc"]],
        columnDefs: [
            { targets: [1, 8], orderable: false }
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
});
</script>
@stop
