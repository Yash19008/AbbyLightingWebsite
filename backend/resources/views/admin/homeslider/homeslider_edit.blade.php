@extends('admin.page')

@section('title', $title)

@section('content_header')
@stop
@section('content')
<div class="row">
        <div class="col-12">
            <div class="content-header">{{@$title}}</div>
            @if($errors->any())
                {{ implode('', $errors->all('<div>:message</div>')) }}
            @endif
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
                            <label for="inputName" class="col-sm-3 control-label">Image Path<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="file" name="path" id="imagefile" accept="image/*" class="file-input">

                                    </div>
                                    <input type="text" class="form-control" disabled id="disabled_file_path" placeholder="Image Path" value="{{ @$slider->path }}">
                                    <input type="hidden" name="oldPhoto" value="{{ @$slider->path }}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                    <a href="javascript:;" data-toggle="tooltip" title="delete" style="width: unset; height: 21px; margin-top: 8px; margin-left: 10px; visibility: hidden;"><i class="ft-trash font-medium-3"></i></a>
                                </div>
                                <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>

                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$slider->id }}" data-img-type="path" title="remove image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>
                        <input type="hidden" name="remove_path" value="0">

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">For_Mobile<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="hidden" id="for_mobile" name="for_mobile" placeholder="" value="0">
                                <input type="checkbox" id="for_mobile" name="for_mobile" placeholder="" value="1" {{ @$slider->for_mobile == 1 ? 'Checked' : '' }}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Sort_Order<i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <input type="text" id="sort_order" name="sort_order" class="form-control" placeholder="" value="{{@$slider->sort_order}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">URL</label>
                            <div class="col-sm-6">
                                <input type="text" id="url" name="url" class="form-control" placeholder="" value="{{@$slider->url}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Heading</label>
                            <div class="col-sm-6">
                                <input type="text" id="heading" name="heading" class="form-control" placeholder="Enter slide heading" value="{{@$slider->heading}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-6">
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter slide description">{{@$slider->description}}</textarea>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Button Text</label>
                            <div class="col-sm-6">
                                <input type="text" id="button_text" name="button_text" class="form-control" placeholder="e.g., Learn More" value="{{@$slider->button_text}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 control-label">Button Link</label>
                            <div class="col-sm-6">
                                <input type="text" id="button_link" name="button_link" class="form-control" placeholder="e.g., /products" value="{{@$slider->button_link}}">
                            </div>
                        </div>
                       
                        <!-- <div class="form-group row">
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
                        </div> -->
                       
                     
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
        // Delete Image
            $(".remove-tag-img").click(function(e) {
                e.preventDefault();

                // var model_id = $(this).attr("data-id");
                var column = $(this).attr("data-img-type");

                $('[name="remove_' + column + '"]').val(1);
                $('#disabled_file_'+column).val("");
            });
        });
    </script>

@stop
