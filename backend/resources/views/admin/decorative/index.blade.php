@extends('admin.page')
@section('title', 'Decorative Products')
@php $main_module = 'Decorative Product'; @endphp
@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Decorative Products</h3>
        </div>
        <div class="content-header-right text-md-right col-md-6 col-12">
            <a href="{{ route('decorative_product_admin.add') }}" class="btn btn-primary">
                <i class="ft-plus"></i> Add New Product
            </a>
        </div>
    </div>
    
    <div class="content-body">
        <section id="configuration">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0 border-bottom mb-3">
                            <h4 class="card-title">All Decorative Products</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard pt-0">
                                <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded border">
                                    <div>
                                        <h6 class="m-0 text-muted"><i class="ft-filter"></i> Filter & Search</h6>
                                    </div>
                                    <form action="{{ route('decorative_product_admin') }}" method="GET" class="form-inline m-0">
                                        <div class="form-group mr-2">
                                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <option value="">All Statuses</option>
                                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search product..." style="width:250px;">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="ft-search"></i> Search</button>
                                        @if(request('search') || request('status'))
                                        <a href="{{ route('decorative_product_admin') }}" class="btn btn-sm btn-secondary ml-1">Clear</a>
                                        @endif
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="80">Image</th>
                                                <th>Name</th>
                                                <th>Collection</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th width="150">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($products as $product)
                                            <tr>
                                                <td>
                                                    @if($product->featured_image)
                                                        <img src="{{ asset('storage/uploads/decorative/' . $product->featured_image) }}" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                                    @else
                                                        <div style="width:50px;height:50px;background:#f4f5f7;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#b4b4b4;">
                                                            <i class="ft-image"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $product->name }}</strong><br>
                                                    <small class="text-muted">{{ $product->slug }}</small>
                                                </td>
                                                <td>{{ $product->collection ? $product->collection->name : '-' }}</td>
                                                <td>{{ $product->category ? $product->category->name : '-' }}</td>
                                                <td>
                                                    @if($product->status == 'published')
                                                        <span class="badge badge-success">Published</span>
                                                    @elseif($product->status == 'archived')
                                                        <span class="badge badge-secondary">Archived</span>
                                                    @else
                                                        <span class="badge badge-warning">Draft</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('decorative_product_admin.edit', $product->id) }}" class="btn btn-sm btn-outline-primary mb-1" title="Edit">
                                                        <i class="ft-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-info btn-duplicate-product mb-1" data-id="{{ $product->id }}" title="Duplicate">
                                                        <i class="ft-copy"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-product mb-1" data-id="{{ $product->id }}" title="Delete">
                                                        <i class="ft-trash-2"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <i class="ft-inbox font-large-1 text-muted mb-1 d-block"></i>
                                                    <p class="text-muted">No decorative products found.</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-2 d-flex justify-content-end">
                                    {{ $products->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.btn-delete-product').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var tr = $(this).closest('tr');
        
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: '{{ url("admin/decorative-products") }}/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(res.message);
                        } else {
                            alert(res.message);
                        }
                        tr.fadeOut(function() { $(this).remove(); });
                    } else {
                        if (typeof toastr !== 'undefined') toastr.error(res.message);
                    }
                },
                error: function() {
                    if (typeof toastr !== 'undefined') toastr.error('An error occurred.');
                }
            });
        }
    });

    $('.btn-duplicate-product').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        if (confirm('Are you sure you want to duplicate this product? This will clone the product, variants, and specifications.')) {
            $.ajax({
                url: '{{ url("admin/decorative-products") }}/' + id + '/duplicate',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(res.message);
                        }
                        window.location.href = res.redirect;
                    } else {
                        if (typeof toastr !== 'undefined') toastr.error(res.message);
                    }
                },
                error: function() {
                    if (typeof toastr !== 'undefined') toastr.error('An error occurred.');
                }
            });
        }
    });
});
</script>
@endsection
