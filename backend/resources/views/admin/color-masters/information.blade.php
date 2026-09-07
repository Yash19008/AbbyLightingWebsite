@extends('admin.page')

@section('title', 'Color Details')

@php
    $main_module = 'Color Masters';
@endphp

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Color Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('contact_form_admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('color_master_admin') }}">Color Masters</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $color->name }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('color_master_admin.edit', $color->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px;">Color Name</th>
                                <td><strong>{{ $color->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Color Code</th>
                                <td><code>{{ $color->code }}</code></td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>
                                    @if($color->type === 'solid')
                                        <span class="badge badge-info">Solid Color</span>
                                    @else
                                        <span class="badge badge-primary">Gradient</span>
                                    @endif
                                </td>
                            </tr>
                            @if($color->type === 'solid')
                                <tr>
                                    <th>Hex Code</th>
                                    <td><code>{{ $color->hex_code }}</code></td>
                                </tr>
                            @else
                                <tr>
                                    <th>Start Color</th>
                                    <td>
                                        <div style="display: inline-block; width: 30px; height: 20px; background: {{ $color->gradient_start }}; border: 1px solid #ddd; vertical-align: middle;"></div>
                                        <code>{{ $color->gradient_start }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <th>End Color</th>
                                    <td>
                                        <div style="display: inline-block; width: 30px; height: 20px; background: {{ $color->gradient_end }}; border: 1px solid #ddd; vertical-align: middle;"></div>
                                        <code>{{ $color->gradient_end }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Gradient Type</th>
                                    <td><span class="badge badge-secondary">{{ ucfirst($color->gradient_type) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Gradient Direction</th>
                                    <td>{{ $color->gradient_direction }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>CSS Value</th>
                                <td><code style="font-size: 11px;">{{ $color->css_value }}</code></td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $color->category ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $color->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Display Order</th>
                                <td>{{ $color->order }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($color->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $color->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $color->updated_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Color Preview</h3>
                    </div>
                    <div class="card-body">
                        <div style="
                            width: 100%; 
                            height: 300px; 
                            background: {{ $color->css_value }}; 
                            border: 2px solid #ddd; 
                            border-radius: 8px;
                            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                        "></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Usage Example (CSS)</h3>
                    </div>
                    <div class="card-body">
                        <pre style="background: #f4f4f4; padding: 10px; border-radius: 4px; font-size: 11px;"><code>.element {
  background: {{ $color->css_value }};
}</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="{{ route('color_master_admin') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('color_master_admin.edit', $color->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Color
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
