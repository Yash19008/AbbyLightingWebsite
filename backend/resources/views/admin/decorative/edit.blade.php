@extends('admin.page')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@php $main_module = 'Decorative Product'; @endphp

@section('extra_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
/* Wizard Shell Styles */
.wizard-container {
    display: flex;
    flex-wrap: nowrap;
    gap: 20px;
    align-items: flex-start;
}
@media (max-width: 768px) {
    .wizard-container {
        flex-direction: column;
    }
    .wizard-nav {
        width: 100%;
    }
}
.wizard-nav {
    width: 240px;
    flex-shrink: 0;
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 15px 0;
}
.wizard-nav .nav-title {
    padding: 0 20px 10px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
    border-bottom: 1px solid #eee;
    margin-bottom: 10px;
}
.wizard-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.wizard-nav li {
    margin: 2px 0;
}
.wizard-nav a {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    color: #666;
    text-decoration: none;
    transition: all 0.2s;
    font-weight: 500;
}
.wizard-nav a:hover {
    background: #f8f9fa;
    color: #333;
}
.wizard-nav a.active {
    background: #f4ece4; /* theme accent light */
    color: #333;
    border-left: 3px solid #d9a05b;
}
.wizard-nav a .step-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1px solid #ccc;
    margin-right: 12px;
    font-size: 10px;
    color: transparent;
}
.wizard-nav a.completed .step-icon {
    background: #28a745;
    border-color: #28a745;
    color: white;
}
.wizard-nav a.active .step-icon {
    border-color: #333;
}
.wizard-nav a.active.completed .step-icon {
    border-color: #28a745;
}

.wizard-content {
    flex-grow: 1;
    min-width: 0; /* prevent flex blowout */
}

/* Tab panes */
.wizard-pane {
    display: none;
}
.wizard-pane.active {
    display: block;
}

.wizard-topbar {
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.topbar-left {
    display: flex;
    align-items: center;
    gap: 15px;
}
.topbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Image Picker */
.image-picker-container {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 30px 20px;
    text-align: center;
    background: #f9fafb;
    cursor: pointer;
    position: relative;
    transition: all 0.2s;
}
.image-picker-container:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}
.image-picker-input {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
}
.image-picker-preview {
    display: none;
}
.image-picker-preview img {
    max-width: 100%;
    max-height: 180px;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.variant-form-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
.variant-form-card .card-header {
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}
</style>
@endsection

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-body">
        
        <!-- Top Bar -->
        <div class="wizard-topbar">
            <div class="topbar-left">
                <a href="{{ route('decorative_product_admin') }}" class="btn btn-sm btn-outline-secondary" title="Back to list">
                    <i class="ft-arrow-left"></i>
                </a>
                <h3 class="m-0" style="font-size:18px; font-weight:600;">
                    {{ isset($product) ? 'Edit Product – ' . $product->name : 'Add New Product' }}
                </h3>
                @if(isset($product))
                    <span class="badge {{ $product->status == 'published' ? 'badge-success' : ($product->status == 'archived' ? 'badge-secondary' : 'badge-warning') }}">
                        {{ ucfirst($product->status) }}
                    </span>
                @endif
            </div>
            <div class="topbar-right">
                @if(isset($product))
                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="ft-eye"></i> Preview</a>
                @endif
                <button type="button" class="btn btn-sm btn-dark" id="btn-save-wizard">Save Changes</button>
            </div>
        </div>

        <!-- Wizard Layout -->
        <div class="wizard-container">
            
            <!-- Left Navigation -->
            <div class="wizard-nav">
                <div class="nav-title">Product Setup</div>
                <ul id="wizard-tabs">
                    @php
                        $isBasicDone = isset($product);
                        $isVariantsDone = $isBasicDone && $product->variants->count() > 0;
                        $isImagesDone = $isVariantsDone && $product->variants->every(function($v) { return $v->main_image && $v->lighton_image; });
                        $isSpecsDone = $isVariantsDone && $product->variants->sum(function($v) { return $v->specRows->count(); }) > 0;
                        $isDownloadsDone = $isBasicDone && ($product->installation_guide || $product->care_instructions);
                        
                        // Just checking if any related products exist
                        $isRelatedDone = $isBasicDone && (\App\Models\Decorative\DecProductRelated::where('product_id', $product->id)->count() > 0);
                        
                        $isSeoDone = $isBasicDone && ($product->meta_title || $product->meta_description || $product->meta_keywords);
                    @endphp
                    
                    <li><a href="#basic" class="active {{ $isBasicDone ? 'completed' : '' }}"><i class="step-icon ft-check"></i> Basic Information</a></li>
                    <li><a href="#variants" class="{{ !$isBasicDone ? 'disabled' : '' }} {{ $isVariantsDone ? 'completed' : '' }}"><i class="step-icon ft-check"></i> Variants</a></li>
                    <li><a href="#images" class="{{ !$isBasicDone ? 'disabled' : '' }} {{ $isImagesDone ? 'completed' : '' }}"><i class="step-icon ft-check"></i> Images</a></li>
                    <li><a href="#specifications" class="{{ !$isBasicDone ? 'disabled' : '' }} {{ $isSpecsDone ? 'completed' : '' }}"><i class="step-icon ft-check"></i> Specifications</a></li>
                    <li><a href="#downloads" class="{{ !$isBasicDone ? 'disabled' : '' }} {{ $isDownloadsDone ? 'completed' : '' }}"><i class="step-icon ft-check"></i> Downloads</a></li>
                    <li><a href="#related" class="{{ !$isBasicDone ? 'disabled' : '' }} {{ $isRelatedDone ? 'completed' : '' }}"><i class="step-icon ft-check"></i> Related Products</a></li>
                    <li><a href="#seo" class="{{ !$isBasicDone ? 'disabled' : '' }} {{ $isSeoDone ? 'completed' : '' }}"><i class="step-icon ft-check"></i> SEO & Settings</a></li>
                </ul>
            </div>

            <!-- Center Content -->
            <div class="wizard-content">
                
                @include('admin.decorative.partials.tab-basic')

                @include('admin.decorative.partials.tab-variants')

                @include('admin.decorative.partials.tab-images')

                @include('admin.decorative.partials.tab-specifications')

                @include('admin.decorative.partials.tab-downloads')

                @include('admin.decorative.partials.tab-related')

                @include('admin.decorative.partials.tab-seo')


            </div>

        </div>

    </div>
</div>

{{-- ===================== SPEC EDIT MODAL ===================== --}}
@if(isset($product))
<div class="modal fade" id="spec-edit-modal" tabindex="-1" role="dialog" aria-labelledby="specEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border:0;">
            <div class="modal-header" style="background:#6366f1; color:#fff; padding:16px 20px;">
                <h5 class="modal-title m-0" id="specEditModalLabel" style="font-size:15px; font-weight:600;">
                    <i class="ft-edit-2 mr-1"></i> Edit Specification
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:.9;">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <input type="hidden" id="spec-edit-id">

                <div class="form-group mb-3">
                    <label class="font-weight-bold" style="font-size:13px;">Attribute <span class="text-danger">*</span></label>
                    <select id="spec-edit-label" class="form-control select2" style="width: 100%;">
                        <option value="">-- Select Attribute --</option>
                        @foreach($spec_attributes as $attr)
                            <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold" style="font-size:13px;">Value Type</label>
                    <div class="d-flex" style="gap:10px;">
                        <label class="spec-type-option" data-val="text" style="flex:1; padding:10px 14px; border:2px solid #6366f1; border-radius:8px; cursor:pointer; text-align:center; background:#f5f3ff; font-size:13px; font-weight:500;">
                            <i class="ft-type d-block mb-1" style="font-size:18px;"></i> Plain Text
                        </label>
                        <label class="spec-type-option" data-val="richtext" style="flex:1; padding:10px 14px; border:2px solid #e5e7eb; border-radius:8px; cursor:pointer; text-align:center; background:#fff; font-size:13px; font-weight:500;">
                            <i class="ft-bold d-block mb-1" style="font-size:18px;"></i> Rich Text
                        </label>
                    </div>
                    <input type="hidden" id="spec-edit-value-type" value="text">
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold" style="font-size:13px;">Value</label>
                    <textarea id="spec-edit-value-plain" class="form-control" rows="3" placeholder="Enter value..." style="font-size:13px; resize:vertical;"></textarea>
                    <div id="spec-edit-richtext-wrap" style="display:none;">
                        <textarea id="spec-edit-value-rich"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:12px 20px; background:#f9fafb; border-top:1px solid #f3f4f6;">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="font-size:13px;">Cancel</button>
                <button type="button" id="btn-spec-modal-save" class="btn btn-primary" style="font-size:13px; font-weight:600; background:#6366f1; border-color:#6366f1;">
                    <i class="ft-save"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Copy Specs --}}
