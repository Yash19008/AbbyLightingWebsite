                <div id="pane-downloads" class="wizard-pane">
                    @if(isset($product))
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white pb-0 border-bottom">
                                    <h5 class="card-title m-0 pb-2"><i class="ft-file-text text-primary"></i> Installation Guide</h5>
                                </div>
                                <div class="card-body pt-3">
                                    <p class="text-muted" style="font-size:13px;">Upload the PDF or DOC installation guide for this product.</p>
                                    
                                    <div id="installation-guide-display" style="{{ $product->installation_guide ? '' : 'display:none;' }}">
                                        <div class="d-flex align-items-center p-2 mb-3" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px;">
                                            <i class="ft-file-text text-danger font-large-1 mr-2"></i>
                                            <div style="flex:1; overflow:hidden;">
                                                <div style="font-size:13px; font-weight:600; text-overflow:ellipsis; white-space:nowrap; overflow:hidden;">
                                                    <a href="{{ $product->installation_guide ? asset('storage/uploads/decorative/downloads/'.$product->installation_guide) : '#' }}" target="_blank" id="installation-guide-link">
                                                        <span id="installation-guide-filename">{{ $product->installation_guide }}</span>
                                                    </a>
                                                </div>
                                                <div style="font-size:11px; color:#6b7280;">Current File</div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger p-1 ml-2 btn-remove-download" data-type="installation_guide" style="border:none;"><i class="ft-trash-2"></i></button>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-replace-download" data-type="installation_guide" style="font-size:12px; font-weight:600;"><i class="ft-upload"></i> Replace File</button>
                                    </div>

                                    <div id="installation-guide-upload" style="{{ $product->installation_guide ? 'display:none;' : '' }}">
                                        <form class="form-download-upload" data-type="installation_guide" enctype="multipart/form-data">
                                            <div class="form-group mb-2">
                                                <input type="file" name="file" class="form-control-file" accept=".pdf,.doc,.docx" required>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary" style="font-size:12px; font-weight:600;"><i class="ft-upload"></i> Upload Guide</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white pb-0 border-bottom">
                                    <h5 class="card-title m-0 pb-2"><i class="ft-shield text-primary"></i> Care Instructions</h5>
                                </div>
                                <div class="card-body pt-3">
                                    <p class="text-muted" style="font-size:13px;">Upload the PDF or DOC care instructions for this product.</p>
                                    
                                    <div id="care-instructions-display" style="{{ $product->care_instructions ? '' : 'display:none;' }}">
                                        <div class="d-flex align-items-center p-2 mb-3" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px;">
                                            <i class="ft-file-text text-danger font-large-1 mr-2"></i>
                                            <div style="flex:1; overflow:hidden;">
                                                <div style="font-size:13px; font-weight:600; text-overflow:ellipsis; white-space:nowrap; overflow:hidden;">
                                                    <a href="{{ $product->care_instructions ? asset('storage/uploads/decorative/downloads/'.$product->care_instructions) : '#' }}" target="_blank" id="care-instructions-link">
                                                        <span id="care-instructions-filename">{{ $product->care_instructions }}</span>
                                                    </a>
                                                </div>
                                                <div style="font-size:11px; color:#6b7280;">Current File</div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger p-1 ml-2 btn-remove-download" data-type="care_instructions" style="border:none;"><i class="ft-trash-2"></i></button>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-replace-download" data-type="care_instructions" style="font-size:12px; font-weight:600;"><i class="ft-upload"></i> Replace File</button>
                                    </div>

                                    <div id="care-instructions-upload" style="{{ $product->care_instructions ? 'display:none;' : '' }}">
                                        <form class="form-download-upload" data-type="care_instructions" enctype="multipart/form-data">
                                            <div class="form-group mb-2">
                                                <input type="file" name="file" class="form-control-file" accept=".pdf,.doc,.docx" required>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary" style="font-size:12px; font-weight:600;"><i class="ft-upload"></i> Upload Instructions</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning text-center">
                        <i class="ft-info font-large-1 d-block mb-1"></i>
                        You must save the basic information first before managing downloads.
                    </div>
                    @endif
                </div>
