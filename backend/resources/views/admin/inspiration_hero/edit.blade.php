@extends('admin.page')

@section('title', 'Inspiration Page Settings - Hero Banner')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header">Inspiration Page - Hero Banner Settings</div>
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
            <form class="form-horizontal" novalidate action="{{route('admin.inspiration_hero.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Title / Heading -->
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 control-label">Heading<i class="text-danger">*</i></label>
                        <div class="col-sm-6">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Enter banner heading" value="{{ old('title', @$section->title ?? 'Ideas, stories & inspiration') }}" required>
                            <small class="form-text text-muted">Example: Ideas, stories &amp; inspiration</small>
                        </div>
                    </div>

                    <!-- Title Highlight / Subheading -->
                    <div class="form-group row">
                        <label for="title_highlight" class="col-sm-3 control-label">Subheading (Italic Highlight)</label>
                        <div class="col-sm-6">
                            <input type="text" id="title_highlight" name="title_highlight" class="form-control" placeholder="Enter italic highlight text" value="{{ old('title_highlight', @$section->title_highlight ?? 'Insights') }}">
                            <small class="form-text text-muted">This text appears below the heading in gold italic font. Example: Insights</small>
                        </div>
                    </div>



                    <!-- Responsive Banner Images Header -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label font-weight-bold text-uppercase text-secondary">
                            <i class="ft-image mr-1"></i> Responsive Banner Images
                        </label>
                        <div class="col-sm-6">
                            <small class="text-muted">Upload tailored images for desktop, tablet, and mobile screens. Tablet and mobile automatically fall back to desktop image if left empty.</small>
                        </div>
                    </div>

                    <!-- Desktop Image -->
                    <div class="form-group row">
                        <label for="background_image" class="col-sm-3 control-label">Desktop Image <small class="text-muted">(Min 1920x600px)</small></label>
                        <div class="col-sm-6">
                            <input type="file" name="background_image" id="background_image" accept="image/*" class="form-control-file border p-1 rounded">
                            <small class="form-text text-muted">High quality landscape image (JPEG, PNG, WebP). Default: <code>/images/ins.png</code></small>
                            @if(!empty($section->background_image))
                            <div class="mt-2 p-1 border rounded bg-light">
                                <small class="font-weight-bold text-muted d-block mb-1">Current Desktop Image:</small>
                                <img style="max-width:320px;height:auto;border-radius:4px;border:1px solid #ddd;" src="/storage/{{ $section->background_image }}" alt="Current Desktop Banner">
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
                                <img style="max-width:320px;height:auto;border-radius:4px;border:1px solid #ddd;" src="/storage/{{ $section->image_tablet }}" alt="Current Tablet Banner">
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
                                <img style="max-width:320px;height:auto;border-radius:4px;border:1px solid #ddd;" src="/storage/{{ $section->image_mobile }}" alt="Current Mobile Banner">
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Is Active -->
                    <div class="form-group row">
                        <label for="is_active" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" {{ (!isset($section) || @$section->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Display banner on the Inspiration page)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-group row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="ft-save mr-1"></i> Save Changes
                            </button>
                            <a href="{{route('admin.inspiration_hero.edit', 1)}}" class="btn btn-secondary ml-2">
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
