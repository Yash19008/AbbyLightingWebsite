@extends('admin.page')

@section('title', 'Edit Collection: ' . $collection->name)

@php
    $main_module = 'Collections';
@endphp

@section('extra_css')
<style>
    .table td, .table th {
        vertical-align: middle !important;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Collection</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Sections Management with Tabs -->
        <div class="row mt-2" id="manage-sections">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-layer-group"></i> Manage Collection & Sections
                        </h3>
                    </div>
                    
                    <div class="card-body p-0">
                        @php
                            // Get the active tab from query parameter, default to 'general'
                            $activeTab = request()->query('tab', 'general');
                        @endphp

                        <!-- Section Tabs Navigation -->
                        <ul class="nav nav-tabs" id="sectionTabs" role="tablist" style="padding: 15px 15px 0 15px; margin: 0; border-bottom: 1px solid #dee2e6;">
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'general' ? 'active' : '' }}" id="general-tab" data-toggle="tab" href="#general-section" role="tab" aria-controls="general-section" aria-selected="{{ $activeTab === 'general' ? 'true' : 'false' }}">
                                    <i class="fas fa-info-circle"></i> General Info
                                    <span class="badge badge-success ml-1">✓</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'hero' ? 'active' : '' }}" id="hero-tab" data-toggle="tab" href="#hero-section" role="tab" aria-controls="hero-section" aria-selected="{{ $activeTab === 'hero' ? 'true' : 'false' }}">
                                    <i class="fas fa-image"></i> Hero Section
                                    @if($collection->heroSection)
                                        <span class="badge badge-success ml-1">✓</span>
                                    @else
                                        <span class="badge badge-secondary ml-1">+</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'parameters' ? 'active' : '' }}" id="parameters-tab" data-toggle="tab" href="#parameters-section" role="tab" aria-controls="parameters-section" aria-selected="{{ $activeTab === 'parameters' ? 'true' : 'false' }}">
                                    <i class="fas fa-cog"></i> Parameters
                                    @if($collection->parametersSection)
                                        <span class="badge badge-success ml-1">✓</span>
                                    @else
                                        <span class="badge badge-secondary ml-1">+</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'compositions' ? 'active' : '' }}" id="compositions-tab" data-toggle="tab" href="#compositions-section" role="tab" aria-controls="compositions-section" aria-selected="{{ $activeTab === 'compositions' ? 'true' : 'false' }}">
                                    <i class="fas fa-images"></i> Compositions
                                    @if($collection->compositionsSection)
                                        <span class="badge badge-success ml-1">✓</span>
                                    @else
                                        <span class="badge badge-secondary ml-1">+</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'tones' ? 'active' : '' }}" id="tones-tab" data-toggle="tab" href="#tones-section" role="tab" aria-controls="tones-section" aria-selected="{{ $activeTab === 'tones' ? 'true' : 'false' }}">
                                    <i class="fas fa-palette"></i> Tones
                                    @if($collection->tonesSection)
                                        <span class="badge badge-success ml-1">✓</span>
                                    @else
                                        <span class="badge badge-secondary ml-1">+</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'places' ? 'active' : '' }}" id="places-tab" data-toggle="tab" href="#places-section" role="tab" aria-controls="places-section" aria-selected="{{ $activeTab === 'places' ? 'true' : 'false' }}">
                                    <i class="fas fa-map-marker-alt"></i> Places
                                    @if($collection->placesSection)
                                        <span class="badge badge-success ml-1">✓</span>
                                    @else
                                        <span class="badge badge-secondary ml-1">+</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'spread-drop' ? 'active' : '' }}" id="spread-drop-tab" data-toggle="tab" href="#spread-drop-section" role="tab" aria-controls="spread-drop-section" aria-selected="{{ $activeTab === 'spread-drop' ? 'true' : 'false' }}">
                                    <i class="fas fa-lightbulb"></i> Spread & Drop
                                    @if($collection->spreadDropSection)
                                        <span class="badge badge-success ml-1">✓</span>
                                    @else
                                        <span class="badge badge-secondary ml-1">+</span>
                                    @endif
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="sectionTabContent">
                            <!-- General Info Tab (First Tab) -->
                            <div class="tab-pane fade {{ $activeTab === 'general' ? 'show active' : '' }} p-4" id="general-section" role="tabpanel" aria-labelledby="general-tab">
                                <form action="{{ route('admin.collections.update', $collection->slug) }}" method="POST" id="collection-details-form">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="card card-outline card-primary shadow-sm mb-4">
                                                <div class="card-header">
                                                    <h3 class="card-title font-weight-bold"><i class="fas fa-edit mr-1"></i> Collection Details</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="name">Collection Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                               id="name" name="name" value="{{ old('name', $collection->name) }}" required>
                                                        @error('name')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="slug">URL Slug <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                                               id="slug" name="slug" value="{{ old('slug', $collection->slug) }}" required>
                                                        <small class="form-text text-muted">URL: /collections/<strong>{{ $collection->slug }}</strong></small>
                                                        @error('slug')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label for="short_description">Short Description</label>
                                                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                                                  id="short_description" name="short_description" rows="3"
                                                                  placeholder="Short description for collection cards and highlights...">{{ old('short_description', $collection->short_description) }}</textarea>
                                                        @error('short_description')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card card-outline card-secondary shadow-sm mb-4">
                                                <div class="card-header">
                                                    <h3 class="card-title font-weight-bold"><i class="fas fa-search mr-1"></i> SEO Settings</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="meta_title">Meta Title</label>
                                                        <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                                               value="{{ old('meta_title', $collection->meta_title) }}"
                                                               placeholder="Page title for search engines">
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label for="meta_description">Meta Description</label>
                                                        <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                                                  placeholder="Meta description for search engines...">{{ old('meta_description', $collection->meta_description) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card card-outline card-info shadow-sm mb-4">
                                                <div class="card-header">
                                                    <h3 class="card-title font-weight-bold"><i class="fas fa-toggle-on mr-1"></i> Status & Display</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="d-block">Visibility</label>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="is_active" 
                                                                   name="is_active" value="1" {{ old('is_active', $collection->is_active) ? 'checked' : '' }}>
                                                            <label class="custom-control-label font-weight-bold text-success" for="is_active">Active (Visible on Frontend)</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label for="order">Display Order</label>
                                                        <input type="number" class="form-control" id="order" name="order" 
                                                               value="{{ old('order', $collection->order) }}" min="0">
                                                        <small class="form-text text-muted">Lower numbers appear first.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-save mr-1"></i> Update Collection Details
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Hero Section Tab -->
                            <div class="tab-pane fade {{ $activeTab === 'hero' ? 'show active' : '' }} p-4" id="hero-section" role="tabpanel" aria-labelledby="hero-tab">
                                <form action="{{ route('admin.collections.store-hero', $collection->slug) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <!-- Background Image -->
                                            <div class="form-group">
                                                <label for="background_image">Background Image</label>
                                                @if($collection->heroSection && $collection->heroSection->background_image)
                                                    <div class="mb-2">
                                                        <img src="{{ $collection->heroSection->background_image_url }}" 
                                                             alt="Current background" 
                                                             style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">
                                                    </div>
                                                @endif
                                                <input type="file" class="form-control-file" id="background_image" 
                                                       name="background_image" accept="image/*">
                                                <small class="form-text text-info"><i class="fas fa-info-circle"></i> <strong>Recommended Size:</strong> 1920 × 600 px (Landscape / 16:5 ratio). Max 5MB.</small>
                                            </div>

                                            <!-- Title Prefix -->
                                            <div class="form-group">
                                                <label for="title_prefix">Title Prefix (e.g., "The")</label>
                                                <input type="text" class="form-control" id="title_prefix" name="title_prefix" 
                                                       value="{{ old('title_prefix', $collection->heroSection->title_prefix ?? '') }}" 
                                                       placeholder="The">
                                                <small class="form-text text-muted">Normal text before the highlighted title</small>
                                            </div>

                                            <!-- Title Highlight -->
                                            <div class="form-group">
                                                <label for="title_highlight">Title Highlight (Emphasized part)</label>
                                                <input type="text" class="form-control" id="title_highlight" name="title_highlight" 
                                                       value="{{ old('title_highlight', $collection->heroSection->title_highlight ?? '') }}" 
                                                       placeholder="Symphony Collection">
                                                <small class="form-text text-muted">This will be shown in italic/emphasized style</small>
                                            </div>

                                            <!-- Description -->
                                            <div class="form-group">
                                                <label for="description">Hero Description</label>
                                                <textarea class="form-control" id="hero_description" name="description" rows="4" 
                                                          placeholder="Full description paragraph for the hero section...">{{ old('description', $collection->heroSection->description ?? '') }}</textarea>
                                            </div>

                                            <!-- Status -->
                                            <div class="form-group mb-0">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="hero_is_active" 
                                                           name="is_active" value="1" 
                                                           {{ old('is_active', $collection->heroSection->is_active ?? false) ? 'checked' : '' }}>
                                                    <label class="custom-control-label font-weight-bold" for="hero_is_active">Display Hero Section on Frontend</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save Hero Section
                                        </button>
                                        @if($collection->heroSection)
                                            <span class="badge badge-success ml-2">Hero section exists</span>
                                        @else
                                            <span class="badge badge-warning ml-2">No hero section yet</span>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <!-- Parameters Section Tab -->
                            <div class="tab-pane fade {{ $activeTab === 'parameters' ? 'show active' : '' }} p-4" id="parameters-section" role="tabpanel" aria-labelledby="parameters-tab">
                                <!-- Section Settings Form -->
                                <form action="{{ route('admin.collections.store-parameters', $collection->slug) }}" method="POST" class="mb-4">
                                    @csrf
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-cog"></i> Section Settings
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="param_title">Section Title <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="param_title" name="title" 
                                                               value="{{ old('title', $collection->parametersSection->title ?? 'Four Parameters, One Composition') }}" 
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="param_is_active" 
                                                                   name="is_active" value="1" 
                                                                   {{ old('is_active', $collection->parametersSection->is_active ?? false) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="param_is_active">Display on Frontend</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="param_subtitle">Section Description</label>
                                                <textarea class="form-control" id="param_subtitle" name="subtitle" rows="2">{{ old('subtitle', $collection->parametersSection->subtitle ?? 'Every Symphony installation is built from four choices that work together as one system.') }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Save Section Settings
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Parameter Cards Table -->
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-list"></i> Parameter Cards
                                        </h5>
                                        <div class="card-tools">
                                            @if($collection->parametersSection)
                                                <a href="{{ route('admin.collections.parameter-items.add', $collection->slug) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus"></i> Add New Card
                                                </a>
                                            @else
                                                <span class="text-muted">Please save section settings first</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($collection->parametersSection && $collection->parametersSection->items->count() > 0)
                                            <table class="table data-table table-bordered w-100" data-order='[[ 0, "asc" ]]' id="parameters-table" style="width:100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 70px; min-width: 60px;" class="text-center">ORDER</th>
                                                        <th style="width: 18%; min-width: 120px;">SMALL TEXT</th>
                                                        <th style="width: 22%; min-width: 140px;">TITLE</th>
                                                        <th style="width: 35%; min-width: 200px;">DESCRIPTION</th>
                                                        <th style="width: 90px; min-width: 80px;" class="text-center">STATUS</th>
                                                        <th style="width: 110px; min-width: 100px;" class="text-center">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($collection->parametersSection->items->sortBy('order') as $item)
                                                        <tr>
                                                            <td class="text-center align-middle font-weight-bold">{{ $item->order ?? 0 }}</td>
                                                            <td class="align-middle text-muted">{{ $item->small_text ?? '-' }}</td>
                                                            <td class="align-middle font-weight-bold text-dark">{{ $item->title }}</td>
                                                            <td class="align-middle text-muted">{{ Str::limit($item->description, 80) }}</td>
                                                            <td class="text-center align-middle">
                                                                @if($item->is_active)
                                                                    <span class="badge badge-success" style="font-size: 11px;">Active</span>
                                                                @else
                                                                    <span class="badge badge-secondary" style="font-size: 11px;">Inactive</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                                                <a href="{{ route('admin.collections.parameter-items.edit', [$collection->slug, $item->id]) }}" 
                                                                   class="mx-1 text-primary" data-toggle="tooltip" title="Edit">
                                                                    <i class="ft-edit-2 font-medium-3"></i>
                                                                </a>
                                                                <form action="{{ route('admin.collections.parameter-items.delete', [$collection->slug, $item->id]) }}" 
                                                                      method="POST" style="display: inline-block;" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this card?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-link p-0 mx-1 text-danger" data-toggle="tooltip" title="Delete" style="border:none;background:none;">
                                                                        <i class="icon ft-trash-2 font-medium-3"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="text-center">Order</th>
                                                        <th>Small Text</th>
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No parameter cards yet.</p>
                                                @if($collection->parametersSection)
                                                    <a href="{{ route('admin.collections.parameter-items.add', $collection->slug) }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Add Your First Card
                                                    </a>
                                                @else
                                                    <p class="text-muted"><small>Save section settings above to start adding cards.</small></p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Compositions Section Tab -->
                            <div class="tab-pane fade {{ $activeTab === 'compositions' ? 'show active' : '' }} p-4" id="compositions-section" role="tabpanel" aria-labelledby="compositions-tab">
                                <!-- Section Settings Form -->
                                <form action="{{ route('admin.collections.store-compositions', $collection->slug) }}" method="POST" class="mb-4">
                                    @csrf
                                    
                                    <div class="card inner-section-card compositions shadow-sm">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-cubes mr-1 text-info"></i> Compositions Section Configuration
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="comp_title">Section Title <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="comp_title" name="title" 
                                                               value="{{ old('title', $collection->compositionsSection->title ?? 'Symphony Compositions') }}" 
                                                               placeholder="Symphony Compositions" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>&nbsp;</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="comp_is_active" 
                                                               name="is_active" value="1" 
                                                               {{ old('is_active', $collection->compositionsSection->is_active ?? false) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="comp_is_active">Display on Frontend</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="comp_subtitle">Section Description</label>
                                                <textarea class="form-control" id="comp_subtitle" name="subtitle" rows="2">{{ old('subtitle', $collection->compositionsSection->subtitle ?? 'Scale, rhythm and volume — architectural lighting tailored to the exact demands of your space.') }}</textarea>
                                            @if(isset($allCompositions))
                                            <div class="form-group">
                                                <label for="composition_ids">Select Compositions</label>
                                                <select class="form-control select2" id="composition_ids" name="composition_ids[]" multiple="multiple" style="width: 100%;">
                                                    @foreach($allCompositions as $composition)
                                                        <option value="{{ $composition->id }}" 
                                                            {{ isset($collection->compositions) && $collection->compositions->contains($composition->id) ? 'selected' : '' }}>
                                                            {{ $composition->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Select the master compositions to display in this collection.</small>
                                            </div>
                                            @endif
                                            <button type="submit" class="btn btn-info">
                                                <i class="fas fa-save mr-1"></i> Save Section Settings
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Composition Cards Table -->
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-list"></i> Composition Cards
                                        </h5>
                                        <div class="card-tools">
                                            @if($collection->compositionsSection)
                                                <a href="{{ route('admin.collections.composition-items.add', $collection->slug) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus"></i> Add New Card
                                                </a>
                                            @else
                                                <span class="text-muted">Please save section settings first</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($collection->compositionsSection && $collection->compositionsSection->items->count() > 0)
                                            <table class="table data-table table-bordered w-100" data-order='[[ 0, "asc" ]]' id="compositions-table" style="width:100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 70px; min-width: 60px;" class="text-center">ORDER</th>
                                                        <th style="width: 90px; min-width: 80px;" class="text-center">IMAGE</th>
                                                        <th style="width: 45%; min-width: 200px;">DESCRIPTION</th>
                                                        <th style="width: 25%; min-width: 140px;">PRODUCTS</th>
                                                        <th style="width: 90px; min-width: 80px;" class="text-center">STATUS</th>
                                                        <th style="width: 110px; min-width: 100px;" class="text-center">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($collection->compositionsSection->items->sortBy('order') as $item)
                                                        <tr>
                                                            <td class="text-center align-middle font-weight-bold">{{ $item->order ?? 0 }}</td>
                                                            <td class="img-td text-center align-middle">
                                                                @if($item->image)
                                                                    <img src="{{ $item->image_url }}" alt="Composition" 
                                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                                                @else
                                                                    <div style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border:1px solid #eee;border-radius:4px;margin:0 auto;color:#aaa;font-size:10px;">
                                                                        No Img
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td class="align-middle text-muted">{{ Str::limit($item->description, 60) }}</td>
                                                            <td class="align-middle"><code class="font-weight-bold">{{ $item->products }}</code></td>
                                                            <td class="text-center align-middle">
                                                                @if($item->is_active)
                                                                    <span class="badge badge-success" style="font-size: 11px;">Active</span>
                                                                @else
                                                                    <span class="badge badge-secondary" style="font-size: 11px;">Inactive</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                                                <a href="{{ route('admin.collections.composition-items.edit', [$collection->slug, $item->id]) }}" 
                                                                   class="mx-1 text-primary" data-toggle="tooltip" title="Edit">
                                                                    <i class="ft-edit-2 font-medium-3"></i>
                                                                </a>
                                                                <form action="{{ route('admin.collections.composition-items.delete', [$collection->slug, $item->id]) }}" 
                                                                      method="POST" style="display: inline-block;" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this card?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-link p-0 mx-1 text-danger" data-toggle="tooltip" title="Delete" style="border:none;background:none;">
                                                                        <i class="icon ft-trash-2 font-medium-3"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="text-center">Order</th>
                                                        <th class="text-center">Image</th>
                                                        <th>Description</th>
                                                        <th>Products</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No composition cards yet.</p>
                                                @if($collection->compositionsSection)
                                                    <a href="{{ route('admin.collections.composition-items.add', $collection->slug) }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Add Your First Card
                                                    </a>
                                                @else
                                                    <p class="text-muted"><small>Save section settings above to start adding cards.</small></p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
>>>>>>> 8c15f62 (feat(admin): standardize admin data tables, live status switches, and optimize responsive layout)
                            </div>



                            <!-- Tones Section Tab -->
                            <div class="tab-pane fade {{ $activeTab === 'tones' ? 'show active' : '' }} p-4" id="tones-section" role="tabpanel" aria-labelledby="tones-tab">
                                <!-- Section Settings Form -->
                                <form action="{{ route('admin.collections.store-tones', $collection->slug) }}" method="POST" class="mb-4">
                                    @csrf
                                    
                                    <div class="card inner-section-card tones shadow-sm">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-palette mr-1 text-purple"></i> Tones Section Configuration
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tones_title">Section Title <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="tones_title" name="title" 
                                                               value="{{ old('title', $collection->tonesSection->title ?? 'Symphony Tones') }}" 
                                                               placeholder="Symphony Tones" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>&nbsp;</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="tones_is_active" 
                                                               name="is_active" value="1" 
                                                               {{ old('is_active', $collection->tonesSection->is_active ?? false) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="tones_is_active">Display on Frontend</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="tones_subtitle">Section Description</label>
                                                <textarea class="form-control" id="tones_subtitle" name="subtitle" rows="2">{{ old('subtitle', $collection->tonesSection->subtitle ?? '24 shades across 4 families: Classic, Earth, Radiant, and Architectural') }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-purple">
                                                <i class="fas fa-save mr-1"></i> Save Section Settings
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Tone Families Table -->
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-swatchbook"></i> Color Families
                                        </h5>
                                        <div class="card-tools">
                                            @if($collection->tonesSection)
                                                <a href="{{ route('admin.collections.tone-families.add', $collection->slug) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus"></i> Add New Family
                                                </a>
                                            @else
                                                <span class="text-muted">Please save section settings first</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($collection->tonesSection && $collection->tonesSection->families->count() > 0)
                                            <table class="table data-table table-bordered w-100" data-order='[[ 0, "asc" ]]' id="tone-families-table" style="width:100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 70px; min-width: 60px;" class="text-center">ORDER</th>
                                                        <th style="width: 90px; min-width: 80px;" class="text-center">IMAGE</th>
                                                        <th style="width: 25%; min-width: 140px;">FAMILY NAME</th>
                                                        <th style="width: 35%; min-width: 180px;">COLORS</th>
                                                        <th style="width: 90px; min-width: 80px;" class="text-center">STATUS</th>
                                                        <th style="width: 110px; min-width: 100px;" class="text-center">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($collection->tonesSection->families->sortBy('order') as $family)
                                                        <tr>
                                                            <td class="text-center align-middle font-weight-bold">{{ $family->order ?? 0 }}</td>
                                                            <td class="img-td text-center align-middle">
                                                                @if($family->image)
                                                                    <img src="{{ $family->image_url }}" 
                                                                         alt="{{ $family->title }}" 
                                                                         style="width: 50px; height: 38px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                                                @else
                                                                    <div style="width:50px;height:38px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border:1px solid #eee;border-radius:4px;margin:0 auto;color:#aaa;font-size:10px;">
                                                                        No Img
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td class="align-middle font-weight-bold text-dark">{{ $family->title }}</td>
                                                            <td class="align-middle">
                                                                <div class="d-flex flex-wrap align-items-center" style="gap: 4px;">
                                                                    @foreach($family->colors->take(6) as $color)
                                                                        <div style="width: 20px; height: 20px; background: {{ $color->css_value }}; border: 1px solid #ced4da; border-radius: 3px; box-shadow: 0 1px 2px rgba(0,0,0,0.08);" title="{{ $color->name }}"></div>
                                                                    @endforeach
                                                                    @if($family->colors->count() > 6)
                                                                        <small class="text-muted font-weight-bold">+{{ $family->colors->count() - 6 }}</small>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                @if($family->is_active)
                                                                    <span class="badge badge-success" style="font-size: 11px;">Active</span>
                                                                @else
                                                                    <span class="badge badge-secondary" style="font-size: 11px;">Inactive</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                                                <a href="{{ route('admin.collections.tone-families.edit', [$collection->slug, $family->id]) }}" 
                                                                   class="mx-1 text-primary" data-toggle="tooltip" title="Edit">
                                                                    <i class="ft-edit-2 font-medium-3"></i>
                                                                </a>
                                                                <form action="{{ route('admin.collections.tone-families.delete', [$collection->slug, $family->id]) }}" 
                                                                      method="POST" style="display: inline-block;" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this tone family?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-link p-0 mx-1 text-danger" data-toggle="tooltip" title="Delete" style="border:none;background:none;">
                                                                        <i class="icon ft-trash-2 font-medium-3"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="text-center">Order</th>
                                                        <th class="text-center">Image</th>
                                                        <th>Family Name</th>
                                                        <th>Colors</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-swatchbook fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No color families yet.</p>
                                                @if($collection->tonesSection)
                                                    <a href="{{ route('admin.collections.tone-families.add', $collection->slug) }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Add Your First Family
                                                    </a>
                                                @else
                                                    <p class="text-muted"><small>Save section settings above to start adding color families.</small></p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Places Section Tab -->
                            <div class="tab-pane fade {{ $activeTab === 'places' ? 'show active' : '' }} p-4" id="places-section" role="tabpanel" aria-labelledby="places-tab">
                                
                                <!-- Section Settings Form -->
                                <form action="{{ route('admin.collections.store-places', $collection->slug) }}" method="POST" class="mb-4">
                                    @csrf
                                    
                                    <div class="card inner-section-card places shadow-sm">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-map-marker-alt mr-1 text-success"></i> Places Section Configuration
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="places_title">Section Title <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="places_title" name="title" 
                                                               value="{{ old('title', $collection->placesSection->title ?? 'Symphony in Place') }}" 
                                                               placeholder="Symphony in Place" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>&nbsp;</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="places_is_active" 
                                                               name="is_active" value="1" 
                                                               {{ old('is_active', $collection->placesSection->is_active ?? false) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="places_is_active">Display on Frontend</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="places_subtitle">Section Description</label>
                                                <textarea class="form-control" id="places_subtitle" name="subtitle" rows="2">{{ old('subtitle', $collection->placesSection->subtitle ?? 'One system, composed differently for every room') }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save mr-1"></i> Save Section Settings
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Place Items List -->
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-map-marked-alt"></i> Place Items
                                        </h5>
                                        <div class="card-tools">
                                            @if($collection->placesSection)
                                                <a href="{{ route('admin.collections.place-items.add', $collection->slug) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus"></i> Add Place Item
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($collection->placesSection && $collection->placesSection->items->count() > 0)
                                            <table class="table data-table table-bordered w-100" data-order='[[ 0, "asc" ]]' id="places-table" style="width:100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 70px; min-width: 60px;" class="text-center">ORDER</th>
                                                        <th style="width: 90px; min-width: 80px;" class="text-center">IMAGE</th>
                                                        <th style="width: 25%; min-width: 140px;">PLACE NAME</th>
                                                        <th style="width: 40%; min-width: 200px;">DESCRIPTION</th>
                                                        <th style="width: 90px; min-width: 80px;" class="text-center">STATUS</th>
                                                        <th style="width: 110px; min-width: 100px;" class="text-center">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($collection->placesSection->items->sortBy('order') as $item)
                                                        <tr>
                                                            <td class="text-center align-middle font-weight-bold">{{ $item->order ?? 0 }}</td>
                                                            <td class="img-td text-center align-middle">
                                                                @if($item->image_url)
                                                                    <img src="{{ $item->image_url }}" alt="{{ $item->place_name }}" 
                                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                                                @else
                                                                    <div style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border:1px solid #eee;border-radius:4px;margin:0 auto;color:#aaa;font-size:10px;">
                                                                        No Img
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td class="align-middle font-weight-bold text-dark">{{ $item->place_name }}</td>
                                                            <td class="align-middle text-muted">{{ Str::limit($item->description, 60) }}</td>
                                                            <td class="text-center align-middle">
                                                                @if($item->is_active)
                                                                    <span class="badge badge-success" style="font-size: 11px;">Active</span>
                                                                @else
                                                                    <span class="badge badge-secondary" style="font-size: 11px;">Inactive</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                                                <a href="{{ route('admin.collections.place-items.edit', [$collection->slug, $item->id]) }}" 
                                                                   class="mx-1 text-primary" data-toggle="tooltip" title="Edit">
                                                                    <i class="ft-edit-2 font-medium-3"></i>
                                                                </a>
                                                                <form action="{{ route('admin.collections.place-items.delete', [$collection->slug, $item->id]) }}" 
                                                                      method="POST" style="display: inline-block;" 
                                                                      onsubmit="return confirm('Delete this place item?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-link p-0 mx-1 text-danger" data-toggle="tooltip" title="Delete" style="border:none;background:none;">
                                                                        <i class="icon ft-trash-2 font-medium-3"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="text-center">Order</th>
                                                        <th class="text-center">Image</th>
                                                        <th>Place Name</th>
                                                        <th>Description</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No place items yet.</p>
                                                @if($collection->placesSection)
                                                    <a href="{{ route('admin.collections.place-items.add', $collection->slug) }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Add Your First Place
                                                    </a>
                                                @else
                                                    <p class="text-muted"><small>Please save section settings first before adding places.</small></p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <!-- Spread & Drop Section Tab -->
                            <div class="tab-pane fade {{ $activeTab === 'spread-drop' ? 'show active' : '' }} p-4" id="spread-drop-section" role="tabpanel" aria-labelledby="spread-drop-tab">
                                
                                <!-- Section Settings Form -->
                                <form action="{{ route('admin.collections.store-spread-drop', $collection->slug) }}" method="POST" class="mb-4">
                                    @csrf
                                    <div class="card inner-section-card spread shadow-sm">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-lightbulb mr-1 text-warning"></i> Spread & Drop Section Settings
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <label class="font-weight-bold d-block mb-3">Section Visibility</label>
                                            <div class="d-flex align-items-center" style="gap: 25px;">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="is_active" id="spread_drop_show" value="1" 
                                                           {{ old('is_active', $collection->spreadDropSection->is_active ?? false) ? 'checked' : '' }}>
                                                    <label class="custom-control-label font-weight-bold text-success" for="spread_drop_show" style="cursor: pointer;">
                                                        <i class="fas fa-eye mr-1"></i> Show
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="is_active" id="spread_drop_hide" value="0" 
                                                           {{ !old('is_active', $collection->spreadDropSection->is_active ?? false) ? 'checked' : '' }}>
                                                    <label class="custom-control-label font-weight-bold text-secondary" for="spread_drop_hide" style="cursor: pointer;">
                                                        <i class="fas fa-eye-slash mr-1"></i> Hide
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-light d-flex align-items-center" style="gap: 10px;">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save mr-1"></i> Save Settings
                                            </button>
                                            @if($collection->spreadDropSection)
                                                @if($collection->spreadDropSection->is_active)
                                                    <span class="badge badge-pill badge-success px-2.5 py-1.5" style="font-size: 11px;">Currently: Visible</span>
                                                @else
                                                    <span class="badge badge-pill badge-secondary px-2.5 py-1.5" style="font-size: 11px;">Currently: Hidden</span>
                                                @endif
                                            @else
                                                <span class="badge badge-pill badge-warning px-2.5 py-1.5" style="font-size: 11px;">Not configured yet</span>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_js')
<style>
   .dataTables_filter,
   .dataTables_info {
       display: none;
   }
   tfoot input {
       width: 100%;
       padding: 4px 8px;
       box-sizing: border-box;
       border: 1px solid #ced4da;
       border-radius: 4px;
       font-size: 13px;
   }
   table.dataTable {
       width: 100% !important;
       table-layout: auto !important;
   }
   table.dataTable thead th, table.dataTable tfoot th {
       font-size: 13px;
       font-weight: 600;
       letter-spacing: 0.5px;
       vertical-align: middle !important;
       white-space: nowrap !important;
   }
   table.dataTable tbody td {
       vertical-align: middle !important;
       font-size: 14px;
   }
</style>
<script type="text/javascript">
$(document).ready(function() {
    function initDataTable(tableId, nonSortableTargets) {
        if (!$(tableId).length) return null;
        
        $(tableId + ' tfoot th').each(function () {
            var title = $(this).text().trim();
            if (title !== 'Action' && title !== 'ACTION' && title !== 'Image' && title !== 'IMAGE' && title !== 'Colors' && title !== 'COLORS') {
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            } else {
                $(this).html('');
            }
        });

        var table = $(tableId).DataTable({
            searching: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[0, "asc"]],
            columnDefs: [
                { targets: nonSortableTargets, orderable: false }
            ]
        });

        table.columns().every(function () {
            var that = this;
            $('input', this.footer()).on('keyup change clear', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });
        });

        return table;
    }

    initDataTable('#parameters-table', [4, 5]);
    initDataTable('#compositions-table', [1, 4, 5]);
    initDataTable('#tone-families-table', [1, 3, 4, 5]);
    initDataTable('#places-table', [1, 4, 5]);

    // Recalculate and adjust columns whenever any tab is shown or clicked
    $('a[data-toggle="tab"], a[data-bs-toggle="tab"], .nav-tabs .nav-link').on('shown.bs.tab click', function (e) {
        setTimeout(function() {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }, 100);
        setTimeout(function() {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }, 300);
    });
});
</script>
@stop
