@extends('admin.page')

@section('title', $title)

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3 d-flex justify-content-between align-items-center">
            <h4>{{ $title }}</h4>
            <a href="{{ route('decorative_category_admin.add') }}" class="btn btn-premium">Add Category</a>
        </div>
        @if(session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="premium-card">
            <div class="premium-card-body">
                <div class="table-responsive">
                    <table class="table premium-table" id="categories-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Parent Category</th>
                                <th>Status</th>
                                <th>Mega Dropdown</th>
                                <th>Sort Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset('uploads/decorative_categories/' . $category->image) }}" alt="{{ $category->name }}" style="max-height: 50px;">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->parent ? $category->parent->name : '-' }}</td>
                                <td>
                                    @if($category->status == 'active')
                                        <span class="badge badge-premium-active">Active</span>
                                    @else
                                        <span class="badge badge-premium-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->show_in_mega_dropdown)
                                        <span class="badge badge-success">Show</span>
                                    @else
                                        <span class="badge badge-secondary">Hide</span>
                                    @endif
                                </td>
                                <td>{{ $category->sort_order }}</td>
                                <td>
                                    <a href="{{ route('decorative_category_admin.edit', $category->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    <a href="{{ route('decorative_category_admin.delete', $category->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('extra_js')
<script>
    $(document).ready(function() {
        $('#categories-table').DataTable();
    });
</script>
@stop
