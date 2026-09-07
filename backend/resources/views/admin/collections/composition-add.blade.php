@extends('admin.page')
@section('title', 'Add Composition — ' . $collection->name)
@php $main_module = 'Collections'; @endphp

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Add Composition Card</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.collections.index') }}">Collections</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.collections.edit', $collection->slug) }}?tab=compositions">{{ $collection->name }}</a>
                    </li>
                    <li class="breadcrumb-item active">Add Composition</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.collections.composition-items.store', $collection->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-images mr-1"></i> Composition Card Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">Composition Image <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                               id="image" name="image" accept="image/*" required>
                                        <label class="custom-file-label" for="image">Choose image…</label>
                                    </div>
                                </div>
                                @error('image')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                <small class="form-text text-info"><i class="fas fa-info-circle"></i> <strong>Recommended Size:</strong> 720 × 860 px (Portrait / 5:6 ratio). Max 5MB.</small>
                            </div>

                            <div class="form-group">
                                <label for="description">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3" required
                                          placeholder="e.g., Pastel hues that settle gently into the space.">{{ old('description') }}</textarea>
                                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label for="products">Product Names <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('products') is-invalid @enderror"
                                       id="products" name="products" value="{{ old('products') }}" required
                                       placeholder="e.g., Symphony II · Symphony VII">
                                @error('products')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                <small class="form-text text-muted">Separate multiple products with · symbol</small>
                            </div>

                            <div class="form-group">
                                <label for="order">Display Order <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                       id="order" name="order"
                                       value="{{ old('order', $collection->compositionsSection ? $collection->compositionsSection->items->count() + 1 : 1) }}"
                                       required min="0" style="max-width:120px">
                                @error('order')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                <small class="form-text text-muted">Controls position in carousel</small>
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
                                <i class="fas fa-save mr-1"></i> Save Composition Card
                            </button>
                            <a href="{{ route('admin.collections.edit', $collection->slug) }}?tab=compositions" class="btn btn-default ml-2">
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