@extends('admin.page')

@section('title',$title)
@section('content_header')
<div class="row">
    <div class="col-6 col-md-6">
        <div class="my-3" style="display:flex;margin-bottom:0px !important;">
            <div style="margin-right:auto">
                <span class="d-flex align-items-center">
                    <h4>Enquiries</h4>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-6 my-3 text-right">

        <form action="{{ route('contact_form_admin.upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <span> Upload Banner Image</span>
            <input type="file" name="banner_image" class="w-50 form-control d-inline" id="">
            <button class="btn btn-primary">Save</button>
        </form>
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
                            <table class="table data-table table-bordered" id="products" data-order='[[ 4, "desc" ]]'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="15%" class="text-center">Name</th>
                                        <th width="20%" class="text-center">Info</th>
                                        <th width="20%" class="text-center">Contact</th>
                                        <th width="20%" class="text-center">Message</th>
                                        <th width="15%" class="text-center">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Info</th>
                                    <th class="text-center">Contact</th>
                                    <th class="text-center">Message</th>
                                    <th class="text-center">Time</th>
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
            data: 'full_name',
            name: 'full_name',
            orderable: true,
            searchable: true,
            class: 'text-left align-middle'
        },
        {
            data: 'profession',
            name: 'profession',
            orderable: true,
            searchable: true,
            class: 'text-center align-middle'
        },
        {
            data: 'email',
            name: 'email',
            orderable: true,
            searchable: true,
            class: 'text-left align-middle'
        },
      
        {
            data: 'i_message',
            name: 'i_message',
            class: 'text-left align-middle',
            orderable: true,
            searchable: false,
        },
        
        {
            data: 'created_at',
            name: 'created_at',
            class: 'text-left align-middle',
            orderable: true,
            searchable: false,
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
        }
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
                ajax: "{{ route('contact_form_admin.list') }}",
                columns: columns,
                searching: true
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