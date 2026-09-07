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
                <form class="form-horizontal" id="{{$frn_id}}" novalidate action="{{@$action}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <!-- ./form sub header-->
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Display Name<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="display_name" name="display_name" class="form-control" placeholder="Display Name" value="{{@$tag->display_name}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Name<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="{{@$tag->name}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Slug<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug" value="{{@$tag->slug}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Thumbnail Image<i class="text-danger">*</i></label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="file" id="imagefile"  accept="image/*" class="file-input">

                                    </div>
                                    <input type="text" class="form-control" disabled placeholder="Upload Image" value="{{@$tag->image}}">
                                    <input type="hidden" name="oldPhoto" value="{{@$tag->image}}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
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
    <script>
        $(document).ready(function() {

            $('input[name="name"]').on('input', function() {
                let variantName = $(this).val();
                let formattedSlug = variantName.toLowerCase().replace(/\s+/g, '-');

                $('input[name="slug"]').val(formattedSlug);
            });
        });
    </script>
@stop
