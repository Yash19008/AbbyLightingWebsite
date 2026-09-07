@extends('admin.page')

@section('title', $title)
@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3">
            <h4>{{ $title }}</h4>
            <a href="{{ route('decorative_product_admin') }}" class="btn btn-secondary mt-2">Back to List</a>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="premium-card">
            <div class="premium-card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('decorative_product_admin.insert') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="title">Title *</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="sku">SKU *</label>
                            <input type="text" name="sku" class="form-control" required value="{{ old('sku') }}">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="categories">Categories</label>
                            <select name="categories[]" class="form-control select2" multiple="multiple" data-placeholder="Select Categories">
                                @foreach($categories as $category)
                                    @if($category->parent_id == null)
                                        <optgroup label="{{ $category->name }}">
                                            <option value="{{ $category->id }}">{{ $category->name }} (Main)</option>
                                            @foreach($category->children as $child)
                                                <option value="{{ $child->id }}">-- {{ $child->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="collection_ids">Collections</label>
                            <select name="collection_ids[]" id="collection_ids" class="form-control select2" multiple="multiple" data-placeholder="Select Collections" style="width: 100%;">
                                @foreach($collections as $col)
                                    <option value="{{ $col->id }}" {{ in_array($col->id, $selected_collections) ? 'selected' : '' }}>{{ $col->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="short_description">Short Description</label>
                            <textarea name="short_description" class="form-control">{{ old('short_description') }}</textarea>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="primary_image">Primary Image <small class="text-primary font-weight-bold">(Recommended: 600×750px | 4:5 Portrait)</small></label>
                            <div class="custom-file-upload">
                                <i class="ft-upload-cloud custom-file-upload-icon"></i>
                                <span class="custom-file-upload-text">Choose a file Or Drag and Drop it here (Primary Image)</span>
                                <span class="custom-file-upload-subtext">PNG, JPEG, up to 12Mb &bull; <strong>Recommended: 600×750 px</strong></span>
                                <input type="file" name="primary_image" accept="image/*" onchange="previewImage(this, 'primary-image-preview')">
                            </div>
                            <div class="mt-2 d-flex flex-wrap gap-3" id="primary-image-preview" style="display: none;">
                                <div class="img-preview-box"><img src=""></div>
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="light_on_image">Light On Image <small class="text-primary font-weight-bold">(Recommended: 600×750px | 4:5 Portrait)</small></label>
                            <div class="custom-file-upload">
                                <i class="ft-upload-cloud custom-file-upload-icon"></i>
                                <span class="custom-file-upload-text">Choose a file Or Drag and Drop it here (Light On Image)</span>
                                <span class="custom-file-upload-subtext">PNG, JPEG, up to 12Mb &bull; <strong>Recommended: 600×750 px</strong> (matches Primary angle)</span>
                                <input type="file" name="light_on_image" accept="image/*" onchange="previewImage(this, 'light-on-image-preview')">
                            </div>
                            <div class="mt-2" id="light-on-image-preview" style="display: none;">
                                <div class="img-preview-box"><img src=""></div>
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="gallery_images">Gallery Images (Multiple) <small class="text-primary font-weight-bold">(Recommended: 800×1000px | 4:5 Portrait)</small></label>
                            <div class="custom-file-upload">
                                <i class="ft-upload-cloud custom-file-upload-icon"></i>
                                <span class="custom-file-upload-text">Choose files Or Drag and Drop them here (Gallery Images)</span>
                                <span class="custom-file-upload-subtext">PNG, JPEG, up to 12Mb &bull; <strong>Recommended: 800×1000 px</strong> (Multiple)</span>
                                <input type="file" name="gallery_images[]" accept="image/*" multiple onchange="previewGalleryImages(this, 'gallery-images-preview')">
                            </div>
                            <div class="mt-2 d-flex flex-wrap gap-2" id="gallery-images-preview">
                                <!-- Previews will be appended here -->
                            </div>
                        </div>
                    </div>

                    <!-- Product Attributes -->
                    <div class="premium-section mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2" style="border-bottom: 1px solid #e2e8f0;">
                            <h5 class="font-weight-bold text-dark mb-0"><i class="ft-list mr-2 text-primary"></i>Product Attributes</h5>
                        </div>
                        <div class="d-flex align-items-center mb-4" style="gap: 15px; max-width: 500px;">
                            <select id="global-attribute-select" class="form-control">
                                <option value="">Select an attribute...</option>
                                @if(isset($global_attributes))
                                    @foreach($global_attributes as $attr)
                                        <option value="{{ $attr->id }}" data-name="{{ $attr->name }}" data-values="{{ json_encode($attr->values) }}">{{ $attr->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <button type="button" class="btn btn-primary" id="add-product-attribute" style="white-space: nowrap;">
                                <i class="ft-plus"></i> Add Attribute
                            </button>
                        </div>
                        <div id="product-attributes-container">
                            <!-- Attributes will be appended here -->
                        </div>
                    </div>

                    <!-- Product Variations -->
                    <div class="premium-section mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2" style="border-bottom: 1px solid #e2e8f0;">
                            <h5 class="font-weight-bold text-dark mb-0"><i class="ft-layers mr-2 text-primary"></i>Product Variations</h5>
                            <div>
                                <button type="button" class="btn btn-warning mr-2" id="generate-variations">
                                    <i class="ft-shuffle"></i> Generate Variations
                                </button>
                                <button type="button" class="btn btn-success" id="add-variation">
                                    <i class="ft-plus"></i> Add Manually
                                </button>
                            </div>
                        </div>
                        <div id="product-variations-container">
                            <!-- Variations will be appended here -->
                        </div>
                    </div>

                    <div class="mt-4 pt-4 text-right" style="border-top: 1px solid #e2e8f0;">
                        <button type="submit" class="btn btn-premium btn-lg" style="padding: 0.75rem 2rem; font-size: 1.1rem;">
                            <i class="ft-check-square mr-2"></i> Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('extra_css')
<link rel="stylesheet" href="{{ asset('adminlte/css/product-variations.css') }}">
<style>
    .select2-container {
        width: 100% !important;
    }
    .select2-search__field {
        width: 100% !important;
    }
</style>
@stop

@section('extra_js')
<script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script>
$(document).ready(function() {
    CKEDITOR.replace('short_description');
    CKEDITOR.replace('description');

    let attrIndex = 0;
    let varIndex = 0;

    // Add Attribute
    document.getElementById('add-product-attribute').addEventListener('click', function() {
        const select = document.getElementById('global-attribute-select');
        const selectedOption = select.options[select.selectedIndex];
        
        if (!selectedOption.value) return;

        const attrId = selectedOption.value;
        const attrName = selectedOption.getAttribute('data-name');
        const attrValues = JSON.parse(selectedOption.getAttribute('data-values'));

        let optionsHtml = '';
        attrValues.forEach(val => {
            optionsHtml += `<option value="${val.id}">${val.name}</option>`;
        });

        const container = document.getElementById('product-attributes-container');
        const html = `
            <div class="prod-attr-block" data-attr-id="${attrId}" data-attr-name="${attrName}">
                <div class="row">
                    <div class="col-md-3">
                        <h6>${attrName}</h6>
                        <input type="hidden" name="product_attributes[${attrIndex}][attribute_id]" value="${attrId}">
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input is-variation-checkbox" name="product_attributes[${attrIndex}][is_variation]" value="1" id="var_chk_${attrIndex}">
                            <label class="form-check-label" for="var_chk_${attrIndex}">Used for variations</label>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <label>Select Values</label>
                        <select name="product_attributes[${attrIndex}][values][]" class="form-control attr-values-select select2" multiple data-placeholder="Select values">
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-prod-attr w-100">Remove</button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        // Initialize select2 on the newly inserted select element
        $(container.lastElementChild).find('.select2').select2();
        
        attrIndex++;
        select.value = ''; // Reset
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-prod-attr')) {
            e.target.closest('.prod-attr-block').remove();
        }
        if (e.target.classList.contains('remove-var')) {
            e.target.closest('.variation-block').remove();
        }
    });

    // Generate Variations
    document.getElementById('generate-variations').addEventListener('click', function() {
        const blocks = document.querySelectorAll('.prod-attr-block');
        let attributesForVariations = [];

        blocks.forEach(block => {
            const isVar = block.querySelector('.is-variation-checkbox').checked;
            if (isVar) {
                const attrId = block.getAttribute('data-attr-id');
                const attrName = block.getAttribute('data-attr-name');
                const select = block.querySelector('.attr-values-select');
                const selectedOptions = Array.from(select.selectedOptions);
                
                if (selectedOptions.length > 0) {
                    attributesForVariations.push({
                        id: attrId,
                        name: attrName,
                        values: selectedOptions.map(opt => ({ id: opt.value, name: opt.text }))
                    });
                }
            }
        });

        if (attributesForVariations.length === 0) {
            alert('Please select attributes, add values, and check "Used for variations".');
            return;
        }

        // Cartesian product of arrays
        const cartesian = (...a) => a.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
        
        const valueArrays = attributesForVariations.map(a => a.values);
        let combinations = [];
        if (valueArrays.length === 1) {
            combinations = valueArrays[0].map(v => [v]);
        } else {
            combinations = cartesian(...valueArrays);
        }

        const container = document.getElementById('product-variations-container');
        const baseSku = document.querySelector('input[name="sku"]').value;

        const existingBlocks = Array.from(document.querySelectorAll('.variation-block'));
        const existingVars = existingBlocks.map(block => {
            const attrInputs = Array.from(block.querySelectorAll('input[name*="[attributes][]"]'));
            const ids = attrInputs.map(inp => inp.value).sort();
            return {
                block: block,
                ids: ids,
                used: false
            };
        });

        combinations.forEach(combo => {
            let comboNameParts = [];
            
            combo.forEach(val => {
                comboNameParts.push(val.name);
            });

            const comboName = comboNameParts.join(' - ');
            const suggestedSku = baseSku ? baseSku + '-' + comboNameParts.map(p => p.substring(0,3).toUpperCase()).join('-') : '';
            const comboIds = combo.map(v => String(v.id)).sort();

            // 1. Check for exact match
            const exactMatch = existingVars.find(ev => ev.ids.join(',') === comboIds.join(','));
            if (exactMatch) {
                exactMatch.used = true;
                return; // Already exists, do nothing
            }

            // 2. Check for subset match (an old variation that can be upgraded)
            const subsetMatch = existingVars.find(ev => !ev.used && ev.ids.length > 0 && ev.ids.every(id => comboIds.includes(id)));
            if (subsetMatch) {
                subsetMatch.used = true;
                // Update title
                const titleEl = subsetMatch.block.querySelector('strong');
                if (titleEl) titleEl.textContent = comboName;
                
                // Add missing attribute hidden inputs
                const attrContainer = titleEl.parentNode;
                const match = subsetMatch.block.innerHTML.match(/variations\[(\d+)\]/);
                const bVarIndex = match ? match[1] : varIndex; 

                combo.forEach(val => {
                    if (!subsetMatch.ids.includes(String(val.id))) {
                        attrContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="variations[${bVarIndex}][attributes][]" value="${val.id}">`);
                    }
                });

                // Update suggested SKU if empty
                const skuInput = subsetMatch.block.querySelector('input[placeholder="SKU"]');
                if (skuInput && skuInput.value === '') {
                    skuInput.value = suggestedSku;
                }
                return; // Upgraded existing, so don't append a new one
            }

            // 3. No match, create a brand new block
            let attrHiddenInputs = '';
            combo.forEach(val => {
                attrHiddenInputs += `<input type="hidden" name="variations[${varIndex}][attributes][]" value="${val.id}">`;
            });

            const html = `
                <div class="variation-block position-relative" data-var-index="${varIndex}">
                    <button type="button" class="btn btn-sm btn-danger remove-var position-absolute" style="top: 15px; right: 15px; z-index: 10; border-radius: 50%; width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Remove Variation">
                        <i class="ft-x"></i>
                    </button>
                    
                    <div class="row align-items-center mb-3">
                        <div class="col-md-12 mb-3">
                            <h6 class="font-weight-bold text-primary mb-0">${comboName}</h6>
                            ${attrHiddenInputs}
                        </div>
                        <div class="col-md-6">
                            <label>SKU (Optional)</label>
                            <input type="text" name="variations[${varIndex}][sku]" class="form-control" placeholder="SKU" value="${suggestedSku}">
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                            <select name="variations[${varIndex}][status]" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Variation Images <small class="text-primary font-weight-bold">(Recommended: 800×1000px | 4:5 Portrait)</small></label>
                            <div class="custom-file-upload">
                                <i class="ft-upload-cloud custom-file-upload-icon"></i>
                                <span class="custom-file-upload-text">Choose files Or Drag and Drop them here</span>
                                <span class="custom-file-upload-subtext">PNG, JPEG, up to 12Mb &bull; <strong>Recommended: 800×1000 px</strong> (Multiple)</span>
                                <input type="file" name="variations[${varIndex}][gallery_images][]" accept="image/*" multiple onchange="previewGalleryImages(this, 'var-gal-preview-${varIndex}')">
                            </div>
                            <div class="mt-2 d-flex flex-wrap gap-3" id="var-gal-preview-${varIndex}"></div>
                        </div>
                    </div>
                    <!-- Specification Sections for this variation -->
                    <div class="mt-3 border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Specification Sections</h6>
                            <button type="button" class="btn btn-sm btn-info add-var-spec-section" data-var-index="${varIndex}">+ Add Section</button>
                        </div>
                        <div class="var-spec-sections-container" id="var-spec-container-${varIndex}"></div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            varIndex++;
        });
    });

    document.getElementById('add-variation').addEventListener('click', function() {
        alert('Manual variation adding is useful for editing, but initially try "Generate Variations" based on attributes!');
    });

    // Variation-level Specification Sections
    function buildSpecSectionHtml(varIdx, secIdx) {
        return `
            <div class="var-spec-section" data-sec-index="${secIdx}">
                <div class="row">
                    <div class="col-md-5">
                        <label>Section Title</label>
                        <input type="text" name="variations[${varIdx}][spec_sections][${secIdx}][title]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Display Order</label>
                        <input type="number" name="variations[${varIdx}][spec_sections][${secIdx}][display_order]" class="form-control" value="0">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-var-sec w-100">Remove Section</button>
                    </div>
                </div>
                <div class="mt-3">
                    <table class="spec-table">
                        <thead><tr>
                            <th>Label</th><th>Value</th><th>Order</th>
                            <th width="100"><button type="button" class="btn btn-sm btn-success add-var-spec-row w-100" data-var-index="${varIdx}" data-sec-index="${secIdx}">+ Row</button></th>
                        </tr></thead>
                        <tbody class="var-spec-rows-container" id="var-spec-rows-${varIdx}-${secIdx}"></tbody>
                    </table>
                </div>
            </div>
        `;
    }

    function buildSpecRowHtml(varIdx, secIdx, rowIdx) {
        return `
            <tr>
                <td><input type="text" name="variations[${varIdx}][spec_sections][${secIdx}][specs][${rowIdx}][label]" class="form-control form-control-sm" required></td>
                <td><input type="text" name="variations[${varIdx}][spec_sections][${secIdx}][specs][${rowIdx}][value]" class="form-control form-control-sm"></td>
                <td><input type="number" name="variations[${varIdx}][spec_sections][${secIdx}][specs][${rowIdx}][display_order]" class="form-control form-control-sm" value="0"></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-var-spec-row w-100">X</button></td>
            </tr>
        `;
    }

    // Track spec section indexes per variation
    const varSpecIndexes = {};

    $(document).on('click', '.add-var-spec-section', function() {
        const varIdx = $(this).data('var-index');
        if (varSpecIndexes[varIdx] === undefined) varSpecIndexes[varIdx] = 0;
        const secIdx = varSpecIndexes[varIdx];
        const container = document.getElementById('var-spec-container-' + varIdx);
        container.insertAdjacentHTML('beforeend', buildSpecSectionHtml(varIdx, secIdx));
        varSpecIndexes[varIdx]++;
    });

    $(document).on('click', '.remove-var-sec', function() {
        $(this).closest('.var-spec-section').remove();
    });

    $(document).on('click', '.add-var-spec-row', function() {
        const varIdx = $(this).data('var-index');
        const secIdx = $(this).data('sec-index');
        const tbody = document.getElementById('var-spec-rows-' + varIdx + '-' + secIdx);
        const rowIdx = tbody.children.length;
        tbody.insertAdjacentHTML('beforeend', buildSpecRowHtml(varIdx, secIdx, rowIdx));
    });

    $(document).on('click', '.remove-var-spec-row', function() {
        $(this).closest('tr').remove();
    });

    // Select2
    $('.select2').select2({
        placeholder: "Select Categories",
        allowClear: true,
        width: '100%'
    });

});

function previewImage(input, previewId, badgeType = null) {
    const previewContainer = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let badgeHtml = '';
            if (badgeType === 'primary') badgeHtml = '<div class="img-preview-badge primary"><i class="fa fa-star"></i> Primary</div>';
            else if (badgeType === 'light_on') badgeHtml = '<div class="img-preview-badge light-on"><i class="fa fa-lightbulb-o"></i> Light On</div>';
            else if (previewId.includes('primary')) badgeHtml = '<div class="img-preview-badge primary"><i class="fa fa-star"></i> Primary</div>';
            else if (previewId.includes('light')) badgeHtml = '<div class="img-preview-badge light-on"><i class="fa fa-lightbulb-o"></i> Light On</div>';

            previewContainer.innerHTML = `
                <div class="img-preview-box">
                    ${badgeHtml}
                    <img src="${e.target.result}" onclick="openLightbox(this.src)">
                    <button type="button" class="remove-image-btn" onclick="
                        $(this).closest('.form-group').find('input[type=file]').val('');
                        document.getElementById('${previewId}').style.display='none';
                    "><i class="ft-x"></i></button>
                </div>
            `;
            previewContainer.style.display = 'block';
            $(input).closest('.custom-file-upload').hide();
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
        previewContainer.innerHTML = '';
        $(input).closest('.custom-file-upload').show();
    }
}

function previewGalleryImages(input, previewContainerId) {
    const container = document.getElementById(previewContainerId);
    container.innerHTML = ''; // clear existing previews
    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'img-preview-box';
                div.innerHTML = `
                    <img src="${e.target.result}" onclick="openLightbox(this.src)">
                    <button type="button" class="remove-image-btn" onclick="
                        $(this).closest('.form-group').find('input[type=file]').val('');
                        document.getElementById('${previewContainerId}').innerHTML='';
                        $(this).closest('.form-group').find('.custom-file-upload').show();
                    "><i class="ft-x"></i></button>
                `;
                container.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
        $(input).closest('.custom-file-upload').hide();
    }
}

// Lightbox logic
function openLightbox(src) {
    let modal = document.getElementById('imageLightbox');
    if (!modal) {
        document.body.insertAdjacentHTML('beforeend', `
            <div class="modal fade" id="imageLightbox" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-body text-center position-relative">
                            <button type="button" class="close text-white position-absolute" data-dismiss="modal" aria-label="Close" style="top: -20px; right: -20px; font-size: 2rem; opacity: 1;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <img id="lightboxImg" src="" class="img-fluid rounded shadow-lg" style="max-height: 85vh;">
                        </div>
                    </div>
                </div>
            </div>
        `);
        modal = document.getElementById('imageLightbox');
    }
    document.getElementById('lightboxImg').src = src;
    $(modal).modal('show');
}
</script>
@stop
