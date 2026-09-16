@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row align-items-center mb-3">
    <div class="col-6 col-md-6">
        <h4 class="m-0 font-weight-bold text-dark">
            <i class="ft-download mr-1"></i> Catalog Download Leads
        </h4>
    </div>
    <div class="col-6 col-md-6 text-right">
        <a href="{{ route('admin.catalogues.index') }}" class="btn btn-primary btn-sm">
            <i class="ft-book mr-1"></i> Manage Catalogues
        </a>
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
                        <table class="table data-table table-bordered" data-order='[[ 5, "desc" ]]' id="catalogDownloadsTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>CATALOGUE</th>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>MOBILE</th>
                                    <th class="text-center" style="width: 160px;">DOWNLOAD DATE</th>
                                    <th class="text-center" style="width: 110px; min-width: 110px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Catalogue</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th class="text-center">Download Date</th>
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

<!-- View Lead Details Modal -->
<div class="modal fade" id="viewLeadModal" tabindex="-1" role="dialog" aria-labelledby="viewLeadModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 650px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="viewLeadModalTitle">
                    <i class="ft-file-text mr-1"></i> Catalogue Download Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <table class="table table-bordered table-striped m-0" style="font-size: 13px;">
                    <tbody>
                        <tr>
                            <th style="width: 32%; background: #f8f9fa;" class="align-middle">Catalogue</th>
                            <td id="modalCatalogueName" class="font-weight-bold text-dark align-middle"></td>
                        </tr>
                        <tr>
                            <th style="background: #f8f9fa;" class="align-middle">Full Name</th>
                            <td id="modalName" class="align-middle font-weight-bold"></td>
                        </tr>
                        <tr>
                            <th style="background: #f8f9fa;" class="align-middle">Email Address</th>
                            <td id="modalEmail" class="align-middle"></td>
                        </tr>
                        <tr>
                            <th style="background: #f8f9fa;" class="align-middle">Mobile Number</th>
                            <td id="modalMobile" class="align-middle"></td>
                        </tr>
                        <tr>
                            <th style="background: #f8f9fa;" class="align-middle">City</th>
                            <td id="modalCity" class="align-middle"></td>
                        </tr>
                        <tr>
                            <th style="background: #f8f9fa;" class="align-middle">Company / Firm</th>
                            <td id="modalCompany" class="align-middle"></td>
                        </tr>
                        <tr>
                            <th style="background: #f8f9fa;" class="align-middle">Role / Profession</th>
                            <td id="modalRole" class="align-middle"></td>
                        </tr>
                        <tr>
                            <th style="background: #f8f9fa;" class="align-top">Message / Notes</th>
                            <td id="modalMessage" class="align-top" style="white-space: pre-wrap; line-height: 1.5;"></td>
                        </tr>
                        <tr>
                            <th style="background: #f8f9fa;" class="align-middle">Download Date</th>
                            <td id="modalCreatedAt" class="text-muted align-middle"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('extra_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    /* Fix Bootstrap modal backdrop overlay issue */
    .modal-backdrop {
        z-index: 1040 !important;
    }
    .modal {
        z-index: 1050 !important;
    }
    
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
    $(document).ready(function () {
        // Move modal to body so it doesn't get obscured by parent container stacking contexts / backdrop
        $('#viewLeadModal').appendTo('body');

        // Setup individual column search boxes in footer
        $('#catalogDownloadsTable tfoot th').each(function () {
            var title = $(this).text().trim();
            if (title !== 'Action' && title !== 'ACTION' && title !== '#' && title !== '') {
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            } else {
                $(this).html('');
            }
        });

        var columns = [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                width: '50px',
                class: 'text-center align-middle font-weight-bold text-muted'
            },
            {
                data: 'catalogue_name',
                name: 'catalogue_name',
                orderable: true,
                searchable: true,
                class: 'align-middle font-weight-bold'
            },
            {
                data: 'name',
                name: 'name',
                orderable: true,
                searchable: true,
                class: 'align-middle'
            },
            {
                data: 'email',
                name: 'email',
                class: 'align-middle',
                orderable: true,
                searchable: true,
            },
            {
                data: 'mobile',
                name: 'mobile',
                class: 'align-middle',
                orderable: true,
                searchable: true,
            },
            {
                data: 'created_at',
                name: 'created_at',
                width: '160px',
                class: 'text-center align-middle text-muted',
                orderable: true,
                searchable: true,
            },
            {
                data: 'action',
                name: 'action',
                width: '110px',
                orderable: false,
                searchable: false,
                class: 'text-center align-middle list-action actBtn-td'
            }
        ];

        var table = $('#catalogDownloadsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('catalog_admin.list') }}",
            columns: columns,
            searching: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[5, "desc"]],
            language: {
                emptyTable: "No catalogue downloads recorded yet."
            }
        });

        // Apply column footer search
        table.columns().every(function () {
            var that = this;
            $('input', this.footer()).on('keyup change clear', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });
        });

        // View Modal Handler
        $(document).on('click', '.view-lead-btn', function () {
            try {
                var data = $(this).data('lead');
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }

                $('#modalCatalogueName').text(data.catalogue_name || 'General Catalog');
                $('#modalName').text(data.name || '-');
                $('#modalEmail').html(data.email ? '<a href="mailto:' + data.email + '" style="color:#0284c7; font-weight:600;">' + data.email + '</a>' : '-');
                $('#modalMobile').text(data.mobile || '-');
                $('#modalCity').text(data.city || '-');
                $('#modalCompany').text(data.company || '-');
                $('#modalRole').text(data.role || '-');
                $('#modalMessage').text(data.message || 'No message provided.');
                $('#modalCreatedAt').text(data.created_at || '-');

                $('#viewLeadModal').modal('show');
            } catch (e) {
                console.error("Error opening lead view modal:", e);
            }
        });

        // Ensure modal closes properly on click
        $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"]', function () {
            $('#viewLeadModal').modal('hide');
        });

        // Delete Handler
        $(document).on('click', '.delete-lead-btn', function () {
            var id = $(this).data('id');
            if (!id) return;

            if (confirm('Are you sure you want to delete this download lead?')) {
                $.ajax({
                    url: "{{ url('admin/catalog/delete') }}/" + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.success) {
                            table.ajax.reload(null, false);
                        } else {
                            alert(res.message || 'Error deleting record.');
                        }
                    },
                    error: function (xhr) {
                        alert('Failed to delete record. Please try again.');
                    }
                });
            }
        });
    });
</script>
@stop