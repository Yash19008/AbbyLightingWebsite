@extends('admin.page')

@section('title', $title ?? 'Collections')

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Collections</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('admin.collections.create') }}" class="buttons"><span>Add Collection</span></a>
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
                        <table class="table data-table table-bordered" data-order='[[ 4, "asc" ]]' id="collections-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>SLUG</th>
                                    <th class="text-center" style="width: 150px;">SECTIONS</th>
                                    <th class="text-center" style="width: 100px;">STATUS</th>
                                    <th class="text-center" style="width: 90px;">SEQUENCE</th>
                                    <th class="text-center" style="width: 130px; min-width: 130px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collections as $collection)
                                @php
                                    $active_sections = 0;
                                    if ($collection->heroSection && ($collection->heroSection->is_active ?? true)) $active_sections++;
                                    if ($collection->parametersSection && ($collection->parametersSection->is_active ?? true)) $active_sections++;
                                    if ($collection->compositionsSection && ($collection->compositionsSection->is_active ?? true)) $active_sections++;
                                    if ($collection->tonesSection && ($collection->tonesSection->is_active ?? true)) $active_sections++;
                                    if ($collection->placesSection && ($collection->placesSection->is_active ?? true)) $active_sections++;
                                    if ($collection->spreadDropSection && ($collection->spreadDropSection->is_active ?? true)) $active_sections++;
                                @endphp
                                <tr class="data module-list" id="data-{{ $collection->id }}">
                                    <td class="align-middle font-weight-bold">
                                        {{ $collection->name }}
                                    </td>
                                    <td class="align-middle">
                                        <a href="/collections/{{ $collection->slug }}" target="_blank" class="text-primary font-weight-bold" title="View Collection">/collections/{{ $collection->slug }}</a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-pill badge-info px-2 py-1" style="font-size: 11px; font-weight: 500;">
                                            <i class="ft-layers mr-1"></i> {{ $active_sections }} / 6 Active
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch text-center">
                                            <input type="checkbox" class="custom-control-input knob switch" data-col="{{ \App\Helpers\Common_function::encrypt('is_active') }}" id="customSwitchActive{{ $collection->id }}" {{ $collection->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitchActive{{ $collection->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle font-weight-bold">{{ $collection->order ?? 0 }}</td>
                                    <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                        <a href="{{ route('admin.collections.edit', $collection->slug) }}" class="mx-1 text-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3"></i></a>
                                        <a href="/collections/{{ $collection->slug }}" target="_blank" class="mx-1 text-info" data-toggle="tooltip" title="Preview"><i class="ft-eye font-medium-3"></i></a>
                                        <a href="javascript:;" class="delete-collection-btn mx-1 text-danger" data-slug="{{ $collection->slug }}" data-toggle="tooltip" title="Delete"><i class="icon ft-trash-2 font-medium-3"></i></a>
                                        <form id="delete-form-{{ $collection->slug }}" action="{{ route('admin.collections.destroy', $collection->slug) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th class="text-center">Sections</th>
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
    $('#collections-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'Action' && title !== 'ACTION' && title !== 'Sections' && title !== 'SECTIONS') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#collections-table').DataTable({
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[4, "asc"]],
        columnDefs: [
            { targets: [2, 5], orderable: false }
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

    $(document).on('click', '.delete-collection-btn', function(e) {
        e.preventDefault();
        var slug = $(this).data('slug');
        if (confirm('Are you sure you want to delete this collection?')) {
            $('#delete-form-' + slug).submit();
        }
    });
});
</script>
@stop