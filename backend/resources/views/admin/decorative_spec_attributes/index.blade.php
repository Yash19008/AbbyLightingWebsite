@extends('admin.page')
@section('title', 'Spec Attributes Master')
@php $main_module = 'Decorative Product'; @endphp
@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Spec Attributes Master</h3>
        </div>
        <div class="content-header-right text-md-right col-md-6 col-12">
            <a href="{{ route('decorative_spec_attributes_admin.add') }}" class="btn btn-primary">
                <i class="ft-plus"></i> Add New Attribute
            </a>
        </div>
    </div>
    
    <div class="content-body">
        <section id="configuration">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0 border-bottom mb-3">
                            <h4 class="card-title">All Attributes</h4>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard pt-0">
                                <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded border">
                                    <div>
                                        <h6 class="m-0 text-muted"><i class="ft-filter"></i> Search Attributes</h6>
                                    </div>
                                    <form action="{{ route('decorative_spec_attributes_admin') }}" method="GET" class="form-inline m-0">
                                        <div class="form-group mr-2">
                                            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search attribute..." style="width:250px;">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="ft-search"></i> Search</button>
                                        @if(request('search'))
                                        <a href="{{ route('decorative_spec_attributes_admin') }}" class="btn btn-sm btn-secondary ml-1">Clear</a>
                                        @endif
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th width="150">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($attributes as $attribute)
                                            <tr>
                                                <td>{{ $attribute->id }}</td>
                                                <td><strong>{{ $attribute->name }}</strong></td>
                                                <td>
                                                    <a href="{{ route('decorative_spec_attributes_admin.edit', $attribute->id) }}" class="btn btn-sm btn-outline-primary mb-1" title="Edit">
                                                        <i class="ft-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-attribute mb-1" data-id="{{ $attribute->id }}" title="Delete">
                                                        <i class="ft-trash-2"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-4">
                                                    <i class="ft-inbox font-large-1 text-muted mb-1 d-block"></i>
                                                    <p class="text-muted">No spec attributes found.</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-2 d-flex justify-content-end">
                                    {{ $attributes->appends(request()->query())->links() }}
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
    $('.btn-delete-attribute').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var tr = $(this).closest('tr');
        
        if (confirm('Are you sure you want to delete this attribute?')) {
            $.ajax({
                url: "{{ url('admin/decorative-spec-attributes') }}/" + id,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        tr.fadeOut(400, function() { $(this).remove(); });
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        }
    });
});
</script>
@endsection
