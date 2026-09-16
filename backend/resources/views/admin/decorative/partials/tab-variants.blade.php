                <div id="pane-variants" class="wizard-pane">
                    @if(isset($product))
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Product Variants</h4>
                                    <button type="button" class="btn btn-sm btn-primary" id="btn-add-variant"><i class="ft-plus"></i> Add Variant</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="variants-table">
                                            <thead>
                                                <tr>
                                                    <th width="40"><i class="ft-move"></i></th>
                                                    <th>Name &amp; SKU</th>
                                                    <th>Color</th>
                                                    <th>Size</th>
                                                    <th>Status</th>
                                                    <th width="100">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="variants-list">
                                                @foreach($product->variants as $variant)
                                                <tr data-id="{{ $variant->id }}">
                                                    <td class="handle" style="cursor: move;"><i class="ft-menu text-muted"></i></td>
                                                    <td><strong>{{ $variant->name }}</strong><br><small class="text-muted">SKU: {{ $variant->sku ?? 'N/A' }}</small></td>
                                                    <td>
                                                        @if($variant->colorMaster)
                                                            <span class="color-indicator" style="display:inline-block;width:15px;height:15px;border-radius:50%;background-color:{{ $variant->colorMaster->hex_code ?? '#ccc' }};border:1px solid #ddd;vertical-align:middle;margin-right:5px;"></span>
                                                            {{ $variant->colorMaster->name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $variant->size ?? '-' }}</td>
                                                    <td><span class="badge badge-{{ $variant->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($variant->status) }}</span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-variant" data-id="{{ $variant->id }}"><i class="ft-edit"></i></button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-variant" data-id="{{ $variant->id }}"><i class="ft-trash-2"></i></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @if($product->variants->count() == 0)
                                                <tr id="no-variants-row">
                                                    <td colspan="6" class="text-center py-4 text-muted">No variants added yet.</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card variant-form-card">
                                <div class="card-header pb-2 pt-2">
                                    <h4 class="card-title m-0" id="variant-form-title" style="font-size:16px;"><i class="ft-plus-circle text-primary"></i> Add Variant</h4>
                                </div>
                                <div class="card-body pt-3">
                                    <form id="form-variant">
                                        <input type="hidden" id="variant_id" name="variant_id" value="">
                                        <div class="form-group">
                                            <label class="font-weight-bold" style="font-size:13px;">Variant Name <span class="text-danger">*</span></label>
                                            <input type="text" id="variant_name" name="name" class="form-control form-control-sm" required placeholder="e.g. Polished Brass">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="font-weight-bold" style="font-size:13px;">SKU</label>
                                                <input type="text" id="variant_sku" name="sku" class="form-control form-control-sm" placeholder="Optional">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="font-weight-bold" style="font-size:13px;">Size</label>
                                                <input type="text" id="variant_size" name="size" class="form-control form-control-sm" placeholder="e.g. 10x10">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold" style="font-size:13px;">Color Profile</label>
                                            <select id="variant_color_master_id" name="color_master_id" class="form-control select2">
                                                <option value="">-- No Color --</option>
                                                @if(isset($color_masters))
                                                    @foreach($color_masters as $color)
                                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold" style="font-size:13px;">Status</label>
                                            <select id="variant_status" name="status" class="form-control select2">
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <hr class="mt-2 mb-2">
                                        <button type="button" class="btn btn-primary w-100" id="btn-save-variant"><i class="ft-check"></i> Save Variant</button>
                                        <button type="button" class="btn btn-light w-100 mt-1 text-muted" id="btn-cancel-variant" style="display:none;">Cancel Edit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning text-center">
                        <i class="ft-info font-large-1 d-block mb-1"></i>
                        You must save the basic information first before managing variants.
                    </div>
                    @endif
                </div>
