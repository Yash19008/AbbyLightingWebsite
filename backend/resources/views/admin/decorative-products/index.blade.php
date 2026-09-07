@extends('admin.page')

@section('title', $title)
@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex;">
            <div class="mr-4">
                <span class="d-flex align-items-center">
                    <h4>{{ $title }}</h4>
                </span>
            </div>
            <button class="btn btn-premium mr-2">
                <span class="d-flex align-items-center">
                    <i class="ft-plus mr-1"></i>
                    <a href="{{ route('decorative_product_admin.add') }}" class="buttons text-white" style="text-decoration:none;"><span>Add Product</span></a>
                </span>
            </button>
        </div>
    </div>
</div>
@stop
@section('content')
<div class="row">
    <div class="col-12">
        <div class="premium-card">
            <div class="card-content">
                <div class="premium-card-body">
                    <div class="table-responsive">
                        <table class="table premium-table data-table w-100" id="decorative_products">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>SKU</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('extra_js')
<script type="text/javascript">
    $(document).ready(function() {
        var columns = [
            { data: 'id', name: 'id', orderable: true, searchable: true, class: 'text-left align-middle' },
            { data: 'title', name: 'title', orderable: true, searchable: true, class: 'text-left align-middle' },
            { data: 'sku', name: 'sku', orderable: true, searchable: true, class: 'text-left align-middle' },
            { data: 'status', name: 'status', class: 'text-center align-middle', orderable: false, searchable: false },
            { data: 'action', name: 'action', class: 'text-center align-middle', orderable: false, searchable: false }
        ];

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('decorative_product_admin.list') }}",
            columns: columns,
            searching: true,
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm("Are you sure you want to delete this product?")) {
                // Add ajax call to delete or form submit
                alert("Delete functionality to be implemented.");
            }
        });
    });
</script>
@stop
