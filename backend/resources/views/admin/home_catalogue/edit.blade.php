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

                    <!-- Background Images Header -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label font-weight-bold text-uppercase text-secondary">
                            <i class="ft-image mr-1"></i> Section Images
                        </label>
                        <div class="col-sm-6">
                            <small class="text-muted">Upload optimized images for each device screen size. Tablet and mobile will fall back to desktop image if not provided.</small>
                        </div>
                    </div>

                    <!-- Desktop Image -->
                    <div class="form-group row">
                        <label for="background_image" class="col-sm-3 control-label">Desktop Image <small class="text-muted">(Min 1920x600px)</small></label>
                        <div class="col-sm-6">
                            <input type="file" name="background_image" id="background_image" accept="image/*" class="form-control-file border p-1 rounded">
                            <small class="form-text text-muted">Recommended: High quality landscape image (JPEG, PNG, WebP).</small>
                            @if(!empty($section->background_image))
                            <div class="mt-2 p-1 border rounded bg-light">
                                <small class="font-weight-bold text-muted d-block mb-1">Current Desktop Image:</small>
                                <img style="max-width:280px;height:auto;border-radius:4px;border:1px solid #ddd;" src="/storage/{{ $section->background_image }}" alt="Current Desktop Image">
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tablet Image -->
                    <div class="form-group row">
                        <label for="image_tablet" class="col-sm-3 control-label">Tablet Image <small class="text-muted">(Min 1024x600px)</small></label>
                        <div class="col-sm-6">
                            <input type="file" name="image_tablet" id="image_tablet" accept="image/*" class="form-control-file border p-1 rounded">
                            <small class="form-text text-muted">Optional: Displayed on screens up to 1024px width.</small>
                            @if(!empty($section->image_tablet))
                            <div class="mt-2 p-1 border rounded bg-light">
                                <small class="font-weight-bold text-muted d-block mb-1">Current Tablet Image:</small>
                                <img style="max-width:280px;height:auto;border-radius:4px;border:1px solid #ddd;" src="/storage/{{ $section->image_tablet }}" alt="Current Tablet Image">
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Mobile Image -->
                    <div class="form-group row">
                        <label for="image_mobile" class="col-sm-3 control-label">Mobile Image <small class="text-muted">(Min 768x600px)</small></label>
                        <div class="col-sm-6">
                            <input type="file" name="image_mobile" id="image_mobile" accept="image/*" class="form-control-file border p-1 rounded">
                            <small class="form-text text-muted">Optional: Displayed on mobile screens up to 640px width.</small>
                            @if(!empty($section->image_mobile))
                            <div class="mt-2 p-1 border rounded bg-light">
                                <small class="font-weight-bold text-muted d-block mb-1">Current Mobile Image:</small>
                                <img style="max-width:280px;height:auto;border-radius:4px;border:1px solid #ddd;" src="/storage/{{ $section->image_mobile }}" alt="Current Mobile Image">
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
