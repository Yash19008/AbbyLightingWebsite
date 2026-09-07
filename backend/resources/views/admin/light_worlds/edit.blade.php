@extends('admin.page')

@section('title', ($method ?? 'Add') . ' World of Light')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12 col-md-8">
        <div class="content-header">{{ ($method ?? 'Add') }} World of Light</div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form id="{{ $frn_id ?? 'light_world_form' }}" action="{{ $action }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $world->name ?? '') }}" required
                                placeholder="e.g. Architectural">
                        </div>

                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" class="form-control" id="link" name="link"
                                value="{{ old('link', $world->link ?? '') }}"
                                placeholder="e.g. /decorative-products or /#arrivals">
                            <small class="form-text text-muted">The URL this card links to on the homepage.</small>
                        </div>

                        <div class="form-group">
                            <label for="light_of_image">Light Off Image</label>
                            @if(isset($world) && $world->light_of_image)
                                @php
                                    $offUrl = (str_starts_with($world->light_of_image, 'http') || str_starts_with($world->light_of_image, '/images') || str_starts_with($world->light_of_image, 'images/'))
                                        ? $world->light_of_image
                                        : asset('storage/' . $world->light_of_image);
                                @endphp
                                <div class="mb-2">
                                    <img src="{{ $offUrl }}" alt="Current Light Off Image"
                                        style="max-width: 250px; max-height: 180px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                    <p class="text-muted mt-1"><small>Current light-off image. Upload a new one to replace it.</small></p>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="light_of_image" name="light_of_image" accept="image/*">
                            <small class="form-text text-info"><i class="fa fa-info-circle"></i> <strong>Recommended Size:</strong> 600 × 750 px (Portrait / 4:5). Image shown when card is in default (off) state. Max 4MB.</small>
                        </div>

                        <div class="form-group">
                            <label for="light_on_image">Light On Image <small class="text-primary font-weight-bold">(Recommended: 600×750px | 4:5 Portrait)</small></label>
                            @if(isset($world) && $world->light_on_image)
                                @php
                                    $onUrl = (str_starts_with($world->light_on_image, 'http') || str_starts_with($world->light_on_image, '/images') || str_starts_with($world->light_on_image, 'images/'))
                                        ? $world->light_on_image
                                        : asset('storage/' . $world->light_on_image);
                                @endphp
                                <div class="mb-2">
                                    <img src="{{ $onUrl }}" alt="Current Light On Image"
                                        style="max-width: 250px; max-height: 180px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                    <p class="text-muted mt-1"><small>Current light-on image. Upload a new one to replace it.</small></p>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="light_on_image" name="light_on_image" accept="image/*">
                            <small class="form-text text-info"><i class="fa fa-info-circle"></i> <strong>Recommended Size:</strong> 600 × 750 px (Portrait / 4:5 - matches off state angle). Image shown on hover. Max 4MB.</small>
                        </div>

                        <div class="form-group">
                            <label for="sort_order">Sort Order <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order"
                                value="{{ old('sort_order', $world->sort_order ?? 0) }}" required
                                style="max-width: 150px;">
                            <small class="form-text text-muted">Lower numbers appear first.</small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ ($method ?? 'Add') === 'Edit' ? 'Update' : 'Add' }} World
                            </button>
                            <a href="{{ route('light_worlds_admin') }}" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
