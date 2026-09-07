@extends('admin.page')

@section('title', 'Add Color')

@php
    $main_module = 'Color Masters';
@endphp

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add New Color</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('contact_form_admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('color_master_admin') }}">Color Masters</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('color_master_admin.insert') }}" method="POST">
            @csrf
            
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
                                               id="name" name="name" value="{{ old('name') }}" required 
                                               placeholder="e.g., Warm White, Ocean Blue">
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code">Color Code</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                               id="code" name="code" value="{{ old('code') }}" 
                                               placeholder="e.g., warm-white (auto-generated if empty)">
                                        <small class="form-text text-muted">Unique identifier (auto-generated from name if empty)</small>
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
                                            <option value="solid" {{ old('type') === 'solid' ? 'selected' : '' }}>Solid Color</option>
                                            <option value="gradient" {{ old('type') === 'gradient' ? 'selected' : '' }}>Gradient</option>
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
                                               id="hex_code_picker" name="hex_code" value="{{ old('hex_code', '#FFFFFF') }}" 
                                               style="max-width: 80px;">
                                        <input type="text" class="form-control" id="hex_code_text" 
                                               value="{{ old('hex_code', '#FFFFFF') }}" 
                                               placeholder="#FFFFFF" maxlength="7">
                                    </div>
                                    <small class="form-text text-muted">Pick a color or enter hex code (e.g., #FF5733)</small>
                                    @error('hex_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Gradient Fields & Interactive Picker -->
                            <div id="gradient-fields" style="display: none;">
                                <!-- Hidden Form Inputs synced with Gradient Picker -->
                                <input type="hidden" id="gradient_start" name="gradient_start" value="{{ old('gradient_start', '#A84628') }}">
                                <input type="hidden" id="gradient_end" name="gradient_end" value="{{ old('gradient_end', '#000000') }}">
                                <input type="hidden" id="gradient_type" name="gradient_type" value="{{ old('gradient_type', 'linear') }}">
                                <input type="hidden" id="gradient_direction" name="gradient_direction" value="{{ old('gradient_direction', '135deg') }}">

                                <div class="card card-outline card-primary shadow-sm mb-3" style="border: 1px solid #ced4da; border-radius: 8px; overflow: hidden;">
                                    <div class="card-header py-2 px-3 bg-light d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #e9ecef;">
                                        <span class="font-weight-bold text-dark" style="font-size: 14px;">
                                            <i class="fas fa-palette mr-1 text-primary"></i> Interactive Gradient Studio
                                        </span>
                                        <div class="btn-group btn-group-sm" role="group" id="gradientTypeToggle">
                                            <button type="button" class="btn btn-primary btn-sm active" data-type="linear" id="btnLinearType">
                                                <i class="fas fa-arrows-alt-h mr-1"></i> Linear
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-type="radial" id="btnRadialType">
                                                <i class="fas fa-dot-circle mr-1"></i> Radial
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body p-3">
                                        <!-- 1. Gradient Track Bar & Drag Handles -->
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="font-weight-600 mb-0 small text-dark">
                                                <i class="fas fa-sliders-h mr-1 text-muted"></i> Color Stops & Percentage
                                            </label>
                                            <small class="text-muted" style="font-size: 11px;">Click bar to add stop &bull; Drag stop to move</small>
                                        </div>
                                        
                                        <div class="gradient-track-wrapper position-relative mb-4 mt-2" style="padding: 10px 14px 26px;">
                                            <!-- The Visual Gradient Bar -->
                                            <div id="gradientTrackBar" style="
                                                height: 34px; 
                                                border-radius: 6px; 
                                                box-shadow: inset 0 0 0 1px rgba(0,0,0,0.18), 0 2px 6px rgba(0,0,0,0.1); 
                                                cursor: copy; 
                                                position: relative;
                                                background-image: linear-gradient(45deg, #eee 25%, transparent 25%), linear-gradient(-45deg, #eee 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #eee 75%), linear-gradient(-45deg, transparent 75%, #eee 75%);
                                                background-size: 12px 12px;
                                                background-position: 0 0, 0 6px, 6px -6px, -6px 0px;
                                            ">
                                                <div id="gradientTrackFill" style="position: absolute; inset: 0; border-radius: 6px;"></div>
                                            </div>
                                            <!-- Stop Markers Container -->
                                            <div id="gradientStopsContainer" style="position: absolute; left: 14px; right: 14px; top: 10px; height: 34px; pointer-events: none;">
                                                <!-- Dynamically rendered stop handles -->
                                            </div>
                                        </div>

                                        <!-- 2. Active Stop Editor Bar -->
                                        <div class="p-2 mb-3 bg-light rounded d-flex flex-wrap align-items-center justify-content-between border" id="activeStopBar">
                                            <div class="d-flex align-items-center flex-wrap mr-2">
                                                <span class="badge badge-primary mr-2 px-2 py-1" id="activeStopBadge" style="font-size: 12px;">Stop 1</span>
                                                
                                                <div class="d-flex align-items-center mr-3 my-1">
                                                    <label class="mr-1 mb-0 small text-muted font-weight-bold">Color:</label>
                                                    <input type="color" id="activeStopColorPicker" class="form-control form-control-sm p-0 border" style="width: 38px; height: 32px; cursor: pointer; border-radius: 4px;">
                                                    <input type="text" id="activeStopColorHex" class="form-control form-control-sm ml-1" style="width: 85px; font-family: monospace; font-weight: 600; text-transform: uppercase;" maxlength="7" value="#A84628">
                                                </div>

                                                <div class="d-flex align-items-center my-1">
                                                    <label class="mr-1 mb-0 small text-muted font-weight-bold">Position:</label>
                                                    <input type="range" id="activeStopPosSlider" min="0" max="100" class="custom-range mr-2" style="width: 100px;">
                                                    <div class="input-group input-group-sm" style="width: 70px;">
                                                        <input type="number" id="activeStopPosNumber" min="0" max="100" class="form-control form-control-sm text-center font-weight-bold" value="0">
                                                        <div class="input-group-append"><span class="input-group-text p-1">%</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="my-1">
                                                <button type="button" class="btn btn-outline-danger btn-sm" id="btnDeleteStop" title="Delete selected stop">
                                                    <i class="fas fa-trash-alt mr-1"></i> Remove Stop
                                                </button>
                                            </div>
                                        </div>

                                        <!-- 3. Direction & Angle Manager (Directly from color picker) -->
                                        <div id="linearDirectionControls" class="mb-3">
                                            <label class="font-weight-600 mb-2 small text-dark d-block">
                                                <i class="fas fa-compass mr-1 text-primary"></i> Direction & Angle Dial
                                            </label>
                                            
                                            <div class="row align-items-center">
                                                <!-- Interactive Circular Angle Dial -->
                                                <div class="col-auto text-center pr-2">
                                                    <div id="angleDialContainer" style="
                                                        width: 58px; 
                                                        height: 58px; 
                                                        border-radius: 50%; 
                                                        background: #ffffff; 
                                                        border: 2px solid #007bff; 
                                                        position: relative; 
                                                        cursor: crosshair; 
                                                        display: inline-block; 
                                                        box-shadow: 0 2px 5px rgba(0,0,0,0.12);
                                                        touch-action: none;
                                                    ">
                                                        <div id="angleDialPointer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: rotate(135deg); pointer-events: none;">
                                                            <div style="width: 8px; height: 8px; background: #007bff; border-radius: 50%; position: absolute; top: 3px; left: calc(50% - 4px); box-shadow: 0 1px 3px rgba(0,0,0,0.4);"></div>
                                                            <div style="width: 2px; height: 20px; background: #007bff; position: absolute; top: 8px; left: calc(50% - 1px);"></div>
                                                        </div>
                                                        <div style="position: absolute; top: calc(50% - 4px); left: calc(50% - 4px); width: 8px; height: 8px; background: #343a40; border-radius: 50%;"></div>
                                                    </div>
                                                </div>

                                                <!-- Degree Input -->
                                                <div class="col-auto pr-2">
                                                    <div class="input-group input-group-sm" style="width: 88px;">
                                                        <input type="number" id="angleDegreeInput" min="0" max="360" value="135" class="form-control text-center font-weight-bold">
                                                        <div class="input-group-append"><span class="input-group-text p-1">°</span></div>
                                                    </div>
                                                </div>

                                                <!-- Quick Preset Direction Arrows -->
                                                <div class="col">
                                                    <div class="d-flex flex-wrap gap-1" id="directionPresets">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-2 py-1" data-angle="90" data-dir="to right" title="Left to Right (90°)">→</button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-2 py-1" data-angle="180" data-dir="to bottom" title="Top to Bottom (180°)">↓</button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-2 py-1" data-angle="270" data-dir="to left" title="Right to Left (270°)">←</button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-2 py-1" data-angle="0" data-dir="to top" title="Bottom to Top (0°)">↑</button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-2 py-1 active font-weight-bold" data-angle="135" data-dir="135deg" title="Diagonal ↘ (135°)">↘ 135°</button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-2 py-1" data-angle="45" data-dir="45deg" title="Diagonal ↗ (45°)">↗ 45°</button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-2 py-1" data-angle="225" data-dir="225deg" title="Diagonal ↙ (225°)">↙ 225°</button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm m-1 px-2 py-1" data-angle="315" data-dir="315deg" title="Diagonal ↖ (315°)">↖ 315°</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Radial Position Controls (only active when radial selected) -->
                                        <div id="radialPositionControls" class="mb-3" style="display: none;">
                                            <label class="font-weight-600 mb-2 small text-dark d-block">
                                                <i class="fas fa-circle-notch mr-1 text-primary"></i> Radial Center
                                            </label>
                                            <div class="btn-group btn-group-sm" id="radialShapePresets">
                                                <button type="button" class="btn btn-outline-secondary active" data-shape="circle at center">Center</button>
                                                <button type="button" class="btn btn-outline-secondary" data-shape="circle at top">Top</button>
                                                <button type="button" class="btn btn-outline-secondary" data-shape="circle at bottom">Bottom</button>
                                                <button type="button" class="btn btn-outline-secondary" data-shape="circle at left">Left</button>
                                                <button type="button" class="btn btn-outline-secondary" data-shape="circle at right">Right</button>
                                            </div>
                                        </div>

                                        <!-- 4. Quick Gradient Swatch Presets -->
                                        <div class="pt-2 border-top">
                                            <label class="font-weight-600 mb-1 small text-muted">
                                                <i class="fas fa-magic mr-1"></i> Quick Presets
                                            </label>
                                            <div class="d-flex flex-wrap" id="quickGradientPresets">
                                                <!-- Populated via JS -->
                                            </div>
                                        </div>

                                        <!-- 5. Live CSS Rule Output with Copy button -->
                                        <div class="mt-2 pt-2 border-top">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="font-weight-bold text-muted">CSS Output:</small>
                                                <button type="button" class="btn btn-link btn-sm p-0 text-primary" id="btnCopyCss" style="font-size: 11px;">
                                                    <i class="far fa-copy mr-1"></i> Copy CSS
                                                </button>
                                            </div>
                                            <div class="p-2 bg-dark rounded text-light font-monospace small" id="cssRuleDisplay" style="word-break: break-all; font-size: 11px; user-select: all;">
                                                linear-gradient(135deg, #a84628 0%, #000000 100%)
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2" 
                                          placeholder="Optional description...">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="order">Display Order</label>
                                <input type="number" class="form-control" id="order" name="order" 
                                       value="{{ old('order', 0) }}" min="0">
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
                                    <input type="checkbox" class="custom-control-input" id="is_active" 
                                           name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
                        <i class="fas fa-save"></i> Save Color
                    </button>
                    <a href="{{ route('color_master_admin') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
.gradient-stop-handle {
    position: absolute;
    top: -4px;
    width: 22px;
    height: 42px;
    transform: translateX(-50%);
    cursor: grab;
    pointer-events: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    user-select: none;
    z-index: 10;
}
.gradient-stop-handle:active {
    cursor: grabbing;
}
.gradient-stop-handle .stop-cap {
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 7px solid #343a40;
    margin-bottom: 2px;
}
.gradient-stop-handle .stop-circle {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.5);
    transition: transform 0.1s ease, box-shadow 0.1s ease;
}
.gradient-stop-handle.active {
    z-index: 20;
}
.gradient-stop-handle.active .stop-cap {
    border-top-color: #007bff;
}
.gradient-stop-handle.active .stop-circle {
    border-color: #007bff;
    transform: scale(1.25);
    box-shadow: 0 0 0 2px rgba(0,123,255,0.4), 0 2px 6px rgba(0,0,0,0.5);
}
.gradient-preset-chip {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 1px solid rgba(0,0,0,0.15);
    margin: 3px;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.gradient-preset-chip:hover {
    transform: scale(1.18);
    box-shadow: 0 3px 7px rgba(0,0,0,0.25);
    z-index: 5;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const solidFields = document.getElementById('solid-fields');
    const gradientFields = document.getElementById('gradient-fields');
    const colorPreview = document.getElementById('color-preview');
    
    // Solid inputs
    const hexPicker = document.getElementById('hex_code_picker');
    const hexText = document.getElementById('hex_code_text');
    
    // Hidden gradient inputs synced with Laravel backend
    const gradientStartInput = document.getElementById('gradient_start');
    const gradientEndInput = document.getElementById('gradient_end');
    const gradientTypeInput = document.getElementById('gradient_type');
    const gradientDirectionInput = document.getElementById('gradient_direction');

    // UI elements
    const trackFill = document.getElementById('gradientTrackFill');
    const trackBar = document.getElementById('gradientTrackBar');
    const stopsContainer = document.getElementById('gradientStopsContainer');
    const activeStopBadge = document.getElementById('activeStopBadge');
    const activeStopColorPicker = document.getElementById('activeStopColorPicker');
    const activeStopColorHex = document.getElementById('activeStopColorHex');
    const activeStopPosSlider = document.getElementById('activeStopPosSlider');
    const activeStopPosNumber = document.getElementById('activeStopPosNumber');
    const btnDeleteStop = document.getElementById('btnDeleteStop');
    
    const angleDialContainer = document.getElementById('angleDialContainer');
    const angleDialPointer = document.getElementById('angleDialPointer');
    const angleDegreeInput = document.getElementById('angleDegreeInput');
    const directionPresets = document.getElementById('directionPresets');
    
    const btnLinearType = document.getElementById('btnLinearType');
    const btnRadialType = document.getElementById('btnRadialType');
    const linearDirectionControls = document.getElementById('linearDirectionControls');
    const radialPositionControls = document.getElementById('radialPositionControls');
    const radialShapePresets = document.getElementById('radialShapePresets');
    const cssRuleDisplay = document.getElementById('cssRuleDisplay');
    const btnCopyCss = document.getElementById('btnCopyCss');
    const quickGradientPresets = document.getElementById('quickGradientPresets');

    // Gradient State
    let gradientType = gradientTypeInput.value || 'linear';
    let gradientAngle = 135;
    let gradientDirection = gradientDirectionInput.value || '135deg';
    let radialShape = 'circle at center';
    
    // Parse direction into angle if numeric
    const angleMatch = gradientDirection.match(/(\d+)deg/);
    if (angleMatch) {
        gradientAngle = parseInt(angleMatch[1], 10);
    } else if (gradientDirection === 'to right') {
        gradientAngle = 90;
    } else if (gradientDirection === 'to bottom') {
        gradientAngle = 180;
    } else if (gradientDirection === 'to left') {
        gradientAngle = 270;
    } else if (gradientDirection === 'to top') {
        gradientAngle = 0;
    }

    // Default Stops
    let stops = [
        { color: gradientStartInput.value || '#A84628', position: 0 },
        { color: gradientEndInput.value || '#000000', position: 100 }
    ];
    let activeStopIndex = 0;

    // Presets Library
    const presets = [
        { name: 'Warm Terracotta', type: 'linear', angle: 135, dir: '135deg', stops: [{ color: '#a84628', position: 0 }, { color: '#000000', position: 100 }] },
        { name: 'Sunset Glow', type: 'linear', angle: 90, dir: 'to right', stops: [{ color: '#ff512f', position: 0 }, { color: '#dd2476', position: 100 }] },
        { name: 'Ocean Blue', type: 'linear', angle: 135, dir: '135deg', stops: [{ color: '#2b5876', position: 0 }, { color: '#4e4376', position: 100 }] },
        { name: 'Emerald Forest', type: 'linear', angle: 135, dir: '135deg', stops: [{ color: '#0ba360', position: 0 }, { color: '#3cba92', position: 100 }] },
        { name: 'Deep Royal', type: 'linear', angle: 90, dir: 'to right', stops: [{ color: '#654ea3', position: 0 }, { color: '#eaafc8', position: 100 }] },
        { name: 'Titanium Silver', type: 'linear', angle: 135, dir: '135deg', stops: [{ color: '#e0e0e0', position: 0 }, { color: '#f5f5f5', position: 50 }, { color: '#9e9e9e', position: 100 }] },
        { name: 'Amber Gold', type: 'linear', angle: 45, dir: '45deg', stops: [{ color: '#f7971e', position: 0 }, { color: '#ffd200', position: 100 }] },
        { name: 'Dark Velvet', type: 'linear', angle: 180, dir: 'to bottom', stops: [{ color: '#1f1c2c', position: 0 }, { color: '#928dab', position: 100 }] },
        { name: 'Midnight Radial', type: 'radial', shape: 'circle at center', stops: [{ color: '#3a6073', position: 0 }, { color: '#16222a', position: 100 }] }
    ];

    // Render Quick Presets
    presets.forEach(p => {
        const chip = document.createElement('div');
        chip.className = 'gradient-preset-chip';
        chip.title = p.name;
        const bg = p.type === 'radial' 
            ? `radial-gradient(${p.shape || 'circle at center'}, ${p.stops.map(s => `${s.color} ${s.position}%`).join(', ')})`
            : `linear-gradient(${p.angle}deg, ${p.stops.map(s => `${s.color} ${s.position}%`).join(', ')})`;
        chip.style.background = bg;
        chip.addEventListener('click', () => applyPreset(p));
        quickGradientPresets.appendChild(chip);
    });

    function applyPreset(p) {
        gradientType = p.type;
        stops = JSON.parse(JSON.stringify(p.stops));
        activeStopIndex = 0;
        if (p.type === 'linear') {
            gradientAngle = p.angle || 135;
            gradientDirection = p.dir || `${gradientAngle}deg`;
        } else {
            radialShape = p.shape || 'circle at center';
        }
        updateTypeUI();
        updateAngleUI();
        renderStops();
        updateActiveStopInputs();
        updateAll();
    }

    // Toggle Type (Linear / Radial)
    btnLinearType.addEventListener('click', function() {
        gradientType = 'linear';
        updateTypeUI();
        updateAll();
    });

    btnRadialType.addEventListener('click', function() {
        gradientType = 'radial';
        updateTypeUI();
        updateAll();
    });

    function updateTypeUI() {
        if (gradientType === 'linear') {
            btnLinearType.classList.add('active', 'btn-primary');
            btnLinearType.classList.remove('btn-outline-secondary');
            btnRadialType.classList.remove('active', 'btn-primary');
            btnRadialType.classList.add('btn-outline-secondary');
            linearDirectionControls.style.display = 'block';
            radialPositionControls.style.display = 'none';
        } else {
            btnRadialType.classList.add('active', 'btn-primary');
            btnRadialType.classList.remove('btn-outline-secondary');
            btnLinearType.classList.remove('active', 'btn-primary');
            btnLinearType.classList.add('btn-outline-secondary');
            linearDirectionControls.style.display = 'none';
            radialPositionControls.style.display = 'block';
        }
        gradientTypeInput.value = gradientType;
    }

    // Radial Shapes
    radialShapePresets.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('click', function() {
            radialShapePresets.querySelectorAll('button').forEach(b => b.classList.remove('active', 'btn-primary'));
            this.classList.add('active', 'btn-primary');
            radialShape = this.getAttribute('data-shape');
            updateAll();
        });
    });

    // Angle Dial Interaction
    function setAngle(deg, directionStr) {
        deg = (deg % 360 + 360) % 360;
        gradientAngle = Math.round(deg);
        gradientDirection = directionStr || `${gradientAngle}deg`;
        updateAngleUI();
        updateAll();
    }

    function updateAngleUI() {
        angleDialPointer.style.transform = `rotate(${gradientAngle}deg)`;
        angleDegreeInput.value = gradientAngle;
        gradientDirectionInput.value = gradientDirection;

        // Highlight matching preset button
        directionPresets.querySelectorAll('button').forEach(b => {
            const bAngle = parseInt(b.getAttribute('data-angle'), 10);
            const bDir = b.getAttribute('data-dir');
            if (bAngle === gradientAngle || bDir === gradientDirection) {
                b.classList.add('active', 'btn-primary');
                b.classList.remove('btn-outline-secondary');
            } else {
                b.classList.remove('active', 'btn-primary');
                b.classList.add('btn-outline-secondary');
            }
        });
    }

    angleDegreeInput.addEventListener('input', function() {
        const val = parseInt(this.value, 10);
        if (!isNaN(val)) {
            setAngle(val, `${val}deg`);
        }
    });

    // Preset direction buttons
    directionPresets.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('click', function() {
            const angle = parseInt(this.getAttribute('data-angle'), 10);
            const dir = this.getAttribute('data-dir');
            setAngle(angle, dir);
        });
    });

    // Interactive Drag Dial
    let isDialDragging = false;
    function handleDialEvent(e) {
        const rect = angleDialContainer.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        const dx = clientX - centerX;
        const dy = clientY - centerY;
        let deg = Math.round((Math.atan2(dy, dx) * 180 / Math.PI + 90 + 360) % 360);
        setAngle(deg, `${deg}deg`);
    }

    angleDialContainer.addEventListener('mousedown', function(e) {
        isDialDragging = true;
        handleDialEvent(e);
        window.addEventListener('mousemove', onDialMove);
        window.addEventListener('mouseup', onDialUp);
    });
    function onDialMove(e) { if (isDialDragging) handleDialEvent(e); }
    function onDialUp() { isDialDragging = false; window.removeEventListener('mousemove', onDialMove); window.removeEventListener('mouseup', onDialUp); }

    angleDialContainer.addEventListener('touchstart', function(e) {
        isDialDragging = true;
        handleDialEvent(e);
        window.addEventListener('touchmove', onDialTouchMove, { passive: false });
        window.addEventListener('touchend', onDialTouchEnd);
    }, { passive: false });
    function onDialTouchMove(e) { if (isDialDragging) { e.preventDefault(); handleDialEvent(e); } }
    function onDialTouchEnd() { isDialDragging = false; window.removeEventListener('touchmove', onDialTouchMove); window.removeEventListener('touchend', onDialTouchEnd); }

    // Render Stop Markers
    function renderStops() {
        stopsContainer.innerHTML = '';
        stops.forEach((stop, index) => {
            const handle = document.createElement('div');
            handle.className = `gradient-stop-handle ${index === activeStopIndex ? 'active' : ''}`;
            handle.style.left = `${stop.position}%`;
            handle.innerHTML = `
                <div class="stop-cap"></div>
                <div class="stop-circle" style="background: ${stop.color};"></div>
            `;

            // Click / Drag stop
            handle.addEventListener('mousedown', (e) => {
                e.stopPropagation();
                selectStop(index);
                startStopDrag(e, index);
            });

            handle.addEventListener('touchstart', (e) => {
                e.stopPropagation();
                selectStop(index);
                startStopTouchDrag(e, index);
            }, { passive: false });

            stopsContainer.appendChild(handle);
        });
    }

    function selectStop(index) {
        activeStopIndex = index;
        renderStops();
        updateActiveStopInputs();
    }

    function updateActiveStopInputs() {
        if (!stops[activeStopIndex]) return;
        const curr = stops[activeStopIndex];
        activeStopBadge.textContent = `Stop ${activeStopIndex + 1}`;
        activeStopColorPicker.value = curr.color;
        activeStopColorHex.value = curr.color.toUpperCase();
        activeStopPosSlider.value = curr.position;
        activeStopPosNumber.value = curr.position;

        btnDeleteStop.disabled = stops.length <= 2;
    }

    // Drag Stop Handle
    function startStopDrag(e, index) {
        const rect = trackBar.getBoundingClientRect();
        function onMove(me) {
            let pos = Math.round(((me.clientX - rect.left) / rect.width) * 100);
            pos = Math.max(0, Math.min(100, pos));
            stops[index].position = pos;
            renderStops();
            updateActiveStopInputs();
            updateAll();
        }
        function onUp() {
            window.removeEventListener('mousemove', onMove);
            window.removeEventListener('mouseup', onUp);
        }
        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', onUp);
    }

    function startStopTouchDrag(e, index) {
        const rect = trackBar.getBoundingClientRect();
        function onTouchMove(te) {
            te.preventDefault();
            let pos = Math.round(((te.touches[0].clientX - rect.left) / rect.width) * 100);
            pos = Math.max(0, Math.min(100, pos));
            stops[index].position = pos;
            renderStops();
            updateActiveStopInputs();
            updateAll();
        }
        function onTouchEnd() {
            window.removeEventListener('touchmove', onTouchMove);
            window.removeEventListener('touchend', onTouchEnd);
        }
        window.addEventListener('touchmove', onTouchMove, { passive: false });
        window.addEventListener('touchend', onTouchEnd);
    }

    // Add Stop on Track Click
    trackBar.addEventListener('click', function(e) {
        const rect = trackBar.getBoundingClientRect();
        let pos = Math.round(((e.clientX - rect.left) / rect.width) * 100);
        pos = Math.max(0, Math.min(100, pos));

        // Use active stop color or generate new
        const newColor = stops[activeStopIndex] ? stops[activeStopIndex].color : '#FFFFFF';
        stops.push({ color: newColor, position: pos });
        stops.sort((a, b) => a.position - b.position);
        activeStopIndex = stops.findIndex(s => s.position === pos && s.color === newColor);
        if (activeStopIndex === -1) activeStopIndex = stops.length - 1;

        renderStops();
        updateActiveStopInputs();
        updateAll();
    });

    // Delete Stop
    btnDeleteStop.addEventListener('click', function() {
        if (stops.length > 2) {
            stops.splice(activeStopIndex, 1);
            activeStopIndex = Math.max(0, activeStopIndex - 1);
            renderStops();
            updateActiveStopInputs();
            updateAll();
        }
    });

    // Active Stop Inputs Sync
    activeStopColorPicker.addEventListener('input', function() {
        if (stops[activeStopIndex]) {
            stops[activeStopIndex].color = this.value;
            activeStopColorHex.value = this.value.toUpperCase();
            renderStops();
            updateAll();
        }
    });

    activeStopColorHex.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            if (stops[activeStopIndex]) {
                stops[activeStopIndex].color = this.value;
                activeStopColorPicker.value = this.value;
                renderStops();
                updateAll();
            }
        }
    });

    activeStopPosSlider.addEventListener('input', function() {
        if (stops[activeStopIndex]) {
            stops[activeStopIndex].position = parseInt(this.value, 10);
            activeStopPosNumber.value = this.value;
            renderStops();
            updateAll();
        }
    });

    activeStopPosNumber.addEventListener('input', function() {
        let val = parseInt(this.value, 10);
        if (!isNaN(val)) {
            val = Math.max(0, Math.min(100, val));
            if (stops[activeStopIndex]) {
                stops[activeStopIndex].position = val;
                activeStopPosSlider.value = val;
                renderStops();
                updateAll();
            }
        }
    });

    // Copy CSS Button
    btnCopyCss.addEventListener('click', function() {
        const css = cssRuleDisplay.textContent.trim();
        navigator.clipboard.writeText(css).then(() => {
            const orig = btnCopyCss.innerHTML;
            btnCopyCss.innerHTML = '<i class="fas fa-check mr-1 text-success"></i> Copied!';
            setTimeout(() => { btnCopyCss.innerHTML = orig; }, 1800);
        });
    });

    // Build CSS and Sync hidden inputs
    function buildCss() {
        const sortedStops = [...stops].sort((a, b) => a.position - b.position);
        const stopsStr = sortedStops.map(s => `${s.color} ${s.position}%`).join(', ');
        
        if (gradientType === 'radial') {
            return `radial-gradient(${radialShape}, ${stopsStr})`;
        } else {
            return `linear-gradient(${gradientDirection}, ${stopsStr})`;
        }
    }

    function updateAll() {
        const sortedStops = [...stops].sort((a, b) => a.position - b.position);
        const trackGradient = `linear-gradient(to right, ${sortedStops.map(s => `${s.color} ${s.position}%`).join(', ')})`;
        trackFill.style.background = trackGradient;

        // Sync hidden inputs for backend
        gradientStartInput.value = sortedStops[0].color;
        gradientEndInput.value = sortedStops[sortedStops.length - 1].color;
        gradientTypeInput.value = gradientType;
        gradientDirectionInput.value = gradientDirection;

        const fullCss = buildCss();
        cssRuleDisplay.textContent = fullCss;

        // Update big preview
        if (typeSelect.value === 'gradient') {
            colorPreview.style.background = fullCss;
        }
    }

    // Toggle Solid vs Gradient
    function toggleFields() {
        const type = typeSelect.value;
        if (type === 'solid') {
            solidFields.style.display = 'block';
            gradientFields.style.display = 'none';
            colorPreview.style.background = hexPicker.value;
        } else {
            solidFields.style.display = 'none';
            gradientFields.style.display = 'block';
            updateAll();
        }
    }

    // Solid Color sync
    hexPicker.addEventListener('input', function() {
        hexText.value = this.value.toUpperCase();
        if (typeSelect.value === 'solid') {
            colorPreview.style.background = this.value;
        }
    });

    hexText.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            hexPicker.value = this.value;
            if (typeSelect.value === 'solid') {
                colorPreview.style.background = this.value;
            }
        }
    });

    typeSelect.addEventListener('change', toggleFields);

    // Initial run
    updateTypeUI();
    updateAngleUI();
    renderStops();
    updateActiveStopInputs();
    toggleFields();
});
</script>
@endsection
