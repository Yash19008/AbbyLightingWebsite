@extends('admin.page')

@section('title',$title)

@section('content_header')
<div class="row">
    <div class="col-6">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Clients List</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{route('client_admin.add')}}" class="buttons"><span>Create Client</span></a>
                </span>
            </button>
        </div>
    </div>
    <div class="col-6 col-md-6 my-3 text-right" style="display:flex;">
        <form action="{{ route('client_admin.upload') }}" method="post" enctype="multipart/form-data"
            style='margin-left: auto;'>
            @csrf
            <span> Upload Banner Image</span>
            <input type="file" name="banner_image" class="w-50 form-control d-inline" id="">
            <button class="btn btn-primary">Save</button>
        </form>
        <div style='margin-left: auto;'>
            <form action="" method="get">
                <input type="search" class="form-control" placeholder="Search Name" name="search" value="{{@$search}}">
            </form>
        </div>
    </div>
</div>
@stop
@section('content')
@include('admin.include.notification')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 50px" class="text-left">ID</th>
                                        <th class="text-center">Image</th>
                                        <th style="width: 80px" class="text-center"><a>Action<i class="ml-1"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($results)==0)
                                    <tr class="data">
                                        <td colspan="3" align="center">No Records Found</td>
                                    </tr>
                                    @else
                                    @foreach($results as $key=>$row)
                                    <tr class="data module-list" id="data-{{ $row->id }}">
                                        <td>{{@$row->id}}</td>
                                        <td class="img-td">
                                            <div class="">
                                                <img style="text-align:center;width:80px;height:60px;object-fit:contain;"
                                                    src="{{ $row->path ?  asset('storage/uploads/clients/'.@$row->path) : asset('images/default.png') }}"
                                                    class="list-image-prof">
                                            </div>
                                        </td>
                                        <td class="text-center list-action actBtn-td">
                                            <a href="javascript:;" class="delete" data-module="{{@$main_module}}"
                                                data-toggle="tooltip" title="delete"><i
                                                    class="ft-trash font-medium-3"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            {{ $results->links('pagination::bootstrap-5') }}
                        </div>
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