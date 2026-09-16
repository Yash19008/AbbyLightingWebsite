                <div id="pane-images" class="wizard-pane">
                    @if (isset($product))
                        <div class="row">
                            <!-- Variant Specific Images -->
                            <div class="col-md-5 mb-3">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-white pb-0 border-bottom">
                                        <h5 class="card-title m-0 pb-2"><i class="ft-image text-primary"></i> Variant
                                            Images</h5>
                                    </div>
                                    <div class="card-body pt-3">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold" style="font-size:13px;">Select
                                                Variant</label>
                                            <select id="image-variant-select" class="form-control select2">
                                                <option value="">-- Choose Variant --</option>
                                                @foreach ($product->variants as $variant)
                                                    <option value="{{ $variant->id }}"
                                                        data-main="{{ $variant->main_image ? asset('storage/uploads/decorative/' . $variant->main_image) : '' }}"
                                                        data-lighton="{{ $variant->lighton_image ? asset('storage/uploads/decorative/' . $variant->lighton_image) : '' }}">
                                                        {{ $variant->name }} {!! $variant->sku ? '(<small>' . $variant->sku . '</small>)' : '' !!}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="variant-image-fields"
                                            style="display:none; padding:15px; background:#f9fafb; border-radius:6px; border:1px solid #eee;">
                                            <form id="form-variant-images" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="variant_image_id" name="variant_id">
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold" style="font-size:13px;">Main
                                                        Image</label>
                                                    <input type="file" name="main_image"
                                                        class="form-control-file form-control-sm" accept="image/*"
                                                        onchange="previewPickerImage(this)">
                                                    <div class="mt-2 text-center bg-white border rounded p-1"
                                                        style="min-height:80px; display:flex; align-items:center; justify-content:center;">
                                                        <img id="variant-main-preview" src=""
                                                            style="max-height:80px; max-width:100%; display:none;">
                                                        <span id="variant-main-placeholder" class="text-muted"
                                                            style="font-size:12px;">No image uploaded</span>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold" style="font-size:13px;">Light-On
                                                        Image</label>
                                                    <input type="file" name="lighton_image"
                                                        class="form-control-file form-control-sm" accept="image/*"
                                                        onchange="previewPickerImage(this)">
                                                    <div class="mt-2 text-center bg-white border rounded p-1"
                                                        style="min-height:80px; display:flex; align-items:center; justify-content:center;">
                                                        <img id="variant-lighton-preview" src=""
                                                            style="max-height:80px; max-width:100%; display:none;">
                                                        <span id="variant-lighton-placeholder" class="text-muted"
                                                            style="font-size:12px;">No image uploaded</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-sm w-100"
                                                    id="btn-save-variant-images"><i class="ft-upload"></i> Upload &
                                                    Save</button>
                                            </form>
                                        </div>
                                        @if ($product->variants->count() == 0)
                                            <div class="alert alert-secondary text-center p-2 mb-0"
                                                style="font-size:13px;">
                                                No variants found. Add variants first.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- General Product Gallery -->
                            <div class="col-md-7">
                                <div class="card shadow-sm border-0">
                                    <div
                                        class="card-header bg-white d-flex justify-content-between align-items-center pb-2 border-bottom">
                                        <h5 class="card-title m-0"><i class="ft-layers text-primary"></i> Product
                                            Gallery</h5>
                                        <div>
                                            <form id="form-gallery-upload" style="display:inline;">
                                                @csrf
                                                <input type="file" id="gallery-file-input" name="file" multiple
                                                    accept="image/*" style="display:none;">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="document.getElementById('gallery-file-input').click();">
                                                    <i class="ft-plus"></i> Add Images
                                                </button>
                                            </form>
                                            <button class="btn btn-sm btn-secondary ml-1" id="btn-save-gallery"><i
                                                    class="ft-save"></i> Save Order/Captions</button>
                                        </div>
                                    </div>
                                    <div class="card-body pt-3 bg-light">
                                        <div class="row" id="gallery-grid">
                                            @foreach ($product->galleries as $gallery)
                                                <div class="col-md-4 col-sm-6 col-6 mb-3 gallery-item"
                                                    data-id="{{ $gallery->id }}">
                                                    <div class="card border mb-0 shadow-sm">
                                                        <div class="card-img-top handle"
                                                            style="height:150px; background:url('{{ asset('storage/uploads/decorative_gallery/' . $gallery->image) }}') center/cover; cursor:move; position:relative; border-bottom:1px solid #eee;">
                                                            <div style="position:absolute; top:5px; right:5px;">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger btn-delete-gallery p-1"
                                                                    data-id="{{ $gallery->id }}"
                                                                    style="line-height:1;"><i
                                                                        class="ft-trash-2"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-2 bg-light">
                                                            <input type="text"
                                                                class="form-control form-control-sm gallery-caption"
                                                                placeholder="Caption..."
                                                                value="{{ $gallery->caption }}"
                                                                data-id="{{ $gallery->id }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if ($product->galleries->count() == 0)
                                                <div class="col-12 text-center py-4 text-muted" id="no-gallery-msg">No
                                                    images in gallery yet.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            <i class="ft-info font-large-1 d-block mb-1"></i>
                            You must save the basic information first before managing images.
                        </div>
                    @endif
                </div>
