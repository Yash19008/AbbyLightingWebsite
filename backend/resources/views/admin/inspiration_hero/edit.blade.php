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



                    <!-- Background Image -->
                    <div class="form-group row">
                        <label for="background_image" class="col-sm-3 control-label">Banner Background Image</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <input type="file" name="background_image" id="imagefile" accept="image/*" class="file-input">
                                </div>
                                <input type="text" class="form-control" disabled id="disabled_file_path" placeholder="Upload Banner Image" value="{{ @$section->background_image }}">
                                <div class="input-group-append">
                                    <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Recommended: High quality landscape image (JPEG, PNG, WebP). If not uploaded, the default theme banner image (/images/ins.png) is used.</small>
                            
                            @if(!empty($section->background_image))
                            <div class="mt-3">
                                <p><strong>Current Image:</strong></p>
                                <img style="max-width:350px;max-height:180px;object-fit:cover;border-radius:4px;border:1px solid #ddd;" src="/storage/{{ $section->background_image }}" alt="Current Banner Image">
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
