@extends('admin.page')

@section('title', 'Color Masters')

@php
    $main_module = 'Color Masters';
@endphp

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Color Masters</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('contact_form_admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Color Masters</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Colors</h3>
                <div class="card-tools">
                    <a href="{{ route('color_master_admin.add') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Color
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($colors->count() > 0)
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Order</th>
                                <th style="width: 100px;">Preview</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>CSS Value</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($colors as $color)
                                <tr>
                                    <td>{{ $color->order }}</td>
                                    <td>
                                        <div style="
                                            width: 60px; 
                                            height: 40px; 
                                            background: {{ $color->css_value }}; 
                                            border: 1px solid #ddd; 
                                            border-radius: 4px;
                                        "></div>
                                    </td>
                                    <td><strong>{{ $color->name }}</strong></td>
                                    <td><code>{{ $color->code }}</code></td>
                                    <td>
                                        @if($color->type === 'solid')
                                            <span class="badge badge-info">Solid</span>
                                        @else
                                            <span class="badge badge-primary">Gradient</span>
                                        @endif
                                    </td>
                                    <td>{{ $color->category ?? '-' }}</td>
                                    <td>
                                        <small class="text-muted">
                                            @if($color->type === 'solid')
                                                {{ $color->hex_code }}
                                            @else
                                                {{ $color->gradient_start }} → {{ $color->gradient_end }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($color->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('color_master_admin.information', $color->id) }}" 
                                           class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('color_master_admin.edit', $color->id) }}" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('color_master_admin.delete', $color->id) }}" 
                                              method="POST" style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this color?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
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
                        <i class="fas fa-palette fa-4x text-muted mb-3"></i>
                        <p class="text-muted">No colors created yet.</p>
                        <a href="{{ route('color_master_admin.add') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Your First Color
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
