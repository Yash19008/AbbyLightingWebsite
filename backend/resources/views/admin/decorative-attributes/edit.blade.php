@extends('admin.page')
@section('title', $title)

@section('extra_css')
<link rel="stylesheet" href="{{ asset('adminlte/css/product-variations.css') }}">
@stop

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0">{{ $title }}</h1>
        <a href="{{ route('decorative_attribute_admin') }}" class="btn btn-secondary"><i class="ft-arrow-left mr-1"></i>Back to List</a>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="premium-card">
            <div class="premium-card-body">
                <form action="{{ $action }}" method="POST">
                    @csrf

                    <h4 class="section-title mb-4">Attribute Details</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-muted">Attribute Name (e.g., Colour, Size)</label>
                            <input type="text" name="name" class="form-control" required value="{{ $attribute->name }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                        <h4 class="section-title mb-0">Attribute Values</h4>
                        <button type="button" class="btn btn-premium btn-info" id="add-value"><i class="ft-plus mr-1"></i> Add Value</button>
                    </div>

                    <div id="values-container" class="mb-4">
                        @foreach($attribute->values as $index => $val)
                            @php
                                $attrNameLower = strtolower($attribute->name);
                                $isColor = str_contains($attrNameLower, 'color') || str_contains($attrNameLower, 'colour') || str_contains($attrNameLower, 'finish');
                                $valHex = $val->hex_code ?? '#ffffff';
                                $isGradient = str_contains($valHex, 'gradient');
                                $gradAngle = 45;
                                $gradColor1 = '#ffffff';
                                $gradColor2 = '#000000';
                                if ($isGradient) {
                                    preg_match('/linear-gradient\(\s*(\d+)deg\s*,\s*(#[a-fA-F0-9]{3,6})\s*,\s*(#[a-fA-F0-9]{3,6})\s*\)/', $valHex, $matches);
                                    if (count($matches) >= 4) {
                                        $gradAngle = $matches[1];
                                        $gradColor1 = $matches[2];
                                        $gradColor2 = $matches[3];
                                    }
                                }
                            @endphp
                            <div class="spec-card p-3 mb-3 shadow-sm rounded border d-flex justify-content-between align-items-center value-row">
                                <div class="flex-grow-1 mr-3">
                                    <label class="font-weight-bold text-muted small">Value Name (e.g., Red, Small)</label>
                                    <input type="hidden" name="values[{{ $index }}][id]" value="{{ $val->id }}">
                                    <input type="text" name="values[{{ $index }}][name]" class="form-control" required value="{{ $val->name }}">
                                </div>
                                @if($isColor)
                                <div class="flex-grow-1 mr-3">
                                    <label class="font-weight-bold text-muted small">Value Color/Code</label>
                                        <div class="color-input-container">
                                            <div class="mb-1 d-flex align-items-center" style="gap: 5px;">
                                                <select class="form-control color-type-select" style="width: auto;">
                                                    <option value="solid" {{ !$isGradient ? 'selected' : '' }}>Solid</option>
                                                    <option value="gradient" {{ $isGradient ? 'selected' : '' }}>Gradient</option>
                                                </select>
                                                <div class="solid-picker" style="{{ $isGradient ? 'display: none;' : 'display: block;' }}">
                                                    <input type="color" class="form-control p-1 color-1" style="max-width: 50px; cursor: pointer; height: 38px;" value="{{ !$isGradient ? $valHex : $gradColor1 }}">
                                                </div>
                                                <div class="gradient-pickers" style="{{ $isGradient ? 'display: flex;' : 'display: none;' }} align-items: center; gap: 5px;">
                                                    <input type="color" class="form-control p-1 color-1" style="max-width: 50px; cursor: pointer; height: 38px;" value="{{ $gradColor1 }}">
                                                    <input type="color" class="form-control p-1 color-2" style="max-width: 50px; cursor: pointer; height: 38px;" value="{{ $gradColor2 }}">
                                                    <div class="input-group" style="width: 100px;">
                                                        <input type="number" class="form-control gradient-angle" value="{{ $gradAngle }}">
                                                        <div class="input-group-append"><span class="input-group-text">deg</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" name="values[{{ $index }}][hex_code]" class="form-control final-color-output" placeholder="#ffffff" value="{{ $valHex }}">
                                        </div>
                                </div>
                                @else
                                    <input type="hidden" name="values[{{ $index }}][hex_code]" value="{{ $val->hex_code }}">
                                @endif
                                <div>
                                    <label class="d-block">&nbsp;</label>
                                    <button type="button" class="btn btn-danger remove-val"><i class="ft-trash-2"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="mt-4 mb-4">
                    <button type="submit" class="btn btn-premium"><i class="ft-save mr-1"></i> Update Attribute</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let valueIndex = {{ $attribute->values->count() }};

    document.getElementById('add-value').addEventListener('click', function () {
        const attrName = document.querySelector('input[name="name"]').value.toLowerCase();
        const isColor = attrName.includes('color') || attrName.includes('colour') || attrName.includes('finish');

        let hexHtml = '';
        if (isColor) {
            hexHtml = `
            <div class='color-input-container'>
                <div class='mb-1 d-flex align-items-center' style='gap: 5px;'>
                    <select class='form-control color-type-select' style='width: auto;'>
                        <option value='solid'>Solid</option>
                        <option value='gradient'>Gradient</option>
                    </select>
                    <div class='solid-picker'>
                        <input type='color' class='form-control p-1 color-1' style='max-width: 50px; cursor: pointer; height: 38px;' value='#ffffff'>
                    </div>
                    <div class='gradient-pickers' style='display: none; align-items: center; gap: 5px;'>
                        <input type='color' class='form-control p-1 color-1' style='max-width: 50px; cursor: pointer; height: 38px;' value='#ffffff'>
                        <input type='color' class='form-control p-1 color-2' style='max-width: 50px; cursor: pointer; height: 38px;' value='#000000'>
                        <div class='input-group' style='width: 100px;'>
                            <input type='number' class='form-control gradient-angle' value='45'>
                            <div class='input-group-append'><span class='input-group-text'>deg</span></div>
                        </div>
                    </div>
                </div>
                <input type='text' name='values[${valueIndex}][hex_code]' class='form-control final-color-output' placeholder='#ffffff' value='#ffffff'>
            </div>`;
        } else {
            hexHtml = `<input type='text' name='values[${valueIndex}][hex_code]' class='form-control' placeholder='Leave blank for non-colors'>`;
        }

        const container = document.getElementById('values-container');
        const row = `
        <div class='spec-card p-3 mb-3 shadow-sm rounded border d-flex justify-content-between align-items-center value-row'>
            <div class='flex-grow-1 mr-3'>
                <label class='font-weight-bold text-muted small'>Value Name (e.g., Red, Small)</label>
                <input type='text' name='values[${valueIndex}][name]' class='form-control' required placeholder='e.g. White'>
            </div>
            <div class='flex-grow-1 mr-3'>
                <label class='font-weight-bold text-muted small'>Value Color/Code</label>
                ${hexHtml}
            </div>
            <div>
                <label class='d-block'>&nbsp;</label>
                <button type='button' class='btn btn-danger remove-val'><i class='ft-trash-2'></i></button>
            </div>
        </div>`;
        container.insertAdjacentHTML('beforeend', row);
        valueIndex++;
    });

    document.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.remove-val');
        if (removeBtn) {
            removeBtn.closest('.value-row').remove();
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('color-type-select')) {
            const colorContainer = e.target.closest('.color-input-container');
            const solidPicker = colorContainer.querySelector('.solid-picker');
            const gradPickers = colorContainer.querySelector('.gradient-pickers');
            if (e.target.value === 'gradient') {
                solidPicker.style.display = 'none';
                gradPickers.style.display = 'flex';
            } else {
                solidPicker.style.display = 'block';
                gradPickers.style.display = 'none';
            }
            updateColorOutput(colorContainer);
        }
    });

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('color-1') || e.target.classList.contains('color-2') || e.target.classList.contains('gradient-angle')) {
            const colorContainer = e.target.closest('.color-input-container');
            if (colorContainer) {
                if (e.target.classList.contains('color-1')) {
                    colorContainer.querySelectorAll('.color-1').forEach(el => el.value = e.target.value);
                }
                updateColorOutput(colorContainer);
            }
        }
    });

    function updateColorOutput(colorContainer) {
        const type = colorContainer.querySelector('.color-type-select').value;
        const output = colorContainer.querySelector('.final-color-output');
        const color1 = colorContainer.querySelector('.color-1').value;
        if (type === 'solid') {
            output.value = color1;
        } else {
            const color2 = colorContainer.querySelector('.color-2').value;
            const angle = colorContainer.querySelector('.gradient-angle').value || 45;
            output.value = `linear-gradient(${angle}deg, ${color1}, ${color2})`;
        }
    }
});
</script>
@stop