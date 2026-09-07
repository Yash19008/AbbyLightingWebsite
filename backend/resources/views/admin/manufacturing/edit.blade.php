@extends('admin.page')

@section('title', 'Homepage Settings')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header">Homepage Settings - Manufacturing Excellence Section</div>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
        <div class="card card-primary">
            <form class="form-horizontal" novalidate action="{{route('admin.manufacturing.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Title -->
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 control-label">Title<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Enter section title" value="{{@$section->title}}" required>
                            <small class="form-text text-muted">Example: Built on Manufacturing Excellence</small>
                        </div>
                    </div>

                    <!-- Title Highlight -->
                    <div class="form-group row">
                        <label for="title_highlight" class="col-sm-3 control-label">Title Highlight</label>
                        <div class="col-sm-6">
                            <input type="text" id="title_highlight" name="title_highlight" class="form-control" placeholder="Enter text to highlight" value="{{@$section->title_highlight}}">
                            <small class="form-text text-muted">This text will be shown in italic/emphasized style. Example: Manufacturing Excellence</small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-6">
                            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter section description">{{@$section->description}}</textarea>
                            <small class="form-text text-muted">Detailed description about manufacturing excellence.</small>
                        </div>
                    </div>

                    <!-- Button Text -->
                    <div class="form-group row">
                        <label for="button_text" class="col-sm-3 control-label">Button Text</label>
                        <div class="col-sm-6">
                            <input type="text" id="button_text" name="button_text" class="form-control" placeholder="Enter button text" value="{{@$section->button_text}}">
                            <small class="form-text text-muted">Example: See How It's Made</small>
                        </div>
                    </div>

                    <!-- Button Link -->
                    <div class="form-group row">
                        <label for="button_link" class="col-sm-3 control-label">Button Link</label>
                        <div class="col-sm-6">
                            <input type="text" id="button_link" name="button_link" class="form-control" placeholder="Enter button URL" value="{{@$section->button_link}}">
                            <small class="form-text text-muted">Example: /#manufacturing or https://example.com/page</small>
                        </div>
                    </div>

                    <!-- Background Image -->
                    <div class="form-group row">
                        <label for="background_image" class="col-sm-3 control-label">Background Image</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <input type="file" name="background_image" id="imagefile" accept="image/*" class="file-input">
                                </div>
                                <input type="text" class="form-control" disabled id="disabled_file_path" placeholder="Background Image" value="{{ @$section->background_image }}">
                                <div class="input-group-append">
                                    <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Recommended size: 1920x800px or larger. Formats: JPEG, PNG, WebP (Max: 5MB)</small>
                            
                            @if($section->background_image)
                            <div class="mt-3">
                                <p><strong>Current Image:</strong></p>
                                <img style="max-width:300px;height:auto;" src="/storage/{{ $section->background_image }}" alt="Current Background">
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Is Active -->
                    <div class="form-group row">
                        <label for="is_active" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" {{ @$section->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Display this section on the website)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-group row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="ft-save mr-1"></i> Update Section
                            </button>
                            <a href="{{route('admin.manufacturing.index')}}" class="btn btn-secondary ml-2">
                                <i class="ft-x mr-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
