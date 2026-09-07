@extends('admin.page')

@section('title',$title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div style="margin-right:auto">
                <span class="d-flex align-items-center">
                    <h4>Families</h4>
                </span>
            </div>
            <button class="btn btn-primary mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{route('family_admin.add')}}" class="buttons"><span>Add Family</span></a>
                </span>
            </button>
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
                                        <th width="60%" class="text-center">Title</th>
                                        <th width="40%" class="text-center"><a>Action<i class="ml-1"></i></a></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="data module-list" id="">
                                            <td>CANALAZZO R</td>
                                            <td class="text-center list-action actBtn-td">
                                                <a href="{{route('add_product.add')}}" class="" style="margin-right:20px" data-toggle="tooltip" title="Edit">Add Product</a>
                                                <a href="{{route('family_admin.edit',1)}}" class="" data-toggle="tooltip" style="margin-right:20px" title="Edit">Edit</a>
                                                <a href="javascript:;" class="" data-toggle="tooltip" style="margin-right:20px" title="View on website">View on website</a>     
                                            </td>
                                        </tr>
                                        <tr class="data module-list" id="">
                                            <td>CANALAZZO S</td>
                                            <td class="text-center list-action actBtn-td">
                                                <a href="{{route('add_product.add')}}" class="" style="margin-right:20px" data-toggle="tooltip" title="Edit">Add Product</a>
                                                <a href="javascript:;" class="" data-toggle="tooltip" style="margin-right:20px" title="Edit">Edit</a>
                                                <a href="javascript:;" class="" data-toggle="tooltip" style="margin-right:20px" title="View on website">View on website</a>     
                                            </td>
                                        </tr>
                                        <tr class="data module-list" id="">
                                            <td>IBARREL</td>
                                            <td class="text-center list-action actBtn-td">
                                                <a href="{{route('add_product.add')}}" class="" data-toggle="tooltip" title="Edit" style="margin-right:20px">Add Product</a>
                                                <a href="javascript:;" class="" style="margin-right:20px" data-toggle="tooltip" title="Edit">Edit</a>
                                                <a href="javascript:;" class="" data-toggle="tooltip" style="margin-right:20px" title="View on website">View on website</a>     
                                            </td>
                                        </tr>
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
   
@stop
