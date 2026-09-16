@extends('admin.page')

@section('title', $title ?? 'Worlds of Light')

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Worlds of Light</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('light_worlds_admin.add') }}" class="buttons"><span>Add World</span></a>
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
                        <table class="table data-table table-bordered" data-order='[[ 4, "asc" ]]' id="lightworlds-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>LINK</th>
                                    <th class="text-center" style="width: 110px;">LIGHT OFF IMAGE</th>
                                    <th class="text-center" style="width: 110px;">LIGHT ON IMAGE</th>
                                    <th class="text-center" style="width: 90px;">SEQUENCE</th>
                                    <th class="text-center" style="width: 100px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $world)
                                <tr class="data module-list" id="data-{{ $world->id }}">
                                    <td class="align-middle font-weight-bold">{{ $world->name }}</td>
                                    <td class="align-middle">
                                        @if($world->link)
                                            <a href="{{ $world->link }}" target="_blank" class="text-primary">{{ $world->link }}</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="img-td text-center align-middle">
                                        @if($world->light_of_image)
                                            @php
                                                $offUrl = (str_starts_with($world->light_of_image, 'http') || str_starts_with($world->light_of_image, '/images') || str_starts_with($world->light_of_image, 'images/'))
                                                    ? $world->light_of_image
                                                    : asset('storage/' . $world->light_of_image);
                                            @endphp
                                            <img src="{{ $offUrl }}" alt="{{ $world->name }} off" style="width: 75px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="img-td text-center align-middle">
                                        @if($world->light_on_image)
                                            @php
                                                $onUrl = (str_starts_with($world->light_on_image, 'http') || str_starts_with($world->light_on_image, '/images') || str_starts_with($world->light_on_image, 'images/'))
                                                    ? $world->light_on_image
                                                    : asset('storage/' . $world->light_on_image);
                                            @endphp
                                            <img src="{{ $onUrl }}" alt="{{ $world->name }} on" style="width: 75px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">{{ $world->sort_order ?? 0 }}</td>
                                    <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                        <a href="{{ route('light_worlds_admin.edit', $world->id) }}" class="mx-1 text-primary" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3"></i></a>
                                        <a href="javascript:;" class="delete-world-btn mx-1 text-danger" data-id="{{ $world->id }}" data-toggle="tooltip" title="Delete"><i class="icon ft-trash-2 font-medium-3"></i></a>
                                        <form id="delete-form-{{ $world->id }}" action="{{ route('light_worlds_admin.delete', $world->id) }}" method="POST" style="display: none;">
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
                                    <th>Link</th>
                                    <th class="text-center">Light Off Image</th>
                                    <th class="text-center">Light On Image</th>
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
    $('#lightworlds-table tfoot th').each(function () {
        var title = $(this).text().trim();
        if (title !== 'Action' && title !== 'ACTION' && title !== 'Light Off Image' && title !== 'LIGHT OFF IMAGE' && title !== 'Light On Image' && title !== 'LIGHT ON IMAGE') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        } else {
            $(this).html('');
        }
    });

    var table = $('#lightworlds-table').DataTable({
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[4, "asc"]],
        columnDefs: [
            { targets: [2, 3, 5], orderable: false }
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

    $(document).on('click', '.delete-world-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this world?')) {
            $('#delete-form-' + id).submit();
        }
    });
});
</script>
@stop
