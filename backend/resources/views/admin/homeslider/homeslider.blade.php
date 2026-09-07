@extends('admin.page')

@section('title',$title)

@section('content_header')
<div class="row">
    <div class="col-6 col-md-6">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>Home Slider Banners</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{route('homeslider_admin.add')}}" class="buttons"><span>Add New Banner</span></a>
                </span>
            </button>
        </div>
    </div>
    <div class="col-6 col-md-6 my-3 text-right" style="display:flex;">
        <!-- <form action="{{ route('sub_tag_admin.upload') }}" method="post" enctype="multipart/form-data" style='margin-left: auto;'>
            @csrf
            <span> Upload Banner Image</span>
            <input type="file" name="banner_image" class="w-50 form-control d-inline" id="">
            <button class="btn btn-primary">Save</button>
        </form> -->
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
                                        <th style="width: 50px" class="text-center">ID</th>
                                        <th style="width: 100px" class="text-center">Image</th>
                                        <th style="width: 22%" class="text-left">Heading</th>
                                        <th style="width: 38%" class="text-left">Description</th>
                                        <th style="width: 100px" class="text-center">For Mobile</th>
                                        <th style="width: 90px" class="text-center">Sort Order</th>
                                        <th style="width: 100px" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($results)==0)
                                    <tr class="data">
                                        <td colspan="7" align="center">No Records Found</td>
                                    </tr>
                                    @else
                                    @foreach($results as $key=>$row)
                                    <tr class="data module-list" id="data-{{ $row->id }}">
                                        <td class="text-center">{{@$row->id}}</td>
                                        <td class="img-td text-center">
                                            <div class="">
                                                <img style="text-align:center;width:80px;height:60px;object-fit:contain;border-radius:4px;border:1px solid #eee;" src="/storage/{{ $row->path }}" class="list-image-prof">
                                            </div>
                                        </td>
                                        <td class="text-left">
                                            @if(!empty($row->heading))
                                                <strong>{{ \Illuminate\Support\Str::limit($row->heading, 40) }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-left">
                                            @if(!empty($row->description))
                                                <span title="{{ $row->description }}">{{ \Illuminate\Support\Str::limit($row->description, 70) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(@$row->for_mobile == 1)
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{@$row->sort_order ?? 0}}</td>
                                        <td class="text-center list-action actBtn-td">
                                            <a href="{{route('homeslider_admin.edit',@$row->id)}}" class=""
                                                data-toggle="tooltip" title="Edit"><i
                                                    class="ft-edit-2 font-medium-3 mr-1"></i></a>
                                            <a href="javascript:;" class="delete" data-module="{{@$main_module}}" data-toggle="tooltip" title="delete"><i class="ft-trash font-medium-3"></i></a>
                                        </td>

                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
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