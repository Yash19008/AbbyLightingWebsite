@extends('admin.page')

@section('title',$title)
@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Groups</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{route('group_admin.add')}}" class="buttons"><span>Create</span></a>
                </span>
            </button>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <a href="{{ route('attribute_admin') }}" data-toggle="tooltip" title="Back" class="buttons a_back">Back</a>
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
                        <table class="table data-table table-bordered  table-responsive" id="groups"  style="width:100%">
                            <thead>
                                <tr>
                                    <th width="300">Title</th>
                                    <th width="300" class="text-center">Status</th>
                                    <th width="300" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th width="300">Title</th>
                                    <th width="300" class="text-center">Status</th>
                                    <th width="300" class="text-center">Action</th>
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
        $('#groups tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });
    var columns = [];
    columns = [
        {
            data: 'title',
            name: 'title',
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
            data: 'action',
            name: 'action',
            class: 'text-center align-middle',
            orderable: false,
            searchable: false
        },
    ];

    $(function() {
            
            $('#groups tfoot th').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            });
        //$('#groups tfoot tr').appendTo('#groups thead');
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('group_admin.list') }}",
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
