@extends('admin.page')
@section('title', 'Collections')
@php $main_module = 'Collections'; @endphp

@section('extra_css')
<style>
.collections-table th { white-space: nowrap; }
.collections-table th, .collections-table td { vertical-align: middle !important; }
.btn-status {
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 10px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    min-width: 75px;
    line-height: 1.5;
}
.btn-action-icon {
    width: 28px;
    height: 28px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    padding: 0;
}
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Collections</h1></div>
            <div class="col-sm-6">
                <a href="{{ route('admin.collections.create') }}" class="btn btn-primary float-right">
                    <i class="fas fa-plus mr-1"></i> New Collection
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h3 class="card-title mb-0"><i class="fas fa-layer-group mr-2"></i>All Collections</h3>
                <div class="ml-auto">
                    <span class="badge badge-secondary px-2 py-1">{{ $collections->count() }} total</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm collections-table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:80px" class="text-center">Order</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th style="width:180px" class="text-center">Sections</th>
                                <th style="width:100px" class="text-center">Status</th>
                                <th style="width:110px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($collections as $collection)
                                @php
                                    $active_sections = 0;
                                    if ($collection->heroSection && ($collection->heroSection->is_active ?? true)) $active_sections++;
                                    if ($collection->parametersSection && ($collection->parametersSection->is_active ?? true)) $active_sections++;
                                    if ($collection->compositionsSection && ($collection->compositionsSection->is_active ?? true)) $active_sections++;
                                    if ($collection->tonesSection && ($collection->tonesSection->is_active ?? true)) $active_sections++;
                                    if ($collection->placesSection && ($collection->placesSection->is_active ?? true)) $active_sections++;
                                    if ($collection->spreadDropSection && ($collection->spreadDropSection->is_active ?? true)) $active_sections++;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <span class="badge badge-light border">{{ $collection->order }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $collection->name }}</strong>
                                    </td>
                                    <td>
                                        <code class="text-muted">/collections/{{ $collection->slug }}</code>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-pill badge-info px-2 py-1" style="font-size: 11px; font-weight: 500;">
                                            <i class="fas fa-cubes mr-1"></i> {{ $active_sections }} / 6 Active
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.collections.toggle-active', $collection->slug) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-status {{ $collection->is_active ? 'btn-success' : 'btn-outline-secondary' }}">
                                                {{ $collection->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center" style="gap: 6px;">
                                            <a href="{{ route('admin.collections.edit', $collection->slug) }}"
                                               class="btn btn-info btn-action-icon" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.collections.destroy', $collection->slug) }}" method="POST"
                                                  onsubmit="return confirm('Delete this collection?')" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-action-icon" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        No collections found.
                                        <a href="{{ route('admin.collections.create') }}">Create one now</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection