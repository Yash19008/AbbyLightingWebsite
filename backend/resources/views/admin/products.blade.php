@extends('admin.page')

@section('title',$title)
@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Products</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{route('product_admin.add')}}" class="buttons"><span>Add Product</span></a>
                </span>
            </button>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-download mr-1"></i>
                    <a id="export-data" class="buttons"><span>Export</span></a>
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
                        <table class="table data-table table-bordered" id="products" data-order='[[ 0, "desc" ]]'  style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 50px" class="text-left">ID</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Show as New Arrival</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Show as New Arrival</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    </div>
        <input type="hidden" id="hdn" value="{{$tbl}}">
        <form action="{{url('admin/products')}}" id="exports_form" method="get">
        </form>
        <div class="modal fade" id="duplicateModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Duplicate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to duplicate this product?</p>
                    <p><strong>ID:</strong> <span id="dupProductId"></span></p>
                    <p><strong>Title:</strong> <span id="dupProductTitle"></span></p>
                </div>
                <div class="modal-footer">
                    <form id="duplicateForm" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Yes, Duplicate</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
function confirmDuplicate(id, title) {    
    if (confirm("Are you sure you want to duplicate the product:" + id + " " + title + " ?")) {
        $.ajax({
            url: siteUrl + '/product/duplicate/' + id, // <-- ensure admin is in URL
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $('.data-table').DataTable().ajax.reload(null, false); // reload without resetting pagination
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr) {
                let message = "Something went wrong. Please try again.";

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = "Error: " + xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    message = "Server Response: " + xhr.responseText;
                }

                alert(message);
                console.error("Duplicate Product Error:", xhr);
            }
        });
    }
}
</script>


@stop
@section('extra_js')
<style>
   .dataTables_filter,
    .dataTables_info {
        display: none;
    }
</style>
<script type="text/javascript">
    $ (document).ready(function(){
        $('#products tfoot th').each(function () {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        });
        
        $(document).on('click', '#export-data', function (e) {
            e.preventDefault();
            var data =  $( "#exports_form" ).serialize();
            $('#exports_form').attr('action', siteUrl+'/products_exports?'+data);
            $('#exports_form').submit();
            
        });

        var columns = [];
        columns = [
            {
                data: 'id',
                name: 'id',
                orderable: true,
                searchable: true,
                class: 'text-left align-middle'
            },
            {
                data: 'title',
                name: 'title',
                orderable: true,
                searchable: true,
                class: 'text-center align-middle'
            },
            {
                data: 'slug',
                name: 'slug',
                orderable: true,
                searchable: true,
                class: 'text-center align-middle'
            },
        
            {
                data: 'status',
                name: 'status',
                class: 'text-center align-middle',
                orderable: false,
                searchable: false,
            },
            
            {
                data: 'show_as_new_arrival',
                name: 'show_as_new_arrival',
                class: 'text-center align-middle',
                orderable: false,
                searchable: false,
            },
            {
                data: 'action',
                name: 'action',
                class: 'text-center align-middle',
                orderable: false,
                searchable: false
            },
        ];

        $(function() {
            
            $('#products tfoot th').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            });
        //$('#products tfoot tr').appendTo('#products thead');
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('product_admin.list') }}",
                columns: columns,
                searching: true,
                
            });
            // Apply the search
            table.columns().every( function () {
                var that = this;
                that.columns()
                    .every(function () {
                        var that = this;
    
                        $('input', this.footer()).on('keyup change clear', function () {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
            } );

        });
    })
   
</script>
@stop
