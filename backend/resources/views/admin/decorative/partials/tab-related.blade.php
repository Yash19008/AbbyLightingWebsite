                <div id="pane-related" class="wizard-pane">
                    @if(isset($product))
                    <div class="row">
                        <!-- Left Column: Settings & Family -->
                        <div class="col-md-5 mb-3">
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-header bg-white pb-0 border-bottom">
                                    <h5 class="card-title m-0 pb-2"><i class="ft-settings text-primary"></i> Related Display Settings</h5>
                                </div>
                                <div class="card-body pt-3">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="toggle-family-section" {{ $product->show_family_section ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-bold" for="toggle-family-section" style="font-size:13px; cursor:pointer;">
                                            Show Collection Family Section
                                        </label>
                                    </div>
                                    <p class="text-muted mt-2 mb-0" style="font-size:12px;">If enabled, other products in the same collection will automatically display as related items on the product page.</p>
                                </div>
                            </div>
                            
                            @if($product->collection_id)
                            <div class="card shadow-sm border-0" style="{{ $product->show_family_section ? '' : 'opacity:0.6;' }}" id="family-section-card">
                                <div class="card-header bg-white pb-0 border-bottom">
                                    <h5 class="card-title m-0 pb-2"><i class="ft-users text-primary"></i> Collection Family</h5>
                                </div>
                                <div class="card-body pt-3 p-0">
                                    @php
                                        $familyProducts = \App\Models\Decorative\DecProduct::where('collection_id', $product->collection_id)
                                            ->where('id', '!=', $product->id)
                                            ->where('status', 'published')
                                            ->get();
                                    @endphp
                                    @if($familyProducts->count() > 0)
                                        <ul class="list-group list-group-flush" style="font-size:13px;">
                                            @foreach($familyProducts as $fp)
                                            <li class="list-group-item d-flex align-items-center" style="padding:10px 16px;">
                                                <div style="width:36px; height:36px; border-radius:6px; border:1px solid #eee; background:url('{{ $fp->featured_image ? asset('storage/uploads/decorative/'.$fp->featured_image) : '' }}') center/cover; margin-right:12px; flex-shrink:0;"></div>
                                                <div style="flex:1;">
                                                    <div style="font-weight:600; color:#374151;">{{ $fp->name }}</div>
                                                    <div style="font-size:11px; color:#6b7280;">Auto-linked</div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="p-3 text-center text-muted" style="font-size:13px;">No other published products in this collection.</div>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="alert alert-secondary p-3" style="font-size:13px;">
                                <i class="ft-info font-weight-bold"></i> This product is not assigned to a collection. Assign a collection in Basic Info to automatically link family products.
                            </div>
                            @endif
                        </div>

                        <!-- Right Column: Manual Related -->
                        <div class="col-md-7 mb-3">
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-header bg-white pb-0 border-bottom d-flex justify-content-between align-items-center">
                                    <h5 class="card-title m-0 pb-2"><i class="ft-link text-primary"></i> Manual Related Products</h5>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-related mb-2" data-type="manual" style="font-size:12px; font-weight:600;"><i class="ft-plus"></i> Add Product</button>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table mb-0 related-table" id="table-related-manual" style="font-size:13px;">
                                        <thead style="background:#f9fafb; border-bottom:1px solid #f3f4f6;">
                                            <tr>
                                                <th style="border:none; width:30px;"></th>
                                                <th style="border:none; font-weight:600; color:#6b7280;">Product</th>
                                                <th style="border:none; text-align:right;"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="related-tbody" data-type="manual">
                                            <!-- Injected via AJAX -->
                                        </tbody>
                                    </table>
                                    <div id="no-related-manual" class="p-4 text-center text-muted" style="display:none; font-size:13px;">
                                        No manually added related products.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning text-center">
                        <i class="ft-info font-large-1 d-block mb-1"></i>
                        You must save the basic information first before managing related products.
                    </div>
                    @endif
                </div>
