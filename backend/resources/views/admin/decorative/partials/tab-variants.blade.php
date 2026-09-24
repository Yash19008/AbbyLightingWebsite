                <div id="pane-variants" class="wizard-pane">
                    @if(isset($product))
                    <div class="row">
                        <!-- Colors Section -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Colors</h4>
                                    <button type="button" class="btn btn-sm btn-primary" id="btn-add-color"><i class="ft-plus"></i> Add Color</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="colors-table">
                                            <thead>
                                                <tr>
                                                    <th width="40"><i class="ft-move"></i></th>
                                                    <th>Color Profile</th>
                                                    <th width="100">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="colors-list">
                                                @foreach($product->colors as $color)
                                                <tr data-id="{{ $color->id }}">
                                                    <td class="handle" style="cursor: move;"><i class="ft-menu text-muted"></i></td>
                                                    <td>
                                                        @if($color->colorMaster)
                                                            <span class="color-indicator" style="display:inline-block;width:15px;height:15px;border-radius:50%;background:{{ $color->colorMaster->css_value ?? '#ccc' }};border:1px solid #ddd;vertical-align:middle;margin-right:5px;"></span>
                                                            {{ $color->colorMaster->name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-color" data-id="{{ $color->id }}" data-color-id="{{ $color->color_master_id }}"><i class="ft-edit"></i></button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-color" data-id="{{ $color->id }}"><i class="ft-trash-2"></i></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @if($product->colors->count() == 0)
                                                <tr id="no-colors-row">
                                                    <td colspan="3" class="text-center py-4 text-muted">No colors added yet.</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Color Form -->
                                    <div id="color-form-container" style="display:none; margin-top:20px; border-top:1px solid #eee; padding-top:15px;">
                                        <h5 id="color-form-title"><i class="ft-plus-circle text-primary"></i> Add Color</h5>
                                        <form id="form-color">
                                            <input type="hidden" id="color_id" name="color_id" value="">
                                            <div class="form-group">
                                                <label class="font-weight-bold" style="font-size:13px;">Select Color <span class="text-danger">*</span></label>
                                                <select id="color_color_master_id" name="color_master_id" class="form-control select2" required>
                                                    <option value="">-- Choose Color --</option>
                                                    @if(isset($color_masters))
                                                        @foreach($color_masters as $cm)
                                                            <option value="{{ $cm->id }}">{{ $cm->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="d-flex" style="gap:10px;">
                                                <button type="button" class="btn btn-primary btn-sm flex-grow-1" id="btn-save-color"><i class="ft-check"></i> Save Color</button>
                                                <button type="button" class="btn btn-light btn-sm text-muted" id="btn-cancel-color">Cancel</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Sizes Section -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Sizes</h4>
                                    <button type="button" class="btn btn-sm btn-primary" id="btn-add-size"><i class="ft-plus"></i> Add Size</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="sizes-table">
                                            <thead>
                                                <tr>
                                                    <th width="40"><i class="ft-move"></i></th>
                                                    <th>Label</th>
                                                    <th width="100">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="sizes-list">
                                                @foreach($product->sizes as $size)
                                                <tr data-id="{{ $size->id }}">
                                                    <td class="handle" style="cursor: move;"><i class="ft-menu text-muted"></i></td>
                                                    <td><strong>{{ $size->label }}</strong></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-size" data-id="{{ $size->id }}" data-label="{{ $size->label }}"><i class="ft-edit"></i></button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-size" data-id="{{ $size->id }}"><i class="ft-trash-2"></i></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @if($product->sizes->count() == 0)
                                                <tr id="no-sizes-row">
                                                    <td colspan="3" class="text-center py-4 text-muted">No sizes added yet.</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Size Form -->
                                    <div id="size-form-container" style="display:none; margin-top:20px; border-top:1px solid #eee; padding-top:15px;">
                                        <h5 id="size-form-title"><i class="ft-plus-circle text-primary"></i> Add Size</h5>
                                        <form id="form-size">
                                            <input type="hidden" id="size_id" name="size_id" value="">
                                            <div class="form-group">
                                                <label class="font-weight-bold" style="font-size:13px;">Size Label <span class="text-danger">*</span></label>
                                                <input type="text" id="size_label" name="label" class="form-control form-control-sm" required placeholder="e.g. Small / 10x10">
                                            </div>
                                            <div class="d-flex" style="gap:10px;">
                                                <button type="button" class="btn btn-primary btn-sm flex-grow-1" id="btn-save-size"><i class="ft-check"></i> Save Size</button>
                                                <button type="button" class="btn btn-light btn-sm text-muted" id="btn-cancel-size">Cancel</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning text-center">
                        <i class="ft-info font-large-1 d-block mb-1"></i>
                        You must save the basic information first before managing colors and sizes.
                    </div>
                    @endif
                </div>
