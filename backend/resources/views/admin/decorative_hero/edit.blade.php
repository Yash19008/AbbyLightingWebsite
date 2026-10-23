@extends('admin.page')

@section('title', 'Decorative Lights - Hero Banner Settings')

@section('content')
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        {{-- <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Decorative Lights - Hero Banner Settings</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('decorative_product_admin') }}">Decorative</a></li>
                        <li class="breadcrumb-item active">Hero Banner</li>
                    </ol>
                </div>
            </div>
        </div>
    </div> --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible mb-2" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Please fix the errors below:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="content-body">
            <div class="card">
                <div class="card-header pb-0 border-bottom mb-3">
                    <h4 class="card-title">Edit Decorative Hero Banner</h4>
                    <p class="text-muted mb-0">Configure heading, styled accent text, description, and responsive images for
                        the Decorative Lights listing page.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.decorative_hero.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Heading -->
                            <div class="col-md-6 form-group">
                                <label for="heading" class="font-weight-bold">Heading <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="heading" name="heading" class="form-control"
                                    placeholder="e.g. Decorative"
                                    value="{{ old('heading', @$section->heading ?? 'Decorative') }}" required>
                                <small class="form-text text-muted">Primary heading text. Example:
                                    <code>Decorative</code></small>
                            </div>

                            <!-- Styled Heading (em / italic accent) -->
                            <div class="col-md-6 form-group">
                                <label for="styled_heading" class="font-weight-bold">Styled Text in Heading</label>
                                <input type="text" id="styled_heading" name="styled_heading" class="form-control"
                                    placeholder="e.g. Lights"
                                    value="{{ old('styled_heading', @$section->styled_heading ?? 'Lights') }}">
                                <small class="form-text text-muted">Styled accent text rendered in gold italic serif.
                                    Example: <code>Lights</code></small>
                            </div>

                            <!-- Live Preview Callout -->
                            <div class="col-md-12 mb-3">
                                <div class="p-3 rounded" style="background: #171512; color: #fff;">
                                    <small class="text-uppercase font-weight-bold text-muted d-block mb-1">Heading
                                        Preview</small>
                                    <span style="font-size: 26px; font-family: sans-serif; font-weight: 400;">
                                        <span id="preview-heading">{{ @$section->heading ?? 'Decorative' }}</span>
                                        <em id="preview-styled"
                                            style="color: #f6c177; font-family: 'Times New Roman', serif; font-style: italic; margin-left: 6px;">{{ @$section->styled_heading ?? 'Lights' }}</em>
                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 form-group">
                                <label for="description" class="font-weight-bold">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="4"
                                    placeholder="Enter hero description...">{{ old('description', @$section->description ?? 'Sculptural pendants, wall lights, floor and table lamps across the Symphony, Quarry and Neoma collections — cast concrete discs, colour-blocked forms and lunar orbs, all made to order.') }}</textarea>
                                <small class="form-text text-muted">Introductory paragraph displayed below the
                                    heading.</small>
                            </div>

                            <!-- Images Section Header -->
                            <div class="col-12 mt-2 mb-2">
                                <h5 class="text-uppercase text-secondary font-weight-bold border-bottom pb-1">
                                    <i class="ft-image mr-1"></i> Responsive Hero Images
                                </h5>
                                <small class="text-muted d-block">Upload optimized images for each device screen size. If
                                    tablet or mobile images are not provided, desktop image will be used as a
                                    fallback.</small>
                            </div>

                            <!-- Desktop Image -->
                            <div class="col-md-4 form-group">
                                <label for="image_desktop" class="font-weight-bold">
                                    Desktop Image <small class="text-muted">(Min 1920x600px)</small>
                                </label>
                                <input type="file" name="image_desktop" id="image_desktop"
                                    class="form-control-file border p-1 rounded w-100" accept="image/*">
                                @if (!empty($section->image_desktop))
                                    <div class="mt-2 p-1 border rounded bg-light">
                                        <small class="font-weight-bold text-muted d-block mb-1">Current Desktop
                                            Image:</small>
                                        <img src="/storage/{{ $section->image_desktop }}" alt="Desktop Banner"
                                            style="max-width: 100%; height: 120px; object-fit: cover; border-radius: 4px;"
                                            class="border">
                                    </div>
                                @else
                                    <small class="form-text text-muted">Using default theme image:
                                        <code>/images/decorative/hero.png</code></small>
                                @endif
                            </div>

                            <!-- Tablet Image -->
                            <div class="col-md-4 form-group">
                                <label for="image_tablet" class="font-weight-bold">
                                    Tablet Image <small class="text-muted">(Min 1024x600px)</small>
                                </label>
                                <input type="file" name="image_tablet" id="image_tablet"
                                    class="form-control-file border p-1 rounded w-100" accept="image/*">
                                @if (!empty($section->image_tablet))
                                    <div class="mt-2 p-1 border rounded bg-light">
                                        <small class="font-weight-bold text-muted d-block mb-1">Current Tablet
                                            Image:</small>
                                        <img src="/storage/{{ $section->image_tablet }}" alt="Tablet Banner"
                                            style="max-width: 100%; height: 120px; object-fit: cover; border-radius: 4px;"
                                            class="border">
                                    </div>
                                @else
                                    <small class="form-text text-muted">Optional (Falls back to Desktop image)</small>
                                @endif
                            </div>

                            <!-- Mobile Image -->
                            <div class="col-md-4 form-group">
                                <label for="image_mobile" class="font-weight-bold">
                                    Mobile Image <small class="text-muted">(Min 768x600px)</small>
                                </label>
                                <input type="file" name="image_mobile" id="image_mobile"
                                    class="form-control-file border p-1 rounded w-100" accept="image/*">
                                @if (!empty($section->image_mobile))
                                    <div class="mt-2 p-1 border rounded bg-light">
                                        <small class="font-weight-bold text-muted d-block mb-1">Current Mobile
                                            Image:</small>
                                        <img src="/storage/{{ $section->image_mobile }}" alt="Mobile Banner"
                                            style="max-width: 100%; height: 120px; object-fit: cover; border-radius: 4px;"
                                            class="border">
                                    </div>
                                @else
                                    <small class="form-text text-muted">Optional (Falls back to Desktop image)</small>
                                @endif
                            </div>

                            <!-- Active Switch -->
                            <div class="col-12 mt-2 form-group">
                                <label class="font-weight-bold d-block">Status</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        value="1" {{ !isset($section) || @$section->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active (Enable hero
                                        banner)</label>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-4 mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ft-save mr-1"></i> Save Changes
                        </button>
                        <a href="{{ route('decorative_product_admin') }}" class="btn btn-secondary ml-1">
                            <i class="ft-arrow-left mr-1"></i> Back to Products
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headingInput = document.getElementById('heading');
            const styledInput = document.getElementById('styled_heading');
            const previewHeading = document.getElementById('preview-heading');
            const previewStyled = document.getElementById('preview-styled');

            if (headingInput && previewHeading) {
                headingInput.addEventListener('input', function() {
                    previewHeading.textContent = this.value || 'Decorative';
                });
            }

            if (styledInput && previewStyled) {
                styledInput.addEventListener('input', function() {
                    previewStyled.textContent = this.value || 'Lights';
                });
            }
        });
    </script>
@endsection
