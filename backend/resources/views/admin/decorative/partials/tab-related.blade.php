                <div id="pane-related" class="wizard-pane">
                    @if(isset($product))
                    <div class="row">
                        <!-- Manual Related -->
                        <div class="col-md-12 mb-3">
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
