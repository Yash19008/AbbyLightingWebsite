@extends('admin.page')
@section('title', 'Add Place Item — ' . $collection->name)
@php $main_module = 'Collections'; @endphp

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Place Item</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.collections.index') }}">Collections</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.collections.edit', $collection->slug) }}?tab=places">{{ $collection->name }}</a>
                    </li>
                    <li class="breadcrumb-item active">Add Place</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.collections.place-items.store', $collection->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-map-marker-alt mr-1"></i> Place Item Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="place_name">Place Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('place_name') is-invalid @enderror"
                                       id="place_name" name="place_name" value="{{ old('place_name') }}"
                                       placeholder="e.g., Living Room, Bedroom, Kitchen" required>
                                @error('place_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                <small class="form-text text-muted">Name of the room or space</small>
                            </div>

                            <div class="form-group">
                                <label for="image">Place Image <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                               id="image" name="image" accept="image/*" required>
                                        <label class="custom-file-label" for="image">Choose image…</label>
                                    </div>
                                </div>
                                @error('image')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                <small class="form-text text-info"><i class="fas fa-info-circle"></i> <strong>Recommended Size:</strong> 720 × 860 px (Portrait / 5:6 ratio). Max: 5MB</small>
                            </div>

                            <div class="form-group">
                                <label for="description">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3" required
                                          placeholder="e.g., Warm tones create a cozy atmosphere…">{{ old('description') }}</textarea>
                                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label for="order">Display Order <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                       id="order" name="order" value="{{ old('order', 0) }}" min="0" required style="max-width:120px">
                                @error('order')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                <small class="form-text text-muted">Lower numbers appear first</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold d-block">Visibility</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active"
                                           name="is_active" value="1" {{ old('is_active', false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active (Visible on Frontend)</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Save Place Item
                            </button>
                            <a href="{{ route('admin.collections.edit', $collection->slug) }}?tab=places" class="btn btn-default ml-2">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
document.getElementById('image').addEventListener('change', function() {
    this.nextElementSibling.textContent = this.files[0] ? this.files[0].name : 'Choose image…';
});
</script>
@endpush
@endsection