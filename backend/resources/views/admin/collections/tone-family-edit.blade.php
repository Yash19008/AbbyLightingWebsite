@extends('admin.page')

@section('title', 'Edit Tone Family - ' . $family->title)

@php
    $main_module = 'Collections';
@endphp

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Tone Family</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.collections.index') }}">Collections</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.collections.edit', $collection->slug) }}?tab=tones">{{ $collection->name }}</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Tone Family</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.collections.tone-families.update', [$collection->slug, $family->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-palette mr-1"></i> Tone Family Details</h3>
                        </div>
                        <div class="card-body">
                            <!-- Family Title -->
                            <div class="form-group">
                                <label for="title">Family Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $family->title) }}" 
                                       placeholder="e.g., The Earths" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Name for this color family (e.g., "The Earths", "The Metallics")</small>
                            </div>

                            <!-- Current Image -->
                            @if($family->image)
                                <div class="form-group">
                                    <label>Current Image</label>
                                    <div class="mb-2">
                                        <img src="{{ $family->image_url }}" 
                                             alt="Current family image" 
                                             style="max-width: 300px; height: 200px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    </div>
                                </div>
                            @endif

                            <!-- Family Image -->
                            <div class="form-group">
                                <label for="image">
                                    @if($family->image)
                                        Replace Moodboard Image
                                    @else
                                        Family Moodboard Image <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*" 
                                       {{ !$family->image ? 'required' : '' }}>
                                @error('image')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-info">
                                    <i class="fas fa-info-circle"></i> <strong>Recommended Size:</strong> 800 × 600 px (4:3 ratio). 
                                    @if($family->image)
                                        Leave empty to keep current image. 
                                    @endif
                                    Max: 5MB
                                </small>
                            </div>

                            <!-- Select Colors (Compact Design with Live Search) -->
                            <div class="form-group mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap" style="gap: 8px;">
                                    <label class="font-weight-bold text-dark mb-0">
                                        Select Colors from Color Masters <span class="text-danger">*</span>
                                    </label>
                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                        <span class="badge badge-primary px-2 py-1 font-weight-bold" id="selectedCountBadge" style="font-size: 11px;">0 Selected</span>
                                        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" id="btnSelectAllColors" style="font-size: 11px; height: 24px;">
                                            <i class="fas fa-check-double mr-1"></i> Select All
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" id="btnClearAllColors" style="font-size: 11px; height: 24px;">
                                            <i class="fas fa-times mr-1"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                @error('colors')
                                    <div class="text-danger mb-2 small">{{ $message }}</div>
                                @enderror

                                <!-- Search Bar -->
                                <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                                    </div>
                                    <input type="text" id="colorSearchInput" class="form-control border-left-0" 
                                           placeholder="Search color name or code..." autocomplete="off">
                                    <div class="input-group-append" id="clearSearchBtnContainer" style="display: none;">
                                        <button class="btn btn-outline-secondary border-left-0" type="button" id="btnClearSearch"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                                
                                <!-- Compact Color Chips Container -->
                                <div class="color-selection-box p-2" style="max-height: 240px; overflow-y: auto; border: 1px solid #ced4da; border-radius: 6px; background-color: #f8f9fc;">
                                    @if($colors->count() > 0)
                                        <div class="d-flex flex-wrap" id="colorChipsList" style="gap: 6px;">
                                            @foreach($colors as $color)
                                                @php
                                                    $isSelected = in_array($color->id, old('colors', $selectedColorIds));
                                                @endphp
                                                <div class="compact-color-chip {{ $isSelected ? 'active' : '' }}" 
                                                     data-color-id="{{ $color->id }}" 
                                                     data-name="{{ strtolower($color->name) }}" 
                                                     data-code="{{ strtolower($color->code) }}"
                                                     title="{{ $color->name }} ({{ $color->code }})">
                                                    
                                                    <!-- Hidden Checkbox -->
                                                    <input type="checkbox" class="color-checkbox d-none" 
                                                           id="color_{{ $color->id }}" 
                                                           name="colors[]" 
                                                           value="{{ $color->id }}"
                                                           {{ $isSelected ? 'checked' : '' }}>
                                                    
                                                    <!-- Color Swatch Circle -->
                                                    <span class="chip-swatch" style="background: {{ $color->css_value }};"></span>
                                                    
                                                    <!-- Color Name & Code -->
                                                    <span class="chip-name text-truncate">{{ $color->name }}</span>
                                                    
                                                    @if($color->type === 'gradient')
                                                        <span class="badge badge-info px-1 py-0" style="font-size: 8px;">G</span>
                                                    @endif

                                                    <!-- Check Icon -->
                                                    <span class="chip-check text-primary font-weight-bold" style="{{ $isSelected ? 'display:inline-block;' : 'display:none;' }}">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="noSearchResults" class="text-center py-3 text-muted" style="display: none;">
                                            <i class="fas fa-search mr-1"></i> No matching colors found
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <p class="text-muted small mb-2">No active colors found in Color Masters.</p>
                                            <a href="{{ route('color_master_admin.add') }}" class="btn btn-primary btn-sm" target="_blank">
                                                <i class="fas fa-plus mr-1"></i> Add Colors First
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Display Order -->
                            <div class="form-group">
                                <label for="order">Display Order</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="order" name="order" value="{{ old('order', $family->order) }}" min="0">
                                @error('order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Lower numbers appear first</small>
                            </div>

                            <!-- Visibility -->
                            <div class="form-group mb-0">
                                <label class="font-weight-bold d-block">Visibility</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" 
                                           name="is_active" value="1" {{ old('is_active', $family->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active (Visible on Frontend)</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Update Tone Family
                            </button>
                            <a href="{{ route('admin.collections.edit', $collection->slug) }}?tab=tones" class="btn btn-default ml-2">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
.compact-color-chip {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px 4px 5px;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
    font-size: 13px;
    font-weight: 500;
    color: #333;
    max-width: 180px;
}
.compact-color-chip:hover {
    border-color: #007bff;
    background: #f0f7ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}
.compact-color-chip.active {
    background: #ebf5ff;
    border-color: #007bff;
    box-shadow: 0 0 0 1px #007bff;
    color: #0056b3;
    font-weight: 600;
}
.chip-swatch {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 1px solid rgba(0,0,0,0.15);
    flex-shrink: 0;
    margin-right: 6px;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
}
.chip-name {
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.chip-check {
    font-size: 12px;
    margin-left: 4px;
}
.selected-color-badge {
    display: inline-flex;
    align-items: center;
    margin: 2px;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    background: #ffffff;
    border: 1px solid #e3e6f0;
    font-weight: 500;
    color: #333;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chips = document.querySelectorAll('.compact-color-chip');
    const searchInput = document.getElementById('colorSearchInput');
    const clearSearchBtn = document.getElementById('btnClearSearch');
    const clearSearchContainer = document.getElementById('clearSearchBtnContainer');
    const noSearchResults = document.getElementById('noSearchResults');
    const btnSelectAll = document.getElementById('btnSelectAllColors');
    const btnClearAll = document.getElementById('btnClearAllColors');
    const badgeCount = document.getElementById('selectedCountBadge');
    
    // Toggle chip on click
    chips.forEach(chip => {
        chip.addEventListener('click', function(e) {
            const checkbox = this.querySelector('.color-checkbox');
            const checkIcon = this.querySelector('.chip-check');
            
            checkbox.checked = !checkbox.checked;
            
            if (checkbox.checked) {
                this.classList.add('active');
                if (checkIcon) checkIcon.style.display = 'inline-block';
            } else {
                this.classList.remove('active');
                if (checkIcon) checkIcon.style.display = 'none';
            }
            
            checkbox.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
    
    // Search filtering
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            let visibleCount = 0;
            
            if (query.length > 0) {
                if (clearSearchContainer) clearSearchContainer.style.display = 'block';
            } else {
                if (clearSearchContainer) clearSearchContainer.style.display = 'none';
            }
            
            chips.forEach(chip => {
                const name = chip.getAttribute('data-name') || '';
                const code = chip.getAttribute('data-code') || '';
                
                if (name.includes(query) || code.includes(query)) {
                    chip.style.display = 'inline-flex';
                    visibleCount++;
                } else {
                    chip.style.display = 'none';
                }
            });
            
            if (noSearchResults) {
                noSearchResults.style.display = (visibleCount === 0) ? 'block' : 'none';
            }
        });
        
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            });
        }
    }
    
    // Select All
    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function() {
            chips.forEach(chip => {
                // only select visible chips if filtered
                if (chip.style.display !== 'none') {
                    const checkbox = chip.querySelector('.color-checkbox');
                    const checkIcon = chip.querySelector('.chip-check');
                    checkbox.checked = true;
                    chip.classList.add('active');
                    if (checkIcon) checkIcon.style.display = 'inline-block';
                }
            });
            updateSelectedColors();
        });
    }
    
    // Clear All
    if (btnClearAll) {
        btnClearAll.addEventListener('click', function() {
            chips.forEach(chip => {
                const checkbox = chip.querySelector('.color-checkbox');
                const checkIcon = chip.querySelector('.chip-check');
                checkbox.checked = false;
                chip.classList.remove('active');
                if (checkIcon) checkIcon.style.display = 'none';
            });
            updateSelectedColors();
        });
    }
    
    // Update selected colors preview & badge counter
    function updateSelectedColors() {
        const checkboxes = document.querySelectorAll('.color-checkbox:checked');
        const container = document.getElementById('selected-colors-container');
        
        if (badgeCount) {
            badgeCount.textContent = checkboxes.length + ' Selected';
        }
        
        if (!container) return;
        
        if (checkboxes.length === 0) {
            container.innerHTML = '<small class="text-muted">No colors selected</small>';
            return;
        }
        
        let html = `<small class="text-muted mb-2 d-block font-weight-bold">${checkboxes.length} color(s) selected:</small>`;
        html += `<div class="d-flex flex-wrap" style="gap: 4px;">`;
        
        checkboxes.forEach(checkbox => {
            const chip = checkbox.closest('.compact-color-chip');
            const colorName = chip.querySelector('.chip-name').textContent;
            const colorSwatch = chip.querySelector('.chip-swatch');
            const bg = colorSwatch ? colorSwatch.style.background : '#eee';
            
            html += `<div class="selected-color-badge">
                <span class="d-inline-block mr-1" style="width: 10px; height: 10px; background: ${bg}; border-radius: 50%; border: 1px solid rgba(0,0,0,0.15); vertical-align: middle;"></span>
                ${colorName}
            </div>`;
        });
        
        html += `</div>`;
        container.innerHTML = html;
    }
    
    // Listen for checkbox changes
    document.querySelectorAll('.color-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedColors);
    });
    
    // Initialize preview
    updateSelectedColors();
});
</script>
@endsection