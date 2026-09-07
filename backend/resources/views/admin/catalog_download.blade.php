@extends('admin.page')

@section('title',$title)
@section('content_header')
<div class="row">
    <div class="col-6 col-md-6">
        <div class="my-3" style="display:flex;">
            <div style="margin-right:auto">
                <span class="d-flex align-items-center">
                    <h4>Catalog Download 
                    <a href="{{ asset('storage/uploads/catalog/Abby_Lighting_Product_Catalog.pdf') }}" target="_blank">view</a></h4>
                                    
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-6 my-3 text-right">

        <form action="{{ route('catalog_admin.upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <span> Upload New Catalog</span>
            <input type="file" name="catalog_file" class="w-50 form-control d-inline" id="">
            <button class="btn btn-primary">Save</button>
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
                        <table class="table data-table table-bordered" data-order='[[ 3, "desc" ]]' id="category"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th class="text-center">Download Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th class="text-center">Download Date</th>
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
        $('#category tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });
    var columns = [];
    columns = [
        {
            data: 'name',
            name: 'name',
            orderable: true,
            searchable: true,
            class: 'text-center align-middle'
        },
      
        {
            data: 'email',
            name: 'email',
            class: 'text-center align-middle',
            orderable: true,
            searchable: true,
        },
        {
            data: 'mobile',
            name: 'mobile',
            class: 'text-center align-middle',
            orderable: true,
            searchable: true,
        },
        {
            data: 'created_at',
            name: 'created_at',
            class: 'text-center align-middle',
            orderable: true,
            searchable: true,
            render:function (value) {
                const createdAt = new Date(value);
                const formattedDate = createdAt.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric'
                });
                return formattedDate
            }
        },
    ];

    $(function() {
            
            $('#category tfoot th').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            });
        //$('#category tfoot tr').appendTo('#category thead');
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('catalog_admin.list') }}",
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