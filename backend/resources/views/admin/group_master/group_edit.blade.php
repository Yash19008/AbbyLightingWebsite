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
                            <label for="inputName" class="col-sm-2 control-label">Title<i class="text-danger">*</i></label>
                            <div class="col-sm-5">
                                <input type='text' name="title"  class="form-control"  value="{{ old('title',@$group->title) }}" placeholder="Title" required>

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