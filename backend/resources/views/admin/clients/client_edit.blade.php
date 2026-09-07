@extends('admin.page')

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
            <form class="form-horizontal" id="{{$frn_id}}" novalidate action="{{@$action}}" method="post"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Client Image<i
                                class="text-danger">*</i></label>
                        <div class="col-sm-5 ">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <input type="hidden" name="photo" id="photo" value="">
                                    <input type="file" name="file" id="imagefile" accept="image/*" class="file-input">

                                </div>
                                <input type="text" class="form-control" disabled placeholder="Upload Image"
                                    value="{{@$client->path}}">
                                <input type="hidden" name="oldPhoto" value="{{@$client->path}}">
                                <div class="input-group-append">
                                    <button class="file-input-browse btn btn-dark" type="button"><i
                                            class="glyphicon glyphicon-search"></i> Browse</button>
                                </div>
                            </div>
                            <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="offset-3 col-sm-8">
                            <button type="submit" class="btn btn-dark ">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('extra_js')

@stop