<div class="modal fade" id="spec-copy-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border:0;">
            <div class="modal-header" style="background:#f3f4f6; padding:16px 20px; border-bottom:1px solid #e5e7eb;">
                <h5 class="modal-title m-0" style="font-size:15px; font-weight:600;"><i class="ft-copy mr-1 text-muted"></i> Copy Specifications</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <div class="alert alert-warning mb-3" style="font-size:12px; padding:10px;"><i class="ft-alert-triangle"></i> This will <strong>overwrite</strong> all current specifications for this variant.</div>
                <div class="form-group mb-0">
                    <label class="font-weight-bold" style="font-size:13px;">Source Variant</label>
                    <select id="spec-copy-source" class="form-control" style="font-size:13px;">
                        <option value="">-- Select variant to copy from --</option>
                        @foreach($product->variants as $v)
                            <option value="{{ $v->id }}">{{ $v->name }} {{ $v->sku ? '('.$v->sku.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="padding:12px 20px; background:#f9fafb; border-top:1px solid #f3f4f6;">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="font-size:13px;">Cancel</button>
                <button type="button" id="btn-spec-copy-confirm" class="btn btn-primary" style="font-size:13px; font-weight:600;"><i class="ft-check"></i> Copy</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Save Template --}}
<div class="modal fade" id="spec-save-template-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border:0;">
            <div class="modal-header" style="background:#f3f4f6; padding:16px 20px; border-bottom:1px solid #e5e7eb;">
                <h5 class="modal-title m-0" style="font-size:15px; font-weight:600;"><i class="ft-save mr-1 text-muted"></i> Save as Template</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <p class="text-muted" style="font-size:12px;">Save the current specification structure (labels & values) as a reusable template for other variants or products.</p>
                <div class="form-group mb-0">
                    <label class="font-weight-bold" style="font-size:13px;">Template Name <span class="text-danger">*</span></label>
                    <input type="text" id="spec-template-name" class="form-control" placeholder="e.g. Standard Pendant Specs" style="font-size:13px;">
                </div>
            </div>
            <div class="modal-footer" style="padding:12px 20px; background:#f9fafb; border-top:1px solid #f3f4f6;">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="font-size:13px;">Cancel</button>
                <button type="button" id="btn-spec-save-template-confirm" class="btn btn-primary" style="font-size:13px; font-weight:600;"><i class="ft-check"></i> Save</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Apply Template --}}
<div class="modal fade" id="spec-apply-template-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border:0;">
            <div class="modal-header" style="background:#f3f4f6; padding:16px 20px; border-bottom:1px solid #e5e7eb;">
                <h5 class="modal-title m-0" style="font-size:15px; font-weight:600;"><i class="ft-download mr-1 text-muted"></i> Apply Template</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <div class="alert alert-warning mb-3" style="font-size:12px; padding:10px;"><i class="ft-alert-triangle"></i> This will <strong>overwrite</strong> all current specifications for this variant.</div>
                <div class="form-group mb-0">
                    <label class="font-weight-bold" style="font-size:13px;">Select Template</label>
                    <select id="spec-template-select" class="form-control" style="font-size:13px;">
                        <option value="">Loading templates...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="padding:12px 20px; background:#f9fafb; border-top:1px solid #f3f4f6;">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="font-size:13px;">Cancel</button>
                <button type="button" id="btn-spec-apply-template-confirm" class="btn btn-primary" style="font-size:13px; font-weight:600;"><i class="ft-check"></i> Apply</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal: Search & Add Related Product --}}
<div class="modal fade" id="related-search-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border:0;">
            <div class="modal-header" style="background:#f3f4f6; padding:16px 20px; border-bottom:1px solid #e5e7eb;">
                <h5 class="modal-title m-0" style="font-size:15px; font-weight:600;"><i class="ft-search mr-1 text-muted"></i> Add <span id="related-search-type-label"></span> Product</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <input type="hidden" id="related-search-type">
                <div class="form-group position-relative mb-3">
                    <input type="text" id="related-search-input" class="form-control" placeholder="Search by name or SKU..." style="font-size:13px; padding-left:36px; border-radius:8px;">
                    <i class="ft-search position-absolute text-muted" style="left:12px; top:10px; font-size:16px;"></i>
                </div>
                
                <div id="related-search-results" style="max-height:300px; overflow-y:auto; border:1px solid #eee; border-radius:8px; display:none;">
                    <!-- Results injected here -->
                </div>
                <div id="related-search-loading" class="text-center p-3 text-muted" style="display:none; font-size:13px;">
                    <i class="ft-loader spinner font-large-1 mb-1"></i><br>Searching...
                </div>
            </div>
            <div class="modal-footer" style="padding:12px 20px; background:#f9fafb; border-top:1px solid #f3f4f6;">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="font-size:13px;">Close</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection


