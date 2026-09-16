@extends('admin.page')

@section('title', $title ?? 'Catalogue Categories')

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Catalogue Categories</h4>
                </span>
            </div>
            <a href="{{ route('admin.catalogues.index') }}" class="btn btn-outline-secondary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-book-open mr-1"></i> All Catalogues
                </span>
            </a>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('admin.catalogue-categories.add') }}" class="buttons"><span>Add Category</span></a>
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
                        <table class="table data-table table-bordered" data-order='[[ 4, "asc" ]]' id="catalogue-categories-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 70px;">IMAGE</th>
                                    <th>NAME</th>
                                    <th>SLUG</th>
                                    <th class="text-center" style="width: 100px;">STATUS</th>
                                    <th class="text-center" style="width: 90px;">SEQUENCE</th>
                                    <th class="text-center" style="width: 120px; min-width: 120px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr class="data module-list" id="data-{{ $category->id }}">
                                    <td class="img-td text-center align-middle">
                                        @if($category->image)
                                            <img src="{{ asset('uploads/catalogue_categories/' . $category->image) }}" alt="{{ $category->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                        @else
                                            <div style="width: 50px; height: 50px; background: #f8f9fa; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin: 0 auto;">
                                                <small class="text-muted">No image</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle font-weight-bold">
                                        {{ $category->name }}
                                    </td>
                                    <td class="align-middle">
                                        <code>{{ $category->slug }}</code>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch text-center">
                                            <input type="checkbox" class="custom-control-input knob switch" data-col="{{ \App\Helpers\Common_function::encrypt('status') }}" id="customSwitchStatus{{ $category->id }}" {{ $category->status === 'active' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitchStatus{{ $category->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle font-weight-bold">{{ $category->sort_order ?? 0 }}</td>
                                    <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                        <a href="{{ route('admin.catalogue-categories.edit', $category->id) }}" class="mx-1 text-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3"></i></a>
                                        <a href="javascript:;" class="delete-cat-btn mx-1 text-danger" data-id="{{ $category->id }}" data-toggle="tooltip" title="Delete"><i class="icon ft-trash-2 font-medium-3"></i></a>
                                        <form id="delete-form-{{ $category->id }}" action="{{ route('admin.catalogue-categories.destroy', $category->id) }}" method="POST" style="display: none;">
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
                                    <th>Name</th>
                                    <th>Slug</th>
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
    $('#catalogue-categories-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'Action' && title !== 'ACTION' && title !== 'Image' && title !== 'IMAGE') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#catalogue-categories-table').DataTable({
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[4, "asc"]],
        columnDefs: [
            { targets: [0, 5], orderable: false }
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

    $(document).on('click', '.delete-cat-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this catalogue category?')) {
            $('#delete-form-' + id).submit();
        }
    });
});
</script>
@stop
