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
                            <label for="inputName" class="col-sm-3 control-label">title<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="title" name="title" class="form-control" required placeholder="Enter title" value="{{ old('title',@$category->title) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Slug<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="slug" name="slug" class="form-control" placeholder="Slug" value="{{@$category->slug}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">uri</label>
                            <div class="col-sm-6">
                                <input type="text" id="uri" name="uri" class="form-control" placeholder="Enter your uri" value="{{ old('uri',@$category->uri) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ingredients" class="col-sm-3 control-label">In Menu<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <select id="in_menu" name="in_menu" required  class="form-control select2" placeholder="In Menu">
                                        <option value="" disabled >Select value</option>
                                        <option value="yes" {{ (@$category->in_menu == 'yes') ? 'selected' : '' }} > Yes </option>
                                        <option value="no" {{ (@$category->in_menu == 'no') ? 'selected' : '' }} > No </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Sheet Value</label>
                            <div class="col-sm-6">
                                <input class="form-control" type="text" name="sheet_title" id="sheet_title" placeholder="Enter your sheet value" value="{{ old('sheet_title',@$category->sheet_title) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Thumbnail Image</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="featured_image" id="featured_image" accept="image/*" class="file-input">

                                    </div>
                                    <input type="text" class="form-control" disabled placeholder="Upload Image" value="{{@$category->featured_image}}">
                                    <input type="hidden" name="oldPhoto" value="{{@$category->featured_image}}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                            </div>
                        </div>
                    <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Display Icon</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="display_icon" id="display_icon" accept="image/*" class="file-input">

                                    </div>
                                    <input type="text" class="form-control" disabled placeholder="Upload Image" value="{{@$category->display_icon}}">
                                    <input type="hidden" name="oldPhoto" value="{{@$category->display_icon}}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Gallary</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="hidden" name="photo" id="photo" value="">
                                        <input type="file" name="gallary[]" id="gallary" multiple accept="image/*" class="file-input">

                                    </div>
                                    <input type="text" class="form-control" disabled placeholder="Upload Image" value="">
                                    <input type="hidden" name="oldPhoto" value="">
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

            $('input[name="title"]').on('input', function() {
                let variantName = $(this).val();
                let formattedSlug = variantName.toLowerCase().replace(/\s+/g, '-');

                $('input[name="slug"]').val(formattedSlug);
            });
        });
    </script>
@endsection
