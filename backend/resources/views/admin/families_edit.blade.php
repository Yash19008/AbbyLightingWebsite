r@extends('admin.page')

@section('title', $title)

@section('content_header')
@stop
@section('content')
<div class="row">
        <div class="col-12">
            <div class="content-header">{{@$title}}</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
            <div class="card card-primary">
                <form class="form-horizontal" id="{{$frn_id}}" novalidate action="javascript:;" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <!-- ./form sub header-->
                        <div class="form-group row">
                                <div class=" col-sm-12" style="display:flex;">
                                    <!-- ADD EDIT PRODUCT LINK HERE -->
                                    <a href="{{route('family_admin.add')}}" style="margin-left: auto;" class="btn btn-dark">Add Family</a>
                                </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Family</label>
                            <div class="col-sm-6">
                                <input type="text" id="family_name" name="family_name" class="form-control" placeholder="Family Name" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="description" rows="5" cols="7" placeholder="Description"  id="description"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Featured Image</label>
                            <div class="col-sm-6">
                                <input type="file" id="image" name="image" class="form-control" placeholder="Image Name Here" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Gallary</label>
                            <div class="col-sm-6">
                                <input type="file" id="image" name="image" class="form-control" placeholder="Image Name Here" value="">
                            </div>
                        </div>
                     
                     
                        <div class="form-group row">
                            <div class="offset-3 col-sm-8">
                                <button type="submit" class="btn btn-dark ">Save</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label" style="font-size:16px">Products</label>
                            
                        </div>
                        <div class="form-group row">
                                <div class=" col-sm-8">
                                    <!-- ADD EDIT PRODUCT LINK HERE -->
                                    <a href="{{route('add_product.add')}}"  class="btn btn-dark">Edit Products</a>
                                </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
