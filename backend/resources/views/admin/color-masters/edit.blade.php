@extends('admin.page')

@section('title', 'Edit Color')

@php
    $main_module = 'Color Masters';
@endphp

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Color: {{ $color->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('contact_form_admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('color_master_admin') }}">Color Masters</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('color_master_admin.update', $color->id) }}" method="POST">
            @csrf
            @method('POST')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Color Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Color Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $color->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code">Color Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                               id="code" name="code" value="{{ old('code', $color->code) }}" required>
                                        <small class="form-text text-muted">Unique identifier</small>
                                        @error('code')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="type">Color Type <span class="text-danger">*</span></label>
                                        <select class="form-control @error('type') is-invalid @enderror" 
                                                id="type" name="type" required>
                                            <option value="solid" {{ old('type', $color->type) === 'solid' ? 'selected' : '' }}>Solid Color</option>
                                            <option value="gradient" {{ old('type', $color->type) === 'gradient' ? 'selected' : '' }}>Gradient</option>
                                        </select>
                                        @error('type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Solid Color Fields -->
                            <div id="solid-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="hex_code">Hex Color Code <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="color" class="form-control @error('hex_code') is-invalid @enderror" 
                                               id="hex_code_picker" name="hex_code" 
                                               value="{{ old('hex_code', $color->hex_code ?? '#FFFFFF') }}" 
                                               style="max-width: 80px;">
                                        <input type="text" class="form-control" id="hex_code_text" 
                                               value="{{ old('hex_code', $color->hex_code ?? '#FFFFFF') }}" 
                                               placeholder="#FFFFFF" maxlength="7">
                                    </div>
                                    @error('hex_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Gradient Fields & Interactive Picker -->
                            <div id="gradient-fields" style="display: none;">
                                <!-- Hidden Form Inputs synced with Gradient Picker -->
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Color 1 (50%)</label>
                                            <div class="d-flex align-items-center">
                                                <input type="color" id="gradient_start_picker" class="form-control form-control-sm p-0 border mr-2" style="width: 40px; height: 38px; cursor: pointer;" value="{{ old('gradient_start', $color->gradient_start ?? '#A84628') }}">
                                                <input type="text" id="gradient_start" name="gradient_start" class="form-control text-uppercase" value="{{ old('gradient_start', $color->gradient_start ?? '#A84628') }}" maxlength="7">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Color 2 (50%)</label>
                                            <div class="d-flex align-items-center">
                                                <input type="color" id="gradient_end_picker" class="form-control form-control-sm p-0 border mr-2" style="width: 40px; height: 38px; cursor: pointer;" value="{{ old('gradient_end', $color->gradient_end ?? '#000000') }}">
                                                <input type="text" id="gradient_end" name="gradient_end" class="form-control text-uppercase" value="{{ old('gradient_end', $color->gradient_end ?? '#000000') }}" maxlength="7">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2" 
                                          placeholder="Optional description...">{{ old('description', $color->description ?? '') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="order">Display Order</label>
                                <input type="number" class="form-control" id="order" name="order" 
                                       value="{{ old('order', $color->order ?? 0) }}" min="0">
                                <small class="form-text text-muted">Controls display sequence (lower numbers first)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Preview</h3>
                        </div>
                        <div class="card-body">
                            <div id="color-preview" style="
                                width: 100%; 
                                height: 200px; 
                                background: #FFFFFF; 
                                border: 2px solid #ddd; 
                                border-radius: 8px;
                                margin-bottom: 15px;
                                box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
                            "></div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="custom-control-input" id="is_active" 
                                           name="is_active" value="1" {{ old('is_active', $color->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Color
                    </button>
                    <a href="{{ route('color_master_admin') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const solidFields = document.getElementById('solid-fields');
    const gradientFields = document.getElementById('gradient-fields');
    const colorPreview = document.getElementById('color-preview');
    
    // Solid inputs
    const hexPicker = document.getElementById('hex_code_picker');
    const hexText = document.getElementById('hex_code_text');
    
    // Gradient inputs
    const gradientStartPicker = document.getElementById('gradient_start_picker');
    const gradientStartText = document.getElementById('gradient_start');
    const gradientEndPicker = document.getElementById('gradient_end_picker');
    const gradientEndText = document.getElementById('gradient_end');

    function updatePreview() {
        const type = typeSelect.value;
        if (type === 'solid') {
            colorPreview.style.background = hexText.value || '#FFFFFF';
        } else {
            const c1 = gradientStartText.value || '#A84628';
            const c2 = gradientEndText.value || '#000000';
            colorPreview.style.background = `linear-gradient(135deg, ${c1} 50%, ${c2} 50%)`;
        }
    }

    function toggleFields() {
        const type = typeSelect.value;
        if (type === 'solid') {
            solidFields.style.display = 'block';
            gradientFields.style.display = 'none';
        } else {
            solidFields.style.display = 'none';
            gradientFields.style.display = 'block';
        }
        updatePreview();
    }

    // Solid Color sync
    hexPicker.addEventListener('input', function() {
        hexText.value = this.value.toUpperCase();
        updatePreview();
    });

    hexText.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            hexPicker.value = this.value;
            updatePreview();
        }
    });

    // Gradient sync
    gradientStartPicker.addEventListener('input', function() {
        gradientStartText.value = this.value.toUpperCase();
        updatePreview();
    });

    gradientStartText.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            gradientStartPicker.value = this.value;
            updatePreview();
        }
    });

    gradientEndPicker.addEventListener('input', function() {
        gradientEndText.value = this.value.toUpperCase();
        updatePreview();
    });

    gradientEndText.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            gradientEndPicker.value = this.value;
            updatePreview();
        }
    });

    typeSelect.addEventListener('change', toggleFields);

    // Initial run
    toggleFields();
});
</script>
@endsection
