@extends('admin.page')

@section('title', $title)
@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex">
            <div style="margin-right:auto">
                <span class="d-flex align-items-center">
                    <h4>Add CSVs</h4>
                </span>
            </div>
           
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
        <form class="form-horizontal" id="" novalidate action="javascript:;" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card">
                <div class="card-body" style="margin:auto;">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="inputName" class="col-sm-12 control-label">Select file to upload</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="file" name="file">
                        </div>
                    </div>
                    <div class="form-group row" style="margin:auto;">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-dark ">Add CSV</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@stop