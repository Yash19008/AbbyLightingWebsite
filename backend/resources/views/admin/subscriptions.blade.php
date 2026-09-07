@extends('admin.page')

@section('title',$title)
@section('content_header')
<div class="row">
    <div class="col-6 col-md-6">
        <div class="my-3" style="display:flex;margin-bottom:0px !important;">
            <div style="margin-right:auto">
                <span class="d-flex align-items-center">
                    <h4>Subscriptions</h4>
                </span>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')
@include('admin.include.notification')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 overflow-hidden">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <section id="configuration">
                        <div class="table-responsive">
                            <table class="table data-table table-bordered" id="products" data-order='[[ 2, "desc" ]]'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="40%" class="text-center">Name</th>
                                        <th width="40%" class="text-center">Email</th>
                                        <th width="20%" class="text-center">Subscribed At</th>
                                        <th width="20%" class="text-center">Verified At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Subscribed At</th>
                                    <th class="text-center">Verified At</th>
                                </tfoot>
                            </table>
                        </div>
                    </section>
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
        $('#products tfoot th').each(function () {
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
            class: 'text-left align-middle',
            targets: [0],
            orderData: [0]
        },
        {
            data: 'email',
            name: 'email',
            orderable: true,
            searchable: true,
            class: 'text-left align-middle',
            targets: [1],
            orderData: [1]
        },
        {
            data: 'created_at',
            name: 'created_at',
            orderable: true,
            searchable: true,
            class: 'text-center align-middle',
            targets: [2],
            orderData: [4]
        },
        {
            data: 'verified_at',
            name: 'verified_at',
            orderable: true,
            searchable: true,
            class: 'text-center align-middle',
            targets: [3],
            orderData: [5]
        },
        {
            data: 'created_at_row',
            name: 'created_at_row',
            orderable: true,
            searchable: true,
            class: 'text-center align-middle',
            visible: false,
            targets: [4],
            orderData: [4]
        },
        {
            data: 'verified_at_row',
            name: 'verified_at_row',
            orderable: true,
            searchable: true,
            class: 'text-center align-middle',
            visible: false,
            targets: [5],
            orderData: [5]
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
                ajax: "{{ route('subscriptions_admin.list') }}",
                columns: columns,
                columnDefs: [
                    {
                        targets: [0],
                        orderData: [0]
                    },
                    {
                        targets: [1],
                        orderData: [1]
                    },
                    {
                        targets: [2],
                        orderData: [4]
                    },
                    {
                        targets: [3],
                        orderData: [5]
                    },
                    {
                        targets: [4],
                        orderData: [4]
                    },
                    {
                        targets: [5],
                        orderData: [5]
                    }
                ],
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

            // table.on( 'order', function (e, settings, details) {
                
            //     if (details && details.length > 0) {
            //         // table.column(details[0].col + ':visible').order(details[0].dir).draw();
            //     }
            //     // if (details && details.length > 0) {
            //     //     table.order({
            //     //     idx: details[0].col,
            //     //     dir: details[0].dir
            //     // }).draw();
            //     // }
            //     e.stopPropagation()
            // } );

        });
    })
   
</script>
@stop