                <div id="pane-seo" class="wizard-pane">
                    @if(isset($product))
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white pb-0 border-bottom">
                                    <h5 class="card-title m-0 pb-2"><i class="ft-search text-primary"></i> Search Engine Optimization</h5>
                                </div>
                                <div class="card-body pt-3">
                                    <form id="form-seo">
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold d-flex justify-content-between" style="font-size:13px;">
                                                <span>Meta Title</span>
                                                <span class="text-muted" id="meta-title-counter" style="font-size:12px;">0 / 60</span>
                                            </label>
                                            <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ $product->meta_title }}" style="font-size:13px;">
                                            <small class="form-text text-muted" style="font-size:11px;">Keep it under 60 characters for best display in search results.</small>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold d-flex justify-content-between" style="font-size:13px;">
                                                <span>Meta Description</span>
                                                <span class="text-muted" id="meta-desc-counter" style="font-size:12px;">0 / 160</span>
                                            </label>
                                            <textarea name="meta_description" id="meta_description" class="form-control" rows="3" style="font-size:13px;">{{ $product->meta_description }}</textarea>
                                            <small class="form-text text-muted" style="font-size:11px;">Keep it under 160 characters. This appears below the title in search results.</small>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold" style="font-size:13px;">Meta Keywords</label>
                                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ $product->meta_keywords }}" placeholder="e.g. modern chandelier, brass lighting..." style="font-size:13px;">
                                            <small class="form-text text-muted" style="font-size:11px;">Comma separated keywords.</small>
                                        </div>

                                        <hr class="my-4">

                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold" style="font-size:13px;">Product Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control" style="font-size:13px;">
                                                <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ $product->status == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="archived" {{ $product->status == 'archived' ? 'selected' : '' }}>Archived</option>
                                            </select>
                                            <small class="form-text text-muted" style="font-size:11px;">Change the visibility of this product on the frontend.</small>
                                        </div>
                                        
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary" id="btn-save-seo"><i class="ft-check"></i> Save SEO & Settings</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-3"><i class="ft-monitor text-primary"></i> Search Preview</h6>
                                    <div style="background:#fff; padding:15px; border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                                        <div style="font-size:12px; color:#1a0dab; margin-bottom:2px;">www.abbylighting.com › decorative › ...</div>
                                        <div id="seo-preview-title" style="font-size:18px; color:#1a0dab; font-weight:400; line-height:1.2; margin-bottom:3px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">
                                            {{ $product->meta_title ?: $product->name }}
                                        </div>
                                        <div id="seo-preview-desc" style="font-size:13px; color:#4d5156; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                            {{ $product->meta_description ?: 'No description provided.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning text-center">
                        <i class="ft-info font-large-1 d-block mb-1"></i>
                        You must save the basic information first before managing SEO settings.
                    </div>
                    @endif
                </div>
