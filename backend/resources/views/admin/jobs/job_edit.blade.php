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
                    <!-- ./form sub header-->
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Title<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="title" name="title" class="form-control" required
                                placeholder="Enter title" value="{{ old('title',@$job->title) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Location<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="location" name="location" class="form-control" required
                                placeholder="Enter location" value="{{ old('location',@$job->location) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Short Description<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="short_description" id="short_description" rows="2"
                                required cols="7" placeholder="Enter short description"
                                id="short_description">{{(@$job->short_description)}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 control-label">Description<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="description" id="description" rows="20" required
                                cols="7" placeholder="Enter description"
                                id="description">{{(@$job->description)}}</textarea>
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
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    $('#description').summernote(
        {
        height: 300,
        toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            [ 'table', [ 'table' ] ],
            [ 'insert', [ 'link'] ],
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
        ]
    }
    );
});
</script>
@endsection