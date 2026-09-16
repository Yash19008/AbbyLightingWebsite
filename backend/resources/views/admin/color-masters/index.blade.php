@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12 col-md-6">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Color Masters</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('color_master_admin.add') }}" class="buttons"><span>Add New Color</span></a>
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
                        <table class="table data-table table-bordered" data-order='[[ 0, "asc" ]]' id="colors-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">ORDER</th>
                                    <th class="text-center" style="width: 100px;">PREVIEW</th>
                                    <th>NAME</th>
                                    <th>CODE</th>
                                    <th class="text-center" style="width: 100px;">TYPE</th>
                                    <th>CATEGORY</th>
                                    <th class="text-center" style="width: 100px;">STATUS</th>
                                    <th class="text-center" style="width: 130px; min-width: 130px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($colors as $color)
                                <tr class="data module-list" id="data-{{ $color->id }}">
                                    <td class="text-center align-middle font-weight-bold">{{ $color->order ?? 0 }}</td>
                                    <td class="text-center align-middle">
                                        <div style="
                                            width: 50px; 
                                            height: 32px; 
                                            background: {{ $color->css_value }}; 
                                            border: 1px solid #ced4da; 
                                            border-radius: 4px;
                                            margin: 0 auto;
                                            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                                        " title="{{ $color->type === 'solid' ? $color->hex_code : ($color->gradient_start.' -> '.$color->gradient_end) }}"></div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-dark font-weight-bold">{{ $color->name }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <code class="font-weight-bold">{{ $color->code }}</code>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($color->type === 'solid')
                                            <span class="badge badge-info" style="font-size: 11px; text-transform: uppercase;">Solid</span>
                                        @else
                                            <span class="badge badge-primary" style="font-size: 11px; text-transform: uppercase;">Gradient</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-muted">
                                        {{ $color->category ?: '-' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox"
                                                class="custom-control-input knob switch"
                                                data-col="{{ Common_function::encrypt('is_active') }}"
                                                id="customSwitch{{ $color->id }}"
                                                {{ $color->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitch{{ $color->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                        <a href="{{ route('color_master_admin.information', $color->id) }}" class="mx-1 text-info" data-toggle="tooltip" title="View Details"><i class="ft-eye font-medium-3"></i></a>
                                        <a href="{{ route('color_master_admin.edit', $color->id) }}" class="mx-1 text-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3"></i></a>
                                        <form action="{{ route('color_master_admin.delete', $color->id) }}" method="POST" style="display:inline-block;" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this color?');">
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
                                    <th class="text-center">Preview</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th class="text-center">Type</th>
                                    <th>Category</th>
                                    <th class="text-center">Status</th>
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
    $('#colors-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'Action' && title !== 'ACTION' && title !== 'Preview' && title !== 'PREVIEW') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#colors-table').DataTable({
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, "asc"]],
        columnDefs: [
            { targets: [1, 7], orderable: false }
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
