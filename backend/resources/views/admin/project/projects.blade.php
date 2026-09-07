@extends('admin.page')

@section('title',$title)
@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Projects</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{route('project_admin.add')}}" class="buttons"><span>Add Project</span></a>
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
                        <table class="table data-table table-bordered" data-order='[[ 3, "asc" ]]' id="projects"  style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Project Type</th>
                                    <th>Slug</th>
                                    <th>Sequence</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Project Type</th>
                                    <th>Slug</th>
                                    <th>Sequence</th>
                                    <th>Status</th>
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
    <form action="{{url('admin/agents')}}" id="exports_form" method="get">
    </form>
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
        $('#projects tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });
    
    $(document).on('click', '#export-data', function (e) {
        e.preventDefault();
        var data =  $( "#exports_form" ).serialize();
        $('#exports_form').attr('action', siteUrl+'/projects_exports?'+data);
        $('#exports_form').submit();
        
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
            data: 'project_type',
            name: 'project_type',
            class: 'text-center align-middle',
            orderable: true,
            searchable: false,
        },
        {
            data: 'slug',
            name: 'slug',
            class: 'text-center align-middle',
            orderable: true,
            searchable: false,
        },
        {
            data: 'sequence',
            name: 'sequence',
            class: 'text-center align-middle',
            orderable: true,
            searchable: false,
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
            searchable: false,
        },
    ];

    $(function() {
            
            $('#projects tfoot th').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            });
        //$('#projects tfoot tr').appendTo('#projects thead');
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('project_admin.list') }}",
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
