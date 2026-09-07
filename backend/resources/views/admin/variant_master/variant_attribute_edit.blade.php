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
    @include('admin.include.notification')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
            <div class="card card-primary">
                <form class="form-horizontal" id="{{$frn_id}}" novalidate action="{{$action}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <!-- ./form sub header-->
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Name<i class="text-danger">*</i></label>
                            <div class="col-sm-5">
                                <input type='text' name="name"  class="form-control"  value="{{ old('name',@$variant_attr->name) }}" placeholder="Name" required>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 control-label">Sheet Title</label>
                            <div class="col-sm-5">
                                <input type='text' name="sheet_title"  class="form-control"  value="{{ old('sheet_title',@$variant_attr->sheet_title) }}" placeholder="Sheet Title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ingredients" class="col-sm-2 control-label">Is File<i class="text-danger">*</i></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <select id="is_file" name="is_file" class="form-control" placeholder="Select is file">
                                        <option value="yes" {{(@$variant_attr->is_file == 'yes') ? "selected" : ""}} >Yes</option>
                                        <option value="no" {{(@$variant_attr->is_file == 'no') ? "selected" : ""}}>No</option>
                                  
                                    </select>
                                </div>
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <div class="offset-2 col-sm-10">
                                <button type="submit" class="btn btn-dark ">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop