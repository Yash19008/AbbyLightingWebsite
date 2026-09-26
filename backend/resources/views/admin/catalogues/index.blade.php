@extends('admin.page')

@section('title', $title ?? 'Catalogues')

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Catalogues</h4>
                </span>
            </div>
            <a href="{{ route('admin.catalogue-categories.index') }}" class="btn btn-outline-secondary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-grid mr-1"></i> Manage Categories
                </span>
            </a>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('admin.catalogues.add') }}" class="buttons"><span>Add Catalogue</span></a>
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
                        <table class="table data-table table-bordered" data-order='[[ 7, "asc" ]]' id="catalogues-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 70px;">COVER</th>
                                    <th>TITLE</th>
                                    <th class="text-center" style="width: 130px;">CATEGORY</th>
                                    <th class="text-center" style="width: 100px;">PDF</th>
                                    <th class="text-center" style="width: 90px;">LEADS</th>
                                    <th class="text-center" style="width: 90px;">FEATURED</th>
                                    <th class="text-center" style="width: 90px;">STATUS</th>
                                    <th class="text-center" style="width: 80px;">SEQUENCE</th>
                                    <th class="text-center" style="width: 130px; min-width: 130px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($catalogues as $cat)
                                <tr class="data module-list" id="data-{{ $cat->id }}">
                                    <td class="img-td text-center align-middle">
                                        @if($cat->cover_image)
                                            <img src="{{ asset('uploads/catalogues/images/' . $cat->cover_image) }}" alt="{{ $cat->title }}" style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                        @else
                                            <div style="width: 50px; height: 70px; background: #f8f9fa; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin: 0 auto;">
                                                <small class="text-muted">No cover</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <strong class="text-dark d-block">{{ $cat->title }}</strong>
                                        <small class="text-muted font-italic">/{{ $cat->slug }}</small>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($cat->category)
                                            <span class="badge badge-info" style="font-size: 11px; padding: 4px 8px; font-weight: 500;">
                                                {{ $cat->category->name }}
                                            </span>
                                        @else
                                            <span class="badge badge-light border text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($cat->pdf_file)
                                            <a href="{{ asset('uploads/catalogues/pdfs/' . $cat->pdf_file) }}" target="_blank" class="text-danger font-weight-bold d-inline-flex align-items-center" style="gap: 3px;" title="View PDF">
                                                <i class="ft-file-text"></i> PDF
                                            </a>
                                            <small class="text-muted d-block font-small-2">({{ $cat->file_size ?? 'N/A' }})</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($cat->downloads_count > 0)
                                            <a href="{{ route('catalog_admin') }}" class="badge badge-success" style="font-size: 11px; padding: 4px 8px; text-decoration: none;" title="View {{ $cat->downloads_count }} download leads">
                                                <i class="ft-download mr-1"></i> {{ $cat->downloads_count }}
                                            </a>
                                        @else
                                            <span class="badge badge-light border text-muted" style="font-size: 11px; padding: 3px 6px;">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch text-center">
                                            <input type="checkbox" class="custom-control-input knob switch" data-col="{{ \App\Helpers\Common_function::encrypt('is_featured') }}" id="customSwitchFeatured{{ $cat->id }}" {{ $cat->is_featured ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitchFeatured{{ $cat->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-switch text-center">
                                            <input type="checkbox" class="custom-control-input knob switch" data-col="{{ \App\Helpers\Common_function::encrypt('status') }}" id="customSwitchStatus{{ $cat->id }}" {{ $cat->status === 'active' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitchStatus{{ $cat->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle font-weight-bold">{{ $cat->sort_order ?? 0 }}</td>
                                    <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                        @if($cat->pdf_file)
                                        <a href="{{ asset('uploads/catalogues/pdfs/' . $cat->pdf_file) }}" target="_blank" class="mx-1 text-info" data-toggle="tooltip" title="View PDF"><i class="ft-eye font-medium-3"></i></a>
                                        @endif
                                        <a href="{{ route('admin.catalogues.edit', $cat->id) }}" class="mx-1 text-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3"></i></a>
                                        <a href="{{ route('admin.catalogues.duplicate', $cat->id) }}" class="mx-1 text-success" data-toggle="tooltip" title="Duplicate" onclick="return confirm('Are you sure you want to duplicate this catalogue?');"><i class="ft-copy font-medium-3"></i></a>
                                        <a href="javascript:;" class="delete-catalogue-btn mx-1 text-danger" data-id="{{ $cat->id }}" data-toggle="tooltip" title="Delete"><i class="icon ft-trash-2 font-medium-3"></i></a>
                                        <form id="delete-form-{{ $cat->id }}" action="{{ route('admin.catalogues.destroy', $cat->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Cover</th>
                                    <th>Title</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">PDF</th>
                                    <th class="text-center">Leads</th>
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
    $('#catalogues-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'Action' && title !== 'ACTION' && title !== 'Cover' && title !== 'COVER') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#catalogues-table').DataTable({
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[7, "asc"]],
        columnDefs: [
            { targets: [0, 3, 8], orderable: false }
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

    $(document).on('click', '.delete-catalogue-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this catalogue?')) {
            $('#delete-form-' + id).submit();
        }
    });
});
</script>
@stop
