@extends('admin.page')
@section('title', 'Edit Parameter Card — ' . $collection->name)
@php $main_module = 'Collections'; @endphp

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Edit Parameter Card</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.collections.index') }}">Collections</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.collections.edit', $collection->slug) }}?tab=parameters">{{ $collection->name }}</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Parameter</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.collections.parameter-items.update', [$collection->slug, $item->id]) }}" method="POST" id="update-form">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cog mr-1"></i> Parameter Card Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="small_text">Small Label Text</label>
                                <input type="text" class="form-control @error('small_text') is-invalid @enderror"
                                       id="small_text" name="small_text" value="{{ old('small_text', $item->small_text) }}"
                                       maxlength="100" placeholder="e.g., The form">
                                @error('small_text')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                <small class="form-text text-muted">Optional — appears above the card title</small>
                            </div>

                            <div class="form-group">
                                <label for="title">Card Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $item->title) }}" required>
                                @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="5" required>{{ old('description', $item->description) }}</textarea>
                                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bg_color">Background Color</label>
                                        <div class="d-flex align-items-center" style="gap:8px">
                                            <span id="bg_color_swatch" style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ old('bg_color', $item->bg_color ?? '#a84628') }};flex-shrink:0;"></span>
                                            <select class="form-control" id="bg_color" name="bg_color"
                                                    onchange="document.getElementById('bg_color_swatch').style.background=this.value||'#a84628'">
                                                <option value="">-- No Color --</option>
                                                @foreach($colorMasters as $color)
                                                    <option value="{{ $color->hex_code }}" {{ old('bg_color', $item->bg_color ?? '#a84628')==$color->hex_code?'selected':'' }}>
                                                        {{ ucfirst($color->name) }} ({{ $color->hex_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <small class="form-text text-muted">Default background</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hover_bg_color">Hover Background Color</label>
                                        <div class="d-flex align-items-center" style="gap:8px">
                                            <span id="hover_swatch" style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ old('hover_bg_color', $item->hover_bg_color ?? '#000000') }};flex-shrink:0;"></span>
                                            <select class="form-control" id="hover_bg_color" name="hover_bg_color"
                                                    onchange="document.getElementById('hover_swatch').style.background=this.value||'#000000'">
                                                <option value="">-- No Color --</option>
                                                @foreach($colorMasters as $color)
                                                    <option value="{{ $color->hex_code }}" {{ old('hover_bg_color', $item->hover_bg_color ?? '#000000')==$color->hex_code?'selected':'' }}>
                                                        {{ ucfirst($color->name) }} ({{ $color->hex_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <small class="form-text text-muted">Color on hover</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="order">Display Order <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                       id="order" name="order" value="{{ old('order', $item->order) }}"
                                       required min="0" style="max-width:120px">
                                @error('order')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                <small class="form-text text-muted">Lower numbers appear first</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold d-block">Visibility</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active (Visible on Frontend)</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Update Parameter Card
                            </button>
                            <a href="{{ route('admin.collections.edit', $collection->slug) }}?tab=parameters" class="btn btn-default ml-2">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection