@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Home Sliders</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('homeslider_admin.add') }}" class="buttons"><span>Add Banner</span></a>
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
                        <table class="table data-table table-bordered" data-order='[[ 4, "asc" ]]' id="homeslider-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 90px;">IMAGE</th>
                                    <th>HEADING</th>
                                    <th>DESCRIPTION</th>
                                    <th class="text-center" style="width: 100px;">STATUS</th>
                                    <th class="text-center" style="width: 90px;">SEQUENCE</th>
                                    <th class="text-center" style="width: 100px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $key => $row)
                                <tr class="data module-list" id="data-{{ $row->id }}">
                                    <td class="img-td text-center align-middle">
                                        <img style="width:75px;height:50px;object-fit:cover;border-radius:4px;border:1px solid #eee;" src="/storage/{{ $row->path }}" class="list-image-prof">
                                    </td>
                                    <td class="align-middle">
                                        @if(!empty($row->heading) || !empty($row->heading_highlight))
                                            <strong>{{ $row->heading }}</strong>
                                            @if(!empty($row->heading_highlight))
                                                <em style="color:#b8860b;font-family:serif;">{{ $row->heading_highlight }}</em>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if(!empty($row->description))
                                            <span title="{{ $row->description }}">{{ \Illuminate\Support\Str::limit($row->description, 65) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch text-center">
                                            <input type="checkbox" class="custom-control-input knob switch" data-col="{{ \App\Helpers\Common_function::encrypt('is_active') }}" id="customSwitchActive{{ $row->id }}" {{ $row->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitchActive{{ $row->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">{{ $row->sort_order ?? 0 }}</td>
                                    <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                        <a href="{{ route('homeslider_admin.edit', $row->id) }}" class="mx-1 text-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3"></i></a>
                                        <a href="javascript:;" class="delete mx-1 text-danger" data-module="{{ @$main_module }}" data-toggle="tooltip" title="Delete"><i class="icon ft-trash-2 font-medium-3"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Image</th>
                                    <th>Heading</th>
                                    <th>Description</th>
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
    $('#homeslider-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'Action' && title !== 'ACTION' && title !== 'Image' && title !== 'IMAGE') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#homeslider-table').DataTable({
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
});
</script>
@stop