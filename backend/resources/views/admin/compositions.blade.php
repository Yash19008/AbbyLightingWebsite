@extends('admin.page')

@section('title', $title ?? 'Compositions')

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <div>
                <span class="d-flex align-items-center">
                    <h4 class="mb-0 text-primary font-weight-bold"><i class="ft-image mr-1"></i> Compositions</h4>
                </span>
            </div>
            <div class="d-flex">
                <a href="{{ route('composition_admin.add') }}" class="btn btn-primary mr-2 shadow-sm">
                    <i class="ft-plus mr-1"></i> Create Composition
                </a>
                <a href="{{ route('composition_categories_admin') }}" class="btn btn-secondary shadow-sm">
                    <i class="ft-grid mr-1"></i> Manage Categories
                </a>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')
@include('admin.include.notification')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table data-table table-striped table-bordered" data-order='[[ 0, "desc" ]]' id="compositions-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 100px" class="text-center">IMAGE</th>
                                    <th>TITLE</th>
                                    <th style="width: 150px" class="text-center">CATEGORY / ROOM</th>
                                    <th style="width: 160px" class="text-center">SHOWCASE IN INSPIRATION</th>
                                    <th style="width: 100px" class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $key => $row)
                                <tr class="data module-list" id="data-{{ $row->id }}">
                                    <td class="img-td text-center align-middle">
                                        @if($row->image)
                                            <img style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" src="{{ asset('storage/uploads/compositions/' . $row->image) }}" class="list-image-prof">
                                        @else
                                            <img style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" src="{{ asset('images/default.png') }}" class="list-image-prof">
                                        @endif
                                    </td>
                                    <td class="align-middle font-weight-bold">
                                        {{ $row->title ?: 'Untitled' }}
                                        @if($row->kicker)
                                            <br><small class="text-muted font-weight-normal">{{ $row->kicker }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-info text-uppercase shadow-sm" style="font-size: 11px; padding: 6px 10px; border-radius: 4px;">
                                            {{ $row->category_rel ? $row->category_rel->name : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch text-center">
                                            <input type="checkbox" class="custom-control-input knob switch" data-col="{{ Common_function::encrypt('is_showcase') }}" id="customSwitch2{{ $row->id }}" {{ $row->is_showcase ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitch2{{ $row->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center list-action actBtn-td align-middle">
                                        <a href="{{ route('composition_admin.edit', $row->id) }}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2"></i></a>
                                        <a href="javascript:;" class="delete-composition-btn btn btn-sm btn-outline-danger ml-1" data-id="{{ $row->id }}" data-toggle="tooltip" title="Delete"><i class="ft-trash"></i></a>
                                        <form id="delete-form-{{ $row->id }}" action="{{ route('composition_admin.delete') }}" method="POST" style="display: none;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $row->id }}">
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">IMAGE</th>
                                    <th>TITLE</th>
                                    <th class="text-center">CATEGORY / ROOM</th>
                                    <th class="text-center">SHOWCASE IN INSPIRATION</th>
                                    <th class="text-center">ACTION</th>
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
    $('#compositions-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'ACTION' && title !== 'IMAGE' && title !== 'SHOWCASE IN INSPIRATION') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#compositions-table').DataTable({
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[1, "asc"]],
        columnDefs: [
            { targets: [0, 3, 4], orderable: false }
        ]
    });

    table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change clear', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('.delete-composition-btn').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if(confirm('Are you sure you want to delete this composition?')) {
            $('#delete-form-' + id).submit();
        }
    });
});
</script>
@stop
