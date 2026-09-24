                <div id="pane-specifications" class="wizard-pane">
                    @if(isset($product))

                    {{-- Info banner --}}
                    <div class="alert" style="background:#eef2ff; border:1px solid #c7d2fe; color:#3730a3; border-radius:8px; font-size:13px; padding:10px 14px; margin-bottom:16px;">
                        <i class="ft-info"></i> Basic specifications apply to the product globally. Dimensions can be grouped by size.
                    </div>

                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="m-0" style="font-size:15px; font-weight:700;">Product Specifications</h6>
                            <small class="text-muted">Manage all specification details.</small>
                        </div>
                        <div class="d-flex" style="gap:8px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-open-copy-spec" style="font-size:12px; font-weight:600; padding:6px 12px; border-radius:6px;">
                                <i class="ft-copy"></i> Copy from...
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" style="font-size:12px; font-weight:600; padding:6px 12px; border-radius:6px;">
                                    <i class="ft-layers"></i> Templates
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="font-size:13px;">
                                    <a class="dropdown-item" href="javascript:void(0)" id="btn-open-save-template"><i class="ft-save text-muted mr-1"></i> Save as Template</a>
                                    <a class="dropdown-item" href="javascript:void(0)" id="btn-open-apply-template"><i class="ft-download text-muted mr-1"></i> Apply Template</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Basic Specifications Section --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center" style="padding:12px 16px;">
                            <div class="d-flex align-items-center" style="gap:8px;">
                                <i class="ft-list text-muted"></i>
                                <h6 class="m-0" style="font-size:14px; font-weight:600;">Basic Specifications</h6>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-add-spec" data-section="basic_specifications" data-size-id="" style="font-size:12px; padding:4px 10px;">
                                <i class="ft-plus"></i> Add Row
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0 spec-table" style="font-size:13px;">
                                <thead style="background:#f9fafb; border-bottom:1px solid #f3f4f6;">
                                    <tr>
                                        <th style="width:28px; padding:8px;"></th>
                                        <th style="padding:8px 12px; font-weight:600; color:#374151;">Label</th>
                                        <th style="padding:8px 12px; font-weight:600; color:#374151;">Value</th>
                                        <th style="width:70px; padding:8px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="spec-tbody" data-section="basic_specifications" data-size-id="" id="spec-tbody-basic">
                                </tbody>
                            </table>
                            <div class="p-4 text-center text-muted no-specs-msg" id="no-specs-basic" style="display:none; font-size:13px;">
                                <i class="ft-list font-large-1 d-block mb-1"></i>No rows yet. Click "Add Row" to start.
                            </div>
                        </div>
                    </div>

                    {{-- Dimensions Section Header --}}
                    <h5 class="mb-3" style="font-size:16px; font-weight:700;">Dimensions <small class="text-muted d-block" style="font-size:12px; font-weight:normal;">Grouped by sizes configured in Colors & Sizes tab.</small></h5>

                    <div id="dimensions-container">
                        <div class="card shadow-sm border-0 mb-3 dimension-card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center" style="padding:12px 16px;">
                                <div class="d-flex align-items-center" style="gap:8px;">
                                    <i class="ft-maximize text-muted"></i>
                                    <h6 class="m-0" style="font-size:14px; font-weight:600;">Dimensions</h6>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-add-spec" data-section="dimensions" style="font-size:12px; padding:4px 10px;">
                                    <i class="ft-plus"></i> Add Row
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0 spec-table" style="font-size:13px;">
                                        <thead style="background:#f9fafb; border-bottom:1px solid #f3f4f6;">
                                            <tr>
                                                <th style="width:28px; padding:8px;"></th>
                                                <th style="padding:8px 12px; font-weight:600; color:#374151;">Attribute</th>
                                                @if($product->sizes && $product->sizes->count() > 0)
                                                    @foreach($product->sizes as $size)
                                                    <th style="padding:8px 12px; font-weight:600; color:#374151;">{{ $size->label }}</th>
                                                    @endforeach
                                                @else
                                                    <th style="padding:8px 12px; font-weight:600; color:#374151;">Value</th>
                                                @endif
                                                <th style="width:70px; padding:8px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="spec-tbody dimension-tbody" data-section="dimensions" id="spec-tbody-dimensions">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-4 text-center text-muted no-specs-msg" id="no-specs-dimensions" style="display:none; font-size:13px;">
                                    <i class="ft-maximize font-large-1 d-block mb-1"></i>No dimension rows yet. Click "Add Row" to start.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @else
                    <div class="alert alert-warning text-center">
                        <i class="ft-info font-large-1 d-block mb-1"></i>
                        You must save the basic information first before managing specifications.
                    </div>
                    @endif
                </div>
