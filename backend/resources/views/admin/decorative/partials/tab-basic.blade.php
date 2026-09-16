                <div id="pane-basic" class="wizard-pane active">
                    <form id="form-basic" action="{{ isset($product) ? route('decorative_product_admin.update', $product->id) : route('decorative_product_admin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($product))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="font-weight-bold">Product Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" id="name" class="form-control" value="{{ isset($product) ? $product->name : '' }}" required>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="font-weight-bold">Slug <span class="text-danger">*</span></label>
                                                <input type="text" name="slug" id="slug" class="form-control" value="{{ isset($product) ? $product->slug : '' }}" required>
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label class="font-weight-bold">Collection</label>
                                                <select name="collection_id" class="form-control select2">
                                                    <option value="">-- Select Collection --</option>
                                                    @foreach($collections as $collection)
                                                        <option value="{{ $collection->id }}" {{ (isset($product) && $product->collection_id == $collection->id) ? 'selected' : '' }}>{{ $collection->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label class="font-weight-bold">Category</label>
                                                <select name="category_id" class="form-control select2">
                                                    <option value="">-- Select Category --</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label class="font-weight-bold">Short Description</label>
                                                <textarea name="short_description" rows="2" class="form-control">{{ isset($product) ? $product->short_description : '' }}</textarea>
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label class="font-weight-bold">Full Description</label>
                                                <textarea name="description" id="description" class="form-control">{{ isset($product) ? $product->description : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h4 class="card-title">Publishing</h4>
                                    </div>
                                    <div class="card-body pt-2">
                                        <div class="form-group mb-2">
                                            <label class="font-weight-bold">Status</label>
                                            <select name="status" class="form-control select2">
                                                <option value="draft" {{ (isset($product) && $product->status == 'draft') ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ (isset($product) && $product->status == 'published') ? 'selected' : '' }}>Published</option>
                                                <option value="archived" {{ (isset($product) && $product->status == 'archived') ? 'selected' : '' }}>Archived</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ (isset($product) && $product->is_featured) ? 'checked' : '' }}>
                                                <label class="custom-control-label font-weight-bold" for="is_featured">Show in Collection</label>
                                            </div>
                                            <small class="text-muted d-block mt-1">If enabled, this product will be highlighted inside its collection.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-2">
                                    <div class="card-header pb-0">
                                        <h4 class="card-title">Featured Image</h4>
                                    </div>
                                    <div class="card-body pt-2">
                                        <div class="image-picker-container" id="featured-image-container">
                                            <input type="file" name="featured_image" class="image-picker-input" accept="image/*" onchange="previewPickerImage(this)">
                                            <div class="picker-placeholder" style="{{ (isset($product) && $product->featured_image) ? 'display:none;' : 'display:block;' }}">
                                                <i class="ft-upload-cloud font-large-2 text-muted"></i>
                                                <p class="mt-1 mb-0 text-muted">Click or drag image to upload</p>
                                            </div>
                                            <div class="image-picker-preview" style="{{ (isset($product) && $product->featured_image) ? 'display:block;' : 'display:none;' }}">
                                                <img src="{{ (isset($product) && $product->featured_image) ? asset('storage/uploads/decorative/' . $product->featured_image) : '' }}">
                                                <div class="mt-2"><span class="badge badge-secondary" style="font-size:11px;padding:6px 10px;"><i class="ft-edit-2"></i> Change Image</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(isset($product))
                                <div class="card mt-2 bg-light">
                                    <div class="card-header pb-0 border-0">
                                        <h4 class="card-title text-muted" style="font-size:14px;"><i class="ft-info"></i> Meta Information</h4>
                                    </div>
                                    <div class="card-body pt-2 pb-2">
                                        <div style="font-size: 13px;">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Product ID:</span>
                                                <strong class="text-dark">#{{ $product->id }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Created:</span>
                                                <strong class="text-dark">{{ $product->created_at->format('d M Y, H:i') }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Updated:</span>
                                                <strong class="text-dark">{{ $product->updated_at->format('d M Y, H:i') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-2 border-0 shadow-none bg-transparent">
                                    <div class="d-flex" style="gap:10px;">
                                        <button type="button" class="btn btn-outline-secondary flex-fill" id="btn-duplicate-product"><i class="ft-copy"></i> Duplicate</button>
                                        <button type="button" class="btn btn-danger flex-fill" id="btn-delete-product"><i class="ft-trash-2"></i> Delete</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if(isset($product))
                        <!-- Variants Preview Area -->
                        <div class="card mt-2">
                            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Variants</h4>
                                <a href="#variants" class="btn btn-sm btn-outline-primary" onclick="$('#wizard-tabs a[href=\'#variants\']').trigger('click');">Manage Variants <i class="ft-arrow-right"></i></a>
                            </div>
                            <div class="card-body pt-2">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name / SKU</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($product->variants->count() > 0)
                                                @foreach($product->variants as $variant)
                                                <tr>
                                                    <td width="50">
                                                        @if($variant->main_image)
                                                            <img src="{{ asset('storage/uploads/decorative/' . $variant->main_image) }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                                                        @else
                                                            <div style="width:40px;height:40px;background:#f4f5f7;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#b4b4b4;"><i class="ft-image"></i></div>
                                                        @endif
                                                    </td>
                                                    <td><strong>{{ $variant->name }}</strong><br><small class="text-muted">{{ $variant->sku ?? 'No SKU' }}</small></td>
                                                    <td>{{ $variant->colorMaster ? $variant->colorMaster->name : '-' }}</td>
                                                    <td>{{ $variant->size ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $variant->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($variant->status) }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-3">No variants created yet.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
