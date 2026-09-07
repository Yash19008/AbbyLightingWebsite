@extends('admin.page')

@section('title', 'Worlds of Light')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content-header">Worlds of Light</div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('light_worlds_admin.add') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add World
            </a>
        </div>

        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Link</th>
                                    <th>Light Off Image</th>
                                    <th>Light On Image</th>
                                    <th>Sort Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $world)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $world->name }}</td>
                                        <td>{{ $world->link ?? '-' }}</td>
                                        <td>
                                            @if($world->light_of_image)
                                                @php
                                                    $offUrl = (str_starts_with($world->light_of_image, 'http') || str_starts_with($world->light_of_image, '/images') || str_starts_with($world->light_of_image, 'images/'))
                                                        ? $world->light_of_image
                                                        : asset('storage/' . $world->light_of_image);
                                                @endphp
                                                <img src="{{ $offUrl }}" alt="{{ $world->name }} off" style="width: 80px; height: 60px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($world->light_on_image)
                                                @php
                                                    $onUrl = (str_starts_with($world->light_on_image, 'http') || str_starts_with($world->light_on_image, '/images') || str_starts_with($world->light_on_image, 'images/'))
                                                        ? $world->light_on_image
                                                        : asset('storage/' . $world->light_on_image);
                                                @endphp
                                                <img src="{{ $onUrl }}" alt="{{ $world->name }} on" style="width: 80px; height: 60px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $world->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('light_worlds_admin.edit', $world->id) }}" class="btn btn-sm btn-info">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('light_worlds_admin.delete', $world->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this world?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No worlds found. <a href="{{ route('light_worlds_admin.add') }}">Add the first world</a></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
