@extends('admin.page')

@section('title', 'Homepage Settings - Catalogue Section')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header">Homepage Settings - Find the Right Catalogue Section</div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
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
            <form class="form-horizontal" novalidate action="{{route('admin.home_catalogue.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Title -->
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 control-label">Title<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Enter section title" value="{{ old('title', @$section->title ?? 'Find the right catalogue.') }}" required>
                            <small class="form-text text-muted">Example: Find the right catalogue.</small>
                        </div>
                    </div>

                    <!-- Title Highlight -->
                    <div class="form-group row">
                        <label for="title_highlight" class="col-sm-3 control-label">Title Highlight</label>
                        <div class="col-sm-6">
                            <input type="text" id="title_highlight" name="title_highlight" class="form-control" placeholder="Enter text to highlight" value="{{ old('title_highlight', @$section->title_highlight ?? 'catalogue.') }}">
                            <small class="form-text text-muted">This text will be shown in italic/emphasized gold style. Example: catalogue.</small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-6">
                            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter section description">{{ old('description', @$section->description ?? 'Explore our complete collection of architectural, decorative and outdoor lighting, with detailed specifications for every luminaire.') }}</textarea>
                            <small class="form-text text-muted">Paragraph text displayed beneath the title.</small>
                        </div>
                    </div>

                    <!-- Button Text -->
                    <div class="form-group row">
                        <label for="button_text" class="col-sm-3 control-label">Button Text</label>
                        <div class="col-sm-6">
                            <input type="text" id="button_text" name="button_text" class="form-control" placeholder="Enter button text" value="{{ old('button_text', @$section->button_text ?? 'Browse the Library') }}">
                            <small class="form-text text-muted">Example: Browse the Library</small>
                        </div>
                    </div>

                    <!-- Button Link -->
                    <div class="form-group row">
                        <label for="button_link" class="col-sm-3 control-label">Button Link</label>
                        <div class="col-sm-6">
                            <input type="text" id="button_link" name="button_link" class="form-control" placeholder="Enter button URL" value="{{ old('button_link', @$section->button_link ?? '/#contact') }}">
                            <small class="form-text text-muted">Example: /#contact, /catalogues or https://example.com/catalogue</small>
                        </div>
                    </div>

                    <!-- Background Image -->
                    <div class="form-group row">
                        <label for="background_image" class="col-sm-3 control-label">Background / Book Image</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <input type="file" name="background_image" id="imagefile" accept="image/*" class="file-input">
                                </div>
                                <input type="text" class="form-control" disabled id="disabled_file_path" placeholder="Upload Image" value="{{ @$section->background_image }}">
                                <div class="input-group-append">
                                    <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Recommended: High quality image (JPEG, PNG, WebP). If not uploaded, default image will be displayed.</small>
                            
                            @if(!empty($section->background_image))
                            <div class="mt-3">
                                <p><strong>Current Custom Image:</strong></p>
                                <img style="max-width:300px;height:auto;border-radius:4px;border:1px solid #ddd;" src="/storage/{{ $section->background_image }}" alt="Current Background">
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Is Active -->
                    <div class="form-group row">
                        <label for="is_active" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" {{ (!isset($section) || @$section->is_active == 'yes') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Display this section on homepage)
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
                            <a href="{{route('admin.home_catalogue.edit', 1)}}" class="btn btn-secondary ml-2">
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
