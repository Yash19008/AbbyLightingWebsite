@extends('admin.page')
@section('title', $title)
@section('content_header')
<div class="row mb-2">
  <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-between align-items-center">
    <h1 class="m-0">{{ $title }}</h1>
    <a href="{{ route('decorative_attribute_admin.add') }}" class="btn btn-premium"><i class="ft-plus mr-1"></i>Add Attribute</a>
  </div>
</div>
@stop
@section('content')
<div class="row">
    <div class="col-12">
        <div class="premium-card">
            <div class="card-content collapse show">
                <div class="premium-card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table premium-table dynamic-table" id="attributes-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Values Count</th>
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
<script>
    $(document).ready(function () {
        $('#attributes-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('decorative_attribute_admin.list') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'slug', name: 'slug' },
                { data: 'values_count', name: 'values_count', searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@stop
