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
                            <label for="inputName" class="col-sm-3 control-label">Desktop Image <i class="text-danger">*</i></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="file" name="path" id="imagefile" accept="image/*" class="file-input">
                                    </div>
                                    <input type="text" class="form-control" disabled id="disabled_file_path" placeholder="Desktop Image Path" value="{{ @$slider->path }}">
                                    <input type="hidden" name="oldPhoto" value="{{ @$slider->path }}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Recommended size: 1920x1080px</small>
                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$slider->id }}" data-img-type="path" title="remove image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>
                        <input type="hidden" name="remove_path" value="0">

                        <div class="form-group row">
                            <label for="mobile_path" class="col-sm-3 control-label">Mobile View Image <span class="badge badge-secondary">Optional</span></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="file" name="mobile_path" id="mobileimagefile" accept="image/*" class="file-input">
                                    </div>
                                    <input type="text" class="form-control" disabled id="disabled_file_mobile_path" placeholder="Mobile Image Path" value="{{ @$slider->mobile_path }}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Recommended size: 600x800px</small>
                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$slider->id }}" data-img-type="mobile_path" title="remove mobile image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>
                        <input type="hidden" name="remove_mobile_path" value="0">

                        <div class="form-group row">
                            <label for="tablet_path" class="col-sm-3 control-label">Tablet View Image <span class="badge badge-secondary">Optional</span></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <input type="file" name="tablet_path" id="tabletimagefile" accept="image/*" class="file-input">
                                    </div>
                                    <input type="text" class="form-control" disabled id="disabled_file_tablet_path" placeholder="Tablet Image Path" value="{{ @$slider->tablet_path }}">
                                    <div class="input-group-append">
                                        <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Recommended size: 1024x768px</small>
                            </div>
                            <div class="col-sm-1">
                                <span data-id="{{ @$slider->id }}" data-img-type="tablet_path" title="remove tablet image" class="btn btn-dark remove-tag-img"><i class="fa fa-times"></i></span>
                            </div>
                        </div>
                        <input type="hidden" name="remove_tablet_path" value="0">

                        <div class="form-group row">
                            <label for="is_active" class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-6">
                                <input type="hidden" name="is_active" value="0">
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', @$slider ? @$slider->is_active : 1) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="is_active">Active (Visible on Homepage)</label>
                                </div>
                                <small class="form-text text-muted">Uncheck to deactivate/hide this slider banner from the homepage.</small>
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
                                <input type="text" id="heading" name="heading" class="form-control" placeholder="Enter slide heading (e.g., Shaping the)" value="{{@$slider->heading}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="heading_highlight" class="col-sm-3 control-label">Heading Highlight <span class="badge badge-warning" style="background-color:#f6c177;color:#111;">Yellow Italic Text</span></label>
                            <div class="col-sm-6">
                                <input type="text" id="heading_highlight" name="heading_highlight" class="form-control" placeholder="e.g., art of light, beautiful spaces" value="{{@$slider->heading_highlight}}">
                                <small class="form-text text-muted">This text will be styled with the elegant golden/yellow serif italic font (e.g. <em>art of light</em>).</small>
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