@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
$(document).ready(function() {

    // ================================================================
    // GLOBAL INIT
    // ================================================================

    // Move modals to body to fix backdrop/z-index issues
    $('#spec-edit-modal, #spec-copy-modal, #spec-save-template-modal, #spec-apply-template-modal').appendTo('body');

    // Select2
    if ($.fn.select2) {
        $('.select2').select2({ width: '100%' });
    }

    // TinyMCE
    tinymce.init({
        selector: '#description',
        height: 400,
        convert_urls: false,
        relative_urls: false,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons help',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | link image media | fullscreen preview',
        menubar: 'file edit view insert format tools table help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        setup: function(editor) {
            editor.on('change', function() { tinymce.triggerSave(); });
        }
    });

    // ================================================================
    // SLUG GENERATOR
    // ================================================================

    var isSlugManual = false;
    $('#slug').on('input', function() {
        isSlugManual = $(this).val().length > 0;
    });
    $('#name').on('input', function() {
        if (!isSlugManual) {
            var slug = $(this).val().toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-');
            $('#slug').val(slug);
        }
    });

    // ================================================================
    // TAB SWITCHING
    // ================================================================

    $('#wizard-tabs a').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) {
            toastr.warning('Please save the product first to access this tab.');
            return;
        }
        var target = $(this).attr('href').replace('#', 'pane-');
        $('#wizard-tabs a').removeClass('active');
        $(this).addClass('active');
        $('.wizard-pane').removeClass('active').hide();
        $('#' + target).show().addClass('active');
        if ($.fn.select2) {
            $('#' + target).find('.select2').select2({ width: '100%' });
        }
        history.replaceState(null, null, $(this).attr('href'));
    });

    if (window.location.hash) {
        var $target = $('#wizard-tabs a[href="' + window.location.hash + '"]');
        if ($target.length && !$target.hasClass('disabled')) {
            $target.trigger('click');
        }
    }

    // ================================================================
    // SAVE WIZARD (BASIC INFO)
    // ================================================================

    $('#btn-save-wizard').on('click', function() {
        var $activeForm = $('#form-basic');
        if (!$activeForm.length) return;
        tinymce.triggerSave();
        var formData = new FormData($activeForm[0]);
        $.ajax({
            url: $activeForm.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#btn-save-wizard').attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');
            },
            success: function(res) {
                $('#btn-save-wizard').attr('disabled', false).html('Save Changes');
                if (res.success) {
                    toastr.success(res.message);
                    if (res.redirect) {
                        setTimeout(function() { window.location.href = res.redirect; }, 500);
                    }
                } else {
                    toastr.error(res.message || 'Error saving data.');
                }
            },
            error: function(err) {
                $('#btn-save-wizard').attr('disabled', false).html('Save Changes');
                if (err.responseJSON && err.responseJSON.errors) {
                    toastr.error(Object.values(err.responseJSON.errors)[0][0]);
                } else {
                    toastr.error('Server error occurred.');
                }
            }
        });
    });

    // ================================================================
    // DELETE PRODUCT
    // ================================================================

    $('#btn-delete-product').on('click', function() {
        Swal.fire({
            title: 'Delete Product?',
            text: 'All variants, images, and specifications will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ isset($product) ? route('decorative_product_admin.destroy', $product->id) : '' }}',
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.success) {
                            toastr.success(res.message);
                            setTimeout(function() { window.location.href = '{{ route('decorative_product_admin') }}'; }, 500);
                        }
                    },
                    error: function() { toastr.error('Error deleting product.'); }
                });
            }
        });
    });

