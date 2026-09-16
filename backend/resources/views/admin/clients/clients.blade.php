@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12 col-md-6">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Clients</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('client_admin.add') }}" class="buttons"><span>Add Client</span></a>
                </span>
            </button>
        </div>
    </div>
    <div class="col-12 col-md-6 my-3 text-right" style="display:flex;">
        <form action="{{ route('client_admin.upload') }}" method="post" enctype="multipart/form-data" class="d-flex align-items-center" style='margin-left: auto;'>
            @csrf
            <span class="mr-2 font-small-3 font-weight-bold">Upload Banner:</span>
            <input type="file" name="banner_image" class="form-control form-control-sm d-inline mr-2" style="width: 200px;" required>
            <button class="btn btn-sm btn-primary">Save</button>
        </form>
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
                        <table class="table data-table table-bordered" data-order='[[ 0, "desc" ]]' id="clients-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">ID</th>
                                    <th class="text-center" style="width: 120px;">IMAGE</th>
                                    <th>FILE NAME</th>
                                    <th class="text-center" style="width: 140px;">CREATED AT</th>
                                    <th class="text-center" style="width: 120px; min-width: 120px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $key => $row)
                                <tr class="data module-list" id="data-{{ $row->id }}">
                                    <td class="text-center align-middle font-weight-bold">{{ $row->id }}</td>
                                    <td class="img-td text-center align-middle">
                                        <img style="width:75px;height:50px;object-fit:contain;border-radius:4px;border:1px solid #eee;background:#fff;padding:2px;"
                                            src="{{ $row->path ? asset('storage/uploads/clients/'.$row->path) : asset('images/default.png') }}"
                                            class="list-image-prof">
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-dark font-weight-bold">{{ $row->path }}</span>
                                    </td>
                                    <td class="text-center align-middle text-muted">
                                        {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d M, Y') : '-' }}
                                    </td>
                                    <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                        <a href="{{ route('client_admin.edit', $row->id) }}" class="mx-1 text-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3"></i></a>
                                        <a href="javascript:;" class="delete mx-1 text-danger" data-module="{{ @$main_module }}" data-toggle="tooltip" title="Delete"><i class="icon ft-trash-2 font-medium-3"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Image</th>
                                    <th>File Name</th>
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
    $('#clients-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'Action' && title !== 'ACTION' && title !== 'Image' && title !== 'IMAGE') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#clients-table').DataTable({
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, "desc"]],
        columnDefs: [
            { targets: [1, 4], orderable: false }
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