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
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th style="width: 50px;">Order</th>
                            <th>Place Name</th>
                            <th>Description</th>
                            <th style="width: 200px;">Products</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($collection->placesSection->items->sortBy('order') as $item)
                            <tr>
                                <td>
                                    <img src="{{ $item->image_url }}" alt="{{ $item->place_name }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td>{{ $item->order }}</td>
                                <td><strong>{{ $item->place_name }}</strong></td>
                                <td>{{ Str::limit($item->description, 60) }}</td>
                                <td>{{ $item->products }}</td>
                                <td>
                                    @if($item->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.collections.place-items.edit', [$collection->slug, $item->id]) }}" 
                                       class="btn btn-sm btn-info" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.collections.place-items.delete', [$collection->slug, $item->id]) }}" 
                                          method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Delete this place item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
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
