{{-- Places Section Tab Content --}}
{{-- This content should be added to edit.blade.php after the Tones Section tab --}}

{{-- ADD THIS TO THE NAV TABS (around line 188, after Tones tab): --}}
<li class="nav-item">
    <a class="nav-link" id="places-tab" data-toggle="tab" href="#places-section" role="tab" aria-controls="places-section" aria-selected="false">
        <i class="fas fa-map-marker-alt"></i> Places
        @if($collection->placesSection)
            <span class="badge badge-success ml-1">✓</span>
        @else
            <span class="badge badge-secondary ml-1">+</span>
        @endif
    </a>
</li>

{{-- ADD THIS TO THE TAB CONTENT (around line 710, after Tones Section content): --}}
<!-- Places Section Tab -->
<div class="tab-pane fade p-4" id="places-section" role="tabpanel" aria-labelledby="places-tab">
    
    <!-- Section Settings Form -->
    <form action="{{ route('admin.collections.store-places', $collection->slug) }}" method="POST" class="mb-4">
        @csrf
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog"></i> Places Section Configuration
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="places_title">Section Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="places_title" name="title" 
                                   value="{{ old('title', $collection->placesSection->title ?? 'Symphony in Place') }}" 
                                   placeholder="Symphony in Place" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>&nbsp;</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="places_is_active" 
                                   name="is_active" value="1" 
                                   {{ old('is_active', $collection->placesSection->is_active ?? false) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="places_is_active">Display on Frontend</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="places_subtitle">Section Description</label>
                    <textarea class="form-control" id="places_subtitle" name="subtitle" rows="2">{{ old('subtitle', $collection->placesSection->subtitle ?? 'One system, composed differently for every room') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Section Settings
                </button>
            </div>
        </div>
    </form>

    <!-- Place Items List -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-map-marked-alt"></i> Place Items
            </h5>
            <div class="card-tools">
                @if($collection->placesSection)
                    <a href="{{ route('admin.collections.place-items.add', $collection->slug) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Place Item
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($collection->placesSection && $collection->placesSection->items->count() > 0)
                <table class="table data-table table-bordered" data-order='[[ 0, "asc" ]]' id="places-tab-table" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 70px;" class="text-center">ORDER</th>
                            <th style="width: 90px;" class="text-center">IMAGE</th>
                            <th>PLACE NAME</th>
                            <th>DESCRIPTION</th>
                            <th style="width: 100px;" class="text-center">STATUS</th>
                            <th style="width: 110px; min-width: 110px;" class="text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($collection->placesSection->items->sortBy('order') as $item)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $item->order ?? 0 }}</td>
                                <td class="img-td text-center align-middle">
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->place_name }}" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                    @else
                                        <div style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border:1px solid #eee;border-radius:4px;margin:0 auto;color:#aaa;font-size:10px;">
                                            No Img
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle font-weight-bold text-dark">{{ $item->place_name }}</td>
                                <td class="align-middle text-muted">{{ Str::limit($item->description, 60) }}</td>
                                <td class="text-center align-middle">
                                    @if($item->is_active)
                                        <span class="badge badge-success" style="font-size: 11px;">Active</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-size: 11px;">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle list-action actBtn-td" style="white-space: nowrap;">
                                    <a href="{{ route('admin.collections.place-items.edit', [$collection->slug, $item->id]) }}" 
                                       class="mx-1 text-primary" data-toggle="tooltip" title="Edit">
                                        <i class="ft-edit-2 font-medium-3"></i>
                                    </a>
                                    <form action="{{ route('admin.collections.place-items.delete', [$collection->slug, $item->id]) }}" 
                                          method="POST" style="display: inline-block;" 
                                          onsubmit="return confirm('Delete this place item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 mx-1 text-danger" data-toggle="tooltip" title="Delete" style="border:none;background:none;">
                                            <i class="icon ft-trash-2 font-medium-3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center">Order</th>
                            <th class="text-center">Image</th>
                            <th>Place Name</th>
                            <th>Description</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No place items yet.</p>
                    @if($collection->placesSection)
                        <a href="{{ route('admin.collections.place-items.add', $collection->slug) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Your First Place
                        </a>
                    @else
                        <p class="text-muted"><small>Please save section settings first before adding places.</small></p>
                    @endif
                </div>
            @endif
        </div>
    </div>

</div>