@if(isset($product))

    // ================================================================
    // VARIANTS SORTABLE
    // ================================================================

    var variantList = document.getElementById('variants-list');
    if (variantList) {
        new Sortable(variantList, {
            handle: '.handle',
            animation: 150,
            onEnd: function() {
                var order = {};
                $('#variants-list tr[data-id]').each(function(i) { order[$(this).data('id')] = i + 1; });
                $.ajax({
                    url: '{{ route('decorative_product_admin.variants.reorder', $product->id) }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', orders: order }
                });
            }
        });
    }

    // ================================================================
    // GALLERY SORTABLE (drag-only, save on button)
    // ================================================================

    var galleryGrid = document.getElementById('gallery-grid');
    if (galleryGrid) {
        new Sortable(galleryGrid, { handle: '.handle', animation: 150 });
    }

    // ================================================================
    // VARIANT IMAGES TAB
    // ================================================================

    $('#image-variant-select').on('change', function() {
        var val = $(this).val();
        if (val) {
            var mainImg  = $(this).find('option:selected').data('main');
            var lightImg = $(this).find('option:selected').data('lighton');
            $('#variant_image_id').val(val);
            $('#form-variant-images')[0].reset();

            if (mainImg)  { $('#variant-main-preview').attr('src', mainImg).show();   $('#variant-main-placeholder').hide(); }
            else          { $('#variant-main-preview').hide();  $('#variant-main-placeholder').show(); }

            if (lightImg) { $('#variant-lighton-preview').attr('src', lightImg).show(); $('#variant-lighton-placeholder').hide(); }
            else          { $('#variant-lighton-preview').hide(); $('#variant-lighton-placeholder').show(); }

            $('#variant-image-fields').fadeIn(200);
        } else {
            $('#variant-image-fields').hide();
        }
    });

    $('#btn-save-variant-images').on('click', function() {
        var id = $('#variant_image_id').val();
        if (!id) return;
        var formData = new FormData($('#form-variant-images')[0]);
        $.ajax({
            url: '{{ url('admin/decorative-products/variants') }}/' + id + '/images',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() { $('#btn-save-variant-images').attr('disabled', true).html('<i class="ft-loader spinner"></i> Uploading...'); },
            success: function(res) {
                $('#btn-save-variant-images').attr('disabled', false).html('<i class="ft-upload"></i> Upload & Save');
                if (res.success) {
                    toastr.success(res.message);
                    var mainUrl  = res.variant.main_image   ? '{{ asset('storage/uploads/decorative') }}/' + res.variant.main_image   : '';
                    var lightUrl = res.variant.lighton_image ? '{{ asset('storage/uploads/decorative') }}/' + res.variant.lighton_image : '';
                    $('#image-variant-select option[value="' + id + '"]').data('main', mainUrl).data('lighton', lightUrl);
                    if (mainUrl)  { $('#variant-main-preview').attr('src', mainUrl).show();   $('#variant-main-placeholder').hide(); }
                    if (lightUrl) { $('#variant-lighton-preview').attr('src', lightUrl).show(); $('#variant-lighton-placeholder').hide(); }
                }
            },
            error: function() {
                $('#btn-save-variant-images').attr('disabled', false).html('<i class="ft-upload"></i> Upload & Save');
                toastr.error('Error uploading images');
            }
        });
    });

    // ================================================================
    // GALLERY UPLOAD (preview-only, saved on button click)
    // ================================================================

    var newGalleryFiles = [];

    $('#gallery-file-input').on('change', function(e) {
        var files = e.target.files;
        if (!files.length) return;
        $('#no-gallery-msg').hide();
        Array.from(files).forEach(function(file) {
            var fileId = 'new_' + Math.random().toString(36).substr(2, 9);
            newGalleryFiles.push({ id: fileId, file: file });
            var reader = new FileReader();
            reader.onload = function(ev) {
                var html = '<div class="col-md-4 col-sm-6 col-6 mb-3 gallery-item" data-id="' + fileId + '">' +
                    '<div class="card border mb-0 shadow-sm">' +
                    '<div class="card-img-top handle" style="height:150px; background:url(\'' + ev.target.result + '\') center/cover; cursor:move; position:relative; border-bottom:1px solid #eee;">' +
                    '<div style="position:absolute; top:5px; left:5px;"><span class="badge badge-success">New</span></div>' +
                    '<div style="position:absolute; top:5px; right:5px;"><button type="button" class="btn btn-sm btn-danger btn-delete-gallery p-1" data-id="' + fileId + '" style="line-height:1;"><i class="ft-trash-2"></i></button></div>' +
                    '</div>' +
                    '<div class="card-body p-2 bg-light"><input type="text" class="form-control form-control-sm gallery-caption" placeholder="Caption..." data-id="' + fileId + '"></div>' +
                    '</div></div>';
                $('#gallery-grid').append(html);
            };
            reader.readAsDataURL(file);
        });
        $(this).val('');
    });

    $('#btn-save-gallery').on('click', function() {
        var $btn = $(this);
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');
        var orders = {};
        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        $('.gallery-item').each(function(index) {
            var id      = String($(this).data('id'));
            var caption = $(this).find('.gallery-caption').val() || '';
            if (id.startsWith('new_')) {
                var f = newGalleryFiles.find(function(x) { return x.id === id; });
                if (f) { formData.append('new_images[]', f.file); formData.append('new_captions[]', caption); }
            } else {
                orders[id] = index + 1;
                $.ajax({ url: '{{ url('admin/decorative-products/gallery') }}/' + id, method: 'PUT', data: { _token: '{{ csrf_token() }}', caption: caption } });
            }
        });

        // Save order for existing
        $.ajax({ url: '{{ route('decorative_product_admin.gallery.reorder', $product->id) }}', method: 'POST', data: { _token: '{{ csrf_token() }}', orders: orders } });

        if (newGalleryFiles.length > 0) {
            newGalleryFiles.forEach(function(item) { formData.append('temp_ids[]', item.id); });
            $.ajax({
                url: '{{ route('decorative_product_admin.gallery.store', $product->id) }}',
                method: 'POST', data: formData, processData: false, contentType: false,
                success: function(res) {
                    if (res.success && res.images) {
                        res.images.forEach(function(img) {
                            var item = $('.gallery-item[data-id="' + img.temp_id + '"]');
                            item.attr('data-id', img.id);
                            item.find('.btn-delete-gallery').attr('data-id', img.id);
                            item.find('.gallery-caption').attr('data-id', img.id);
                            item.find('.badge-success').closest('div').remove();
                        });
                        newGalleryFiles = [];
                    }
                    $btn.attr('disabled', false).html('<i class="ft-save"></i> Save Order/Captions');
                    toastr.success('Gallery saved successfully!');
                },
                error: function() {
                    $btn.attr('disabled', false).html('<i class="ft-save"></i> Save Order/Captions');
                    toastr.error('Error uploading new images.');
                }
            });
        } else {
            setTimeout(function() {
                $btn.attr('disabled', false).html('<i class="ft-save"></i> Save Order/Captions');
                toastr.success('Gallery updated successfully!');
            }, 400);
        }
    });

    $(document).on('click', '.btn-delete-gallery', function() {
        var id   = String($(this).data('id'));
        var card = $(this).closest('.gallery-item');
        if (id.startsWith('new_')) {
            newGalleryFiles = newGalleryFiles.filter(function(f) { return f.id !== id; });
            card.fadeOut(function() { $(this).remove(); });
        } else {
            $.ajax({
                url: '{{ url('admin/decorative-products/gallery') }}/' + id,
                method: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                success: function(res) { if (res.success) { card.fadeOut(function() { $(this).remove(); }); } }
            });
        }
    });

    // ================================================================
    // VARIANT FORM (ADD / EDIT)
    // ================================================================

    $('#btn-add-variant, #btn-cancel-variant').on('click', function() {
        $('#form-variant')[0].reset();
        $('#form-variant .select2').trigger('change');
        $('#variant_id').val('');
        $('#variant-form-title').text('Add Variant');
        $('#btn-cancel-variant').hide();
    });

    $('#btn-save-variant').on('click', function() {
        var id     = $('#variant_id').val();
        var url    = id ? '{{ url('admin/decorative-products/variants') }}/' + id : '{{ route('decorative_product_admin.variants.store', $product->id) }}';
        var method = id ? 'PUT' : 'POST';
        $.ajax({
            url: url, method: method,
            data: $('#form-variant').serialize() + '&_token={{ csrf_token() }}',
            beforeSend: function() { $('#btn-save-variant').attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...'); },
            success: function(res) {
                $('#btn-save-variant').attr('disabled', false).html('Save Variant');
                if (res.success) { toastr.success(res.message); window.location.reload(); }
            },
            error: function(err) {
                $('#btn-save-variant').attr('disabled', false).html('Save Variant');
                if (err.responseJSON && err.responseJSON.errors) {
                    toastr.error(Object.values(err.responseJSON.errors)[0][0]);
                } else { toastr.error('Server error occurred.'); }
            }
        });
    });

    $(document).on('click', '.btn-edit-variant', function() {
        var id = $(this).data('id');
        $.get('{{ url('admin/decorative-products/variants') }}/' + id, function(res) {
            if (res.success) {
                $('#variant_id').val(res.variant.id);
                $('#variant_name').val(res.variant.name);
                $('#variant_sku').val(res.variant.sku);
                $('#variant_size').val(res.variant.size);
                $('#variant_color_master_id').val(res.variant.color_master_id).trigger('change');
                $('#variant_status').val(res.variant.status).trigger('change');
                $('#variant-form-title').text('Edit Variant');
                $('#btn-cancel-variant').show();
                $('html, body').animate({ scrollTop: $('#variant-form-title').offset().top - 100 }, 400);
            }
        });
    });

    $(document).on('click', '.btn-delete-variant', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Delete Variant?',
            text: 'All images and specs related to this variant will be lost.',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete!'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('admin/decorative-products/variants') }}/' + id,
                    method: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.success) {
                            toastr.success(res.message);
                            $('tr[data-id="' + id + '"]').fadeOut(function() { $(this).remove(); });
                        }
                    }
                });
            }
        });
    });

    // ================================================================
    // SPECIFICATIONS TAB
    // ================================================================

    // Variant card selection
    var currentSpecVariantId = null;

    $(document).on('click', '.spec-variant-card', function() {
        $('.spec-variant-card').css({ 'border-color': '#e5e7eb', 'background': '#fff', 'box-shadow': 'none' });
        $(this).css({ 'border-color': '#6366f1', 'background': '#f5f3ff', 'box-shadow': '0 0 0 3px rgba(99,102,241,.15)' });
        currentSpecVariantId = $(this).data('id');
        var variantName = $(this).find('div > div').first().text().trim();
        var status = $(this).data('status');
        
        $('#spec-variant-name').text(variantName);
        
        var $badge = $('#spec-variant-status-badge');
        if (status === 'active') {
            $badge.text('Active').removeClass('badge-secondary').addClass('badge-success');
        } else {
            $badge.text('Inactive').removeClass('badge-success').addClass('badge-secondary');
        }
        
        loadVariantSpecs(currentSpecVariantId);
    });

    function renderSpecRow(row) {
        var isRich = row.value_type === 'richtext';
        var valDisplay = isRich
            ? '<span class="badge badge-light" style="font-size:10px; color:#6366f1;">Rich Text</span> '
            : '';
        var safeLabel = row.label ? row.label.replace(/"/g, '&quot;') : '';
        var rawVal = row.value || '';
        
        var displayVal = rawVal;
        if (rawVal) {
            var strippedVal = rawVal.replace(/<[^>]*>?/gm, ''); // strip HTML
            displayVal = '<div style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="' + strippedVal.replace(/"/g, '&quot;') + '">' + strippedVal + '</div>';
        } else {
            displayVal = '<span class="text-muted" style="font-size:12px;">— empty —</span>';
        }

        return '<tr data-id="' + row.id + '" data-value-type="' + row.value_type + '" data-label="' + safeLabel + '" class="spec-row" style="border-bottom:1px solid #f3f4f6;">' +
            '<td style="padding:10px 8px; vertical-align:middle; width:28px;"><i class="ft-menu handle text-muted" style="cursor:move; font-size:14px;"></i></td>' +
            '<td style="padding:10px 12px; vertical-align:middle; font-weight:500; color:#374151;">' + (row.label || '') + '</td>' +
            '<td style="padding:10px 12px; vertical-align:middle; color:#4b5563;">' + valDisplay + displayVal + '</td>' +
            '<td style="padding:10px 8px; vertical-align:middle; text-align:right; white-space:nowrap;">' +
                '<button type="button" class="btn btn-sm btn-edit-spec p-1 mr-1" data-id="' + row.id + '" style="color:#6366f1; background:none; border:none; font-size:14px;"><i class="ft-edit-2"></i></button>' +
                '<button type="button" class="btn btn-sm btn-delete-spec p-1" data-id="' + row.id + '" style="color:#ef4444; background:none; border:none; font-size:14px;"><i class="ft-trash-2"></i></button>' +
            '</td>' +
            '</tr>';
    }

    function initSpecSortables() {
        $('.spec-tbody').each(function() {
            if (this._sortable) this._sortable.destroy();
            this._sortable = new Sortable(this, {
                handle: '.handle', animation: 150,
                onEnd: function(evt) {
                    var tbody     = $(evt.item).closest('tbody');
                    var orders    = {};
                    tbody.find('tr').each(function(i) { orders[$(this).data('id')] = i + 1; });
                    $.ajax({
                        url: '{{ url('admin/decorative-products/variants') }}/' + currentSpecVariantId + '/specs/reorder',
                        method: 'POST', data: { _token: '{{ csrf_token() }}', orders: orders }
                    });
                }
            });
        });
    }

    function loadVariantSpecs(variantId) {
        if (!variantId) {
            $('#spec-content-area').hide();
            $('#spec-placeholder').show();
            return;
        }
        $('#spec-placeholder').hide();
        $('#spec-content-area').show();
        $('.spec-tbody').empty();

        $.get('{{ url('admin/decorative-products/variants') }}/' + variantId + '/specs', function(res) {
            if (!res.success) return;
            ['basic_specifications', 'dimensions'].forEach(function(section) {
                var tbody       = $('.spec-tbody[data-section="' + section + '"]');
                var noMsgId    = section === 'basic_specifications' ? '#no-specs-basic' : '#no-specs-dimensions';
                var sectionRows = res.rows[section];
                if (sectionRows && sectionRows.length > 0) {
                    $(noMsgId).hide();
                    sectionRows.forEach(function(row) {
                        var $tr = $(renderSpecRow(row));
                        $tr.data('value', row.value || '');
                        tbody.append($tr);
                    });
                } else {
                    $(noMsgId).show();
                }
            });
            initSpecSortables();
        });
    }

    // Add spec row
    $(document).on('click', '.btn-add-spec', function() {
        var section   = $(this).data('section');
        if (!currentSpecVariantId) { toastr.error('Select a variant first'); return; }
        var $btn = $(this);
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i>');
        
        // Grab first attribute as default
        var defaultAttrId = $('#spec-edit-label option:nth-child(2)').val() || 1;

        $.ajax({
            url: '{{ url('admin/decorative-products/variants') }}/' + currentSpecVariantId + '/specs',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', section: section, dec_spec_attribute_id: defaultAttrId },
            success: function(res) {
                $btn.attr('disabled', false).html('<i class="ft-plus"></i> Add Row');
                if (res.success) {
                    var tbody    = $('.spec-tbody[data-section="' + section + '"]');
                    var noMsgId = section === 'basic_specifications' ? '#no-specs-basic' : '#no-specs-dimensions';
                    $(noMsgId).hide();
                    tbody.append(renderSpecRow(res.row));
                    initSpecSortables();
                    // immediately open edit for the new row
                    openSpecEdit(res.row);
                }
            }
        });
    });

    // Open edit modal for a spec row
    function openSpecEdit(row) {
        $('#spec-edit-id').val(row.id);
        
        // Find the option in the select dropdown that matches the row's label text, or set by id if we had it
        // We can just find the option by text since we have the label
        var matchingOption = $('#spec-edit-label option').filter(function() { return $(this).text() === row.label; });
        if (matchingOption.length) {
            $('#spec-edit-label').val(matchingOption.val()).trigger('change');
        } else if (row.dec_spec_attribute_id) {
            $('#spec-edit-label').val(row.dec_spec_attribute_id).trigger('change');
        } else {
            $('#spec-edit-label').val($('#spec-edit-label option:nth-child(2)').val()).trigger('change');
        }

        $('#spec-edit-value-type').val(row.value_type || 'text');

        // Set active type option button
        $('.spec-type-option').css({ 'border-color': '#e5e7eb', 'background': '#fff' });
        $('.spec-type-option[data-val="' + (row.value_type || 'text') + '"]').css({ 'border-color': '#6366f1', 'background': '#f5f3ff' });

        if (row.value_type === 'richtext') {
            $('#spec-edit-value-plain').hide();
            $('#spec-edit-richtext-wrap').show();
            if (tinymce.get('spec-edit-value-rich')) {
                tinymce.get('spec-edit-value-rich').setContent(row.value || '');
            } else {
                tinymce.init({
                    selector: '#spec-edit-value-rich',
                    height: 220,
                    plugins: 'lists link charmap textcolor colorpicker',
                    toolbar: 'bold italic underline forecolor backcolor | fontsize | bullist numlist | link | removeformat',
                    menubar: false,
                    statusbar: false,
                    content_style: 'body { font-family:Inter,sans-serif; font-size:13px; }',
                    setup: function(ed) { ed.on('change', function() { tinymce.triggerSave(); }); },
                    init_instance_callback: function(ed) { ed.setContent(row.value || ''); }
                });
            }
        } else {
            $('#spec-edit-value-plain').show().val(row.value || '');
            $('#spec-edit-richtext-wrap').hide();
            if (tinymce.get('spec-edit-value-rich')) tinymce.get('spec-edit-value-rich').remove();
        }

        $('#spec-edit-modal').modal('show');
    }

    // Reinit tinymce when value type changes in modal
    $('#spec-edit-value-type').on('change', function() {
        var type = $(this).val();
        if (type === 'richtext') {
            $('#spec-edit-value-plain').hide();
            $('#spec-edit-richtext-wrap').show();
            var plainVal = $('#spec-edit-value-plain').val();
            if (!tinymce.get('spec-edit-value-rich')) {
                tinymce.init({
                    selector: '#spec-edit-value-rich',
                    height: 220,
                    plugins: 'lists link charmap textcolor colorpicker',
                    toolbar: 'bold italic underline forecolor backcolor | fontsize | bullist numlist | link | removeformat',
                    menubar: false, statusbar: false,
                    content_style: 'body { font-family:Inter,sans-serif; font-size:13px; }',
                    setup: function(ed) { ed.on('change', function() { tinymce.triggerSave(); }); },
                    init_instance_callback: function(ed) { ed.setContent(plainVal); }
                });
            }
        } else {
            $('#spec-edit-richtext-wrap').hide();
            $('#spec-edit-value-plain').show();
            if (tinymce.get('spec-edit-value-rich')) {
                $('#spec-edit-value-plain').val(tinymce.get('spec-edit-value-rich').getContent({ format: 'text' }));
                tinymce.get('spec-edit-value-rich').remove();
            }
        }
    });

    // Click edit button on row
    $(document).on('click', '.btn-edit-spec', function() {
        var tr = $(this).closest('tr');
        var id = tr.data('id');
        openSpecEdit({
            id: id,
            label: tr.attr('data-label') || tr.find('td:nth-child(2)').text().trim(),
            value_type: tr.data('value-type'),
            value: tr.data('value') || ''
        });
    });

    // Save from modal
    $('#btn-spec-modal-save').on('click', function() {
        var id     = $('#spec-edit-id').val();
        var type   = $('#spec-edit-value-type').val();
        var attrId = $('#spec-edit-label').val();
        var label  = $('#spec-edit-label option:selected').text();
        
        var value  = type === 'richtext'
            ? (tinymce.get('spec-edit-value-rich') ? tinymce.get('spec-edit-value-rich').getContent() : '')
            : $('#spec-edit-value-plain').val();

        var $btn = $(this);
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');

        $.ajax({
            url: '{{ url('admin/decorative-products/specs') }}/' + id,
            method: 'PUT',
            data: { _token: '{{ csrf_token() }}', dec_spec_attribute_id: attrId, value: value, value_type: type },
            success: function(res) {
                $btn.attr('disabled', false).html('<i class="ft-save"></i> Save');
                if (res.success) {
                    // Update the row display
                    var tr = $('.spec-tbody tr[data-id="' + id + '"]');
                    tr.data('value-type', type).data('value', value).attr('data-label', label);
                    var isRich = type === 'richtext';
                    var badge  = isRich ? '<span class="badge badge-light" style="font-size:10px; color:#6366f1;">Rich Text</span> ' : '';
                    
                    var displayVal = value;
                    if (value) {
                        var strippedVal = value.replace(/<[^>]*>?/gm, '');
                        displayVal = '<div style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="' + strippedVal.replace(/"/g, '&quot;') + '">' + strippedVal + '</div>';
                    } else {
                        displayVal = '<span class="text-muted" style="font-size:12px;">— empty —</span>';
                    }
                    
                    tr.find('td:nth-child(2)').text(label);
                    tr.find('td:nth-child(3)').html(badge + displayVal);
                    $('#spec-edit-modal').modal('hide');
                    toastr.success('Saved');
                }
            },
            error: function() {
                $btn.attr('disabled', false).html('<i class="ft-save"></i> Save');
                toastr.error('Error saving');
            }
        });
    });

    // Type option toggle in spec modal
    $(document).on('click', '.spec-type-option', function() {
        var val = $(this).data('val');
        $('#spec-edit-value-type').val(val).trigger('change');
        $('.spec-type-option').css({ 'border-color': '#e5e7eb', 'background': '#fff' });
        $(this).css({ 'border-color': '#6366f1', 'background': '#f5f3ff' });
    });

    // Reset modal tinymce on close
    $('#spec-edit-modal').on('hidden.bs.modal', function() {
        if (tinymce.get('spec-edit-value-rich')) tinymce.get('spec-edit-value-rich').remove();
    });

    // Explicitly hide modal on cancel/close since it was moved to body
    $(document).on('click', '#spec-edit-modal .close, #spec-edit-modal [data-dismiss="modal"], #spec-copy-modal .close, #spec-copy-modal [data-dismiss="modal"], #spec-save-template-modal .close, #spec-save-template-modal [data-dismiss="modal"], #spec-apply-template-modal .close, #spec-apply-template-modal [data-dismiss="modal"]', function() {
        var modal = $(this).closest('.modal');
        if (modal.length) {
            modal.modal('hide');
        }
    });

    // Delete spec row
    $(document).on('click', '.btn-delete-spec', function() {
        if (!confirm('Are you sure you want to delete this specification?')) return;
        var id    = $(this).data('id');
        var tr    = $(this).closest('tr');
        var tbody = tr.closest('tbody');
        $.ajax({
            url: '{{ url('admin/decorative-products/specs') }}/' + id,
            method: 'DELETE', data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    tr.fadeOut(function() {
                        $(this).remove();
                        if (tbody.find('tr').length === 0) {
                            var section = tbody.data('section');
                            var noMsgId = section === 'basic_specifications' ? '#no-specs-basic' : '#no-specs-dimensions';
                            $(noMsgId).show();
                        }
                    });
                    if (res.message) toastr.success(res.message);
                } else {
                    if (res.message) toastr.error(res.message);
                }
            },
            error: function() {
                toastr.error('Error deleting specification.');
            }
        });
    });

    // ================================================================
    // SPECIFICATIONS - COPY & TEMPLATES
    // ================================================================

    // Copy Specs
    $('#btn-open-copy-spec').on('click', function() {
        if (!currentSpecVariantId) { toastr.error('Select a variant first'); return; }
        $('#spec-copy-source option').prop('disabled', false).show();
        $('#spec-copy-source option[value="' + currentSpecVariantId + '"]').prop('disabled', true).hide();
        $('#spec-copy-source').val('');
        $('#spec-copy-modal').modal('show');
    });

    $('#btn-spec-copy-confirm').on('click', function() {
        var sourceId = $('#spec-copy-source').val();
        if (!sourceId) { toastr.error('Select a source variant'); return; }
        
        var $btn = $(this);
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Copying...');
        
        $.ajax({
            url: '{{ url('admin/decorative-products/variants') }}/' + currentSpecVariantId + '/specs/copy',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', source_variant_id: sourceId },
            success: function(res) {
                $btn.attr('disabled', false).html('<i class="ft-check"></i> Copy');
                if (res.success) {
                    $('#spec-copy-modal').modal('hide');
                    toastr.success(res.message);
                    loadVariantSpecs(currentSpecVariantId); // reload rows
                } else {
                    toastr.error(res.message);
                }
            },
            error: function() {
                $btn.attr('disabled', false).html('<i class="ft-check"></i> Copy');
                toastr.error('Error copying specifications.');
            }
        });
    });

    // Save Template
    $('#btn-open-save-template').on('click', function() {
        if (!currentSpecVariantId) { toastr.error('Select a variant first'); return; }
        $('#spec-template-name').val('');
        $('#spec-save-template-modal').modal('show');
    });

    $('#btn-spec-save-template-confirm').on('click', function() {
        var name = $('#spec-template-name').val().trim();
        if (!name) { toastr.error('Template name is required'); return; }
        
        var $btn = $(this);
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');
        
        $.ajax({
            url: '{{ url('admin/decorative-products/variants') }}/' + currentSpecVariantId + '/specs/templates',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', name: name },
            success: function(res) {
                $btn.attr('disabled', false).html('<i class="ft-check"></i> Save');
                if (res.success) {
                    $('#spec-save-template-modal').modal('hide');
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function() {
                $btn.attr('disabled', false).html('<i class="ft-check"></i> Save');
                toastr.error('Error saving template.');
            }
        });
    });

    // Apply Template
    $('#btn-open-apply-template').on('click', function() {
        if (!currentSpecVariantId) { toastr.error('Select a variant first'); return; }
        
        $('#spec-template-select').html('<option value="">Loading templates...</option>');
        $('#spec-apply-template-modal').modal('show');
        
        $.get('{{ url('admin/decorative-products/specs/templates') }}', function(res) {
            if (res.success) {
                var opts = '<option value="">-- Select a template --</option>';
                res.templates.forEach(function(t) {
                    opts += '<option value="' + t.id + '">' + t.name + '</option>';
                });
                $('#spec-template-select').html(opts);
            }
        });
    });

    $('#btn-spec-apply-template-confirm').on('click', function() {
        var templateId = $('#spec-template-select').val();
        if (!templateId) { toastr.error('Select a template'); return; }
        
        var $btn = $(this);
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Applying...');
        
        $.ajax({
            url: '{{ url('admin/decorative-products/variants') }}/' + currentSpecVariantId + '/specs/templates/apply',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', template_id: templateId },
            success: function(res) {
                $btn.attr('disabled', false).html('<i class="ft-check"></i> Apply');
                if (res.success) {
                    $('#spec-apply-template-modal').modal('hide');
                    toastr.success(res.message);
                    loadVariantSpecs(currentSpecVariantId); // reload rows
                } else {
                    toastr.error(res.message);
                }
            },
            error: function() {
                $btn.attr('disabled', false).html('<i class="ft-check"></i> Apply');
                toastr.error('Error applying template.');
            }
        });
    });

@endif

    // ================================================================
    // PHASE 7 - DOWNLOADS TAB
    // ================================================================

@if(isset($product))
    $('.btn-replace-download').on('click', function() {
        var type = $(this).data('type');
        var idPrefix = type === 'installation_guide' ? 'installation-guide' : 'care-instructions';
        $('#' + idPrefix + '-display').hide();
        $('#' + idPrefix + '-upload').show();
    });

    $('.form-download-upload').on('submit', function(e) {
        e.preventDefault();
        var type = $(this).data('type');
        var idPrefix = type === 'installation_guide' ? 'installation-guide' : 'care-instructions';
        var formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('type', type);
        
        var $btn = $(this).find('button[type="submit"]');
        var originalBtn = $btn.html();
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Uploading...');

        $.ajax({
            url: '{{ url('admin/decorative-products/'.$product->id.'/downloads') }}',
            method: 'POST',
            data: formData,
            contentType: false, processData: false,
            success: function(res) {
                $btn.attr('disabled', false).html(originalBtn);
                if (res.success) {
                    $('#' + idPrefix + '-filename').text(res.filename);
                    $('#' + idPrefix + '-link').attr('href', '{{ asset('storage/uploads/decorative/downloads/') }}/' + res.filename);
                    $('#' + idPrefix + '-upload').hide();
                    $('#' + idPrefix + '-display').show();
                    toastr.success('File uploaded successfully');
                } else {
                    toastr.error(res.message || 'Error uploading file');
                }
            },
            error: function() {
                $btn.attr('disabled', false).html(originalBtn);
                toastr.error('Error uploading file');
            }
        });
    });

    $('.btn-remove-download').on('click', function() {
        var type = $(this).data('type');
        var idPrefix = type === 'installation_guide' ? 'installation-guide' : 'care-instructions';
        
        var $btn = $(this);
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i>');

        $.ajax({
            url: '{{ url('admin/decorative-products/'.$product->id.'/downloads') }}',
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}', type: type },
            success: function(res) {
                $btn.attr('disabled', false).html('<i class="ft-trash-2"></i>');
                if (res.success) {
                    $('#' + idPrefix + '-display').hide();
                    $('#' + idPrefix + '-upload').show();
                    $('#' + idPrefix + '-upload form')[0].reset();
                    toastr.success('File removed successfully');
                }
            }
        });
    });
@endif

    // ================================================================
    // PHASE 8 - RELATED PRODUCTS TAB
    // ================================================================

@if(isset($product))
    // Move modal to body
    $('#related-search-modal').appendTo('body');
    
    // Explicit close handler for related search modal
    $(document).on('click', '#related-search-modal .close, #related-search-modal [data-dismiss="modal"]', function() {
        $('#related-search-modal').modal('hide');
    });

    var relatedSearchTimer;

    function renderRelatedRow(item) {
        var prod = item.related_product || {};
        var image = prod.featured_image ? '{{ asset('storage/uploads/decorative/') }}/' + prod.featured_image : '';
        return '<tr data-id="' + item.id + '" style="border-bottom:1px solid #f3f4f6;">' +
            '<td style="padding:10px 8px; vertical-align:middle; width:28px;"><i class="ft-menu handle text-muted" style="cursor:move; font-size:14px;"></i></td>' +
            '<td style="padding:10px 12px; vertical-align:middle;">' +
                '<div class="d-flex align-items-center">' +
                    '<div style="width:36px; height:36px; border-radius:6px; border:1px solid #eee; background:url(\'' + image + '\') center/cover; margin-right:12px; flex-shrink:0;"></div>' +
                    '<div>' +
                        '<div style="font-weight:600; color:#374151;">' + (prod.name || 'Unknown') + '</div>' +
                    '</div>' +
                '</div>' +
            '</td>' +
            '<td style="padding:10px 8px; vertical-align:middle; text-align:right; white-space:nowrap;">' +
                '<button type="button" class="btn btn-sm btn-delete-related p-1" data-id="' + item.id + '" style="color:#ef4444; background:none; border:none; font-size:14px;"><i class="ft-trash-2"></i></button>' +
            '</td>' +
            '</tr>';
    }

    function initRelatedSortables() {
        $('.related-tbody').each(function() {
            if (this._sortable) this._sortable.destroy();
            this._sortable = new Sortable(this, {
                handle: '.handle', animation: 150,
                onEnd: function(evt) {
                    var tbody  = $(evt.item).closest('tbody');
                    var orders = {};
                    tbody.find('tr').each(function(i) { orders[$(this).data('id')] = i + 1; });
                    $.ajax({
                        url: '{{ url('admin/decorative-products/'.$product->id.'/related/reorder') }}',
                        method: 'POST', data: { _token: '{{ csrf_token() }}', orders: orders }
                    });
                }
            });
        });
    }

    function loadRelatedProducts() {
        $.get('{{ url('admin/decorative-products/'.$product->id.'/related') }}', function(res) {
            if (res.success) {
                // Update toggles
                $('#toggle-family-section').prop('checked', res.show_family_section);
                if (res.show_family_section) {
                    $('#family-section-card').css('opacity', '1');
                } else {
                    $('#family-section-card').css('opacity', '0.6');
                }

                // Render Manual
                var mBody = $('.related-tbody[data-type="manual"]').empty();
                if (res.manual.length > 0) {
                    $('#no-related-manual').hide();
                    res.manual.forEach(function(item) { mBody.append(renderRelatedRow(item)); });
                } else {
                    $('#no-related-manual').show();
                }

                initRelatedSortables();
            }
        });
    }

    // Tab clicked -> load
    $('a[href="#related"]').on('click', function() {
        if (!$(this).hasClass('disabled')) {
            loadRelatedProducts();
        }
    });

    // Toggle family
    $('#toggle-family-section').on('change', function() {
        var isChecked = $(this).is(':checked');
        $.ajax({
            url: '{{ url('admin/decorative-products/'.$product->id.'/related/toggle-family') }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', show_family_section: isChecked ? 1 : 0 },
            success: function(res) {
                if (res.success) {
                    $('#family-section-card').css('opacity', isChecked ? '1' : '0.6');
                    toastr.success('Settings updated');
                }
            }
        });
    });

    // Open Search Modal
    $('.btn-add-related').on('click', function() {
        var type = $(this).data('type');
        $('#related-search-type').val(type);
        $('#related-search-type-label').text('Related');
        $('#related-search-input').val('');
        $('#related-search-results').empty().hide();
        $('#related-search-modal').modal('show');
        setTimeout(function() { $('#related-search-input').focus(); }, 300);
    });

    // Search input typing
    $('#related-search-input').on('keyup', function() {
        var q = $(this).val().trim();
        clearTimeout(relatedSearchTimer);
        
        if (q.length < 2) {
            $('#related-search-results').empty().hide();
            return;
        }

        relatedSearchTimer = setTimeout(function() {
            $('#related-search-results').empty().hide();
            $('#related-search-loading').show();
            
            $.get('{{ url('admin/decorative-products/related/search') }}', { q: q, exclude_id: {{ $product->id }} }, function(res) {
                $('#related-search-loading').hide();
                var html = '';
                if (res.results && res.results.length > 0) {
                    html += '<ul class="list-group list-group-flush mb-0">';
                    res.results.forEach(function(p) {
                        html += '<li class="list-group-item d-flex align-items-center justify-content-between p-2" style="font-size:13px;">' +
                            '<div class="d-flex align-items-center">' +
                                '<div style="width:32px; height:32px; border-radius:4px; border:1px solid #eee; background:url(\'' + (p.image || '') + '\') center/cover; margin-right:10px;"></div>' +
                                '<div><div style="font-weight:600; color:#374151;">' + p.name + '</div><div style="font-size:11px; color:#6b7280;">' + (p.sku || '') + '</div></div>' +
                            '</div>' +
                            '<button type="button" class="btn btn-sm btn-outline-primary btn-attach-related px-2 py-1" data-id="' + p.id + '" style="font-size:12px; font-weight:600;"><i class="ft-plus"></i> Add</button>' +
                        '</li>';
                    });
                    html += '</ul>';
                } else {
                    html = '<div class="p-3 text-center text-muted" style="font-size:13px;">No products found.</div>';
                }
                $('#related-search-results').html(html).show();
            });
        }, 400);
    });

    // Attach product
    $(document).on('click', '.btn-attach-related', function() {
        var $btn = $(this);
        var relatedId = $btn.data('id');
        var type = $('#related-search-type').val();
        
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i>');

        $.ajax({
            url: '{{ url('admin/decorative-products/'.$product->id.'/related') }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', related_product_id: relatedId, type: type },
            success: function(res) {
                if (res.success) {
                    var tbody = $('.related-tbody[data-type="' + type + '"]');
                    var noMsg = $('#no-related-' + type);
                    noMsg.hide();
                    tbody.append(renderRelatedRow(res.item));
                    initRelatedSortables();
                    toastr.success(res.message);
                    $btn.removeClass('btn-outline-primary').addClass('btn-success').html('<i class="ft-check"></i> Added');
                } else {
                    $btn.attr('disabled', false).html('<i class="ft-plus"></i> Add');
                    toastr.error(res.message);
                }
            },
            error: function() {
                $btn.attr('disabled', false).html('<i class="ft-plus"></i> Add');
                toastr.error('Error adding product.');
            }
        });
    });

    // Detach product
    $(document).on('click', '.btn-delete-related', function() {
        var id = $(this).data('id');
        var tr = $(this).closest('tr');
        var tbody = tr.closest('tbody');
        var type = tbody.data('type');
        
        $.ajax({
            url: '{{ url('admin/decorative-products/related') }}/' + id,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    tr.fadeOut(function() {
                        $(this).remove();
                        if (tbody.find('tr').length === 0) {
                            $('#no-related-' + type).show();
                        }
                    });
                }
            }
        });
    });
@endif

    // ================================================================
    // PHASE 9 - SEO & SETTINGS
    // ================================================================

@if(isset($product))
    function updateSeoCounters() {
        var title = $('#meta_title').val() || '';
        var desc = $('#meta_description').val() || '';
        
        $('#meta-title-counter').text(title.length + ' / 60').css('color', title.length > 60 ? '#ef4444' : '');
        $('#meta-desc-counter').text(desc.length + ' / 160').css('color', desc.length > 160 ? '#ef4444' : '');
        
        $('#seo-preview-title').text(title || '{{ $product->name }}');
        $('#seo-preview-desc').text(desc || 'No description provided.');
    }

    $('#meta_title, #meta_description').on('keyup change', updateSeoCounters);
    updateSeoCounters(); // init

    $('#form-seo').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btn-save-seo');
        var originalBtn = $btn.html();
        
        $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');

        $.ajax({
            url: '{{ url('admin/decorative-products/'.$product->id.'/seo') }}',
            method: 'POST',
            data: $(this).serialize() + '&_token={{ csrf_token() }}',
            success: function(res) {
                $btn.attr('disabled', false).html(originalBtn);
                if (res.success) {
                    toastr.success(res.message);
                } else {
                    toastr.error('Error saving SEO settings');
                }
            },
            error: function() {
                $btn.attr('disabled', false).html(originalBtn);
                toastr.error('Server Error');
            }
        });
    });

    $('#btn-duplicate-product').on('click', function() {
        if(confirm('Are you sure you want to duplicate this product? This will clone the product, variants, and specifications.')) {
            var $btn = $(this);
            var originalBtn = $btn.html();
            $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Duplicating...');
            
            $.ajax({
                url: '{{ url('admin/decorative-products/'.$product->id.'/duplicate') }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.message);
                        window.location.href = res.redirect;
                    } else {
                        $btn.attr('disabled', false).html(originalBtn);
                        toastr.error('Error duplicating product');
                    }
                },
                error: function() {
                    $btn.attr('disabled', false).html(originalBtn);
                    toastr.error('Server Error');
                }
            });
        }
    });
    @endif

    // Initialize Select2 for Specification Attribute Dropdown
    if ($('#spec-edit-label').length) {
        $('#spec-edit-label').select2({
            dropdownParent: $('#spec-edit-modal'),
            placeholder: "-- Select Attribute --",
            allowClear: true,
            width: '100%'
        });
    }

}); // end document.ready

// ================================================================
// IMAGE PICKER PREVIEW (global, outside ready)
// ================================================================
function previewPickerImage(input) {
    if (input.classList.contains('image-picker-input')) {
        var container   = input.closest('.image-picker-container');
        var preview     = container.querySelector('.image-picker-preview');
        var placeholder = container.querySelector('.picker-placeholder');
        var img         = preview.querySelector('img');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                placeholder.style.display = 'none';
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
            img.src = '';
        }
    } else {
        var previewDiv  = input.nextElementSibling;
        var img         = previewDiv.querySelector('img');
        var placeholder = previewDiv.querySelector('span');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                if (placeholder) placeholder.style.display = 'none';
                img.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            img.style.display = 'none';
            if (placeholder) placeholder.style.display = 'block';
            img.src = '';
        }
    }
}
</script>
@endsection
