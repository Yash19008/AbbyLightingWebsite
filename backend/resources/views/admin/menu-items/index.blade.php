@extends('admin.page')

@section('title', 'Menu Builder')

@section('content_header')
<div class="row">
    <div class="col-12">
        <div class="my-3" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h4>Menu Builder</h4>
                <p class="text-muted">Manage your website's navigation menus. Drag and drop to reorder.</p>
            </div>
            <button class="btn btn-primary" onclick="resetForm()">
                <i class="ft-plus mr-1"></i> Add New Link
            </button>
        </div>
    </div>
</div>
@stop

@section('extra_css')
<style>
    .sortable-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
        min-height: 50px;
    }
    .sortable-item {
        background: #fff;
        border: 1px solid #e0e0e0;
        margin-bottom: 8px;
        padding: 10px 15px;
        border-radius: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: grab;
        transition: box-shadow 0.2s;
    }
    .sortable-item:active {
        cursor: grabbing;
    }
    .sortable-item:hover {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .item-content { width: 100%; }
    .item-details {
        display: flex;
        flex-direction: column;
    }
    .item-title {
        font-weight: 500;
        font-size: 14px;
    }
    .item-url {
        font-size: 12px;
        color: #888;
    }
    .item-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-active { background-color: #28a745; }
    .status-inactive { background-color: #dc3545; }
    
    .menu-card {
        margin-bottom: 24px;
        border-top: 3px solid #007bff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .sticky-form {
        position: sticky;
        top: 20px;
    }
</style>
@stop

@section('content')
<div class="row">
    <!-- LEFT PANEL: Editor Form -->
    <div class="col-md-4">
        <div class="card menu-card sticky-form">
            <div class="card-header bg-light">
                <h4 class="card-title mb-0" id="formTitle">Add Menu Item</h4>
            </div>
            <div class="card-body">
                <form id="menuForm" method="POST" action="{{ route('admin.menu-items.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="form-group">
                        <label>Location <span class="text-danger">*</span></label>
                        <select class="form-control" name="location" id="location" required onchange="handleLocationChange()">
                            <option value="">-- Select Location First --</option>
                            @foreach($locations as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="dependentFields" style="display: none;">
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="form-group" id="typeGroup">
                            <label>Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="type" id="type" required onchange="toggleUrlField()">
                                <option value="link">Direct Link</option>
                                <option value="group" id="optGroup">Dropdown / Accordion Group</option>
                            </select>
                        </div>
                        <div class="form-group" id="urlGroup">
                            <label>URL <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="url" id="url" value="/">
                            <small class="text-muted">Use relative paths like /products or absolute URLs.</small>
                        </div>
                        <div class="form-group" id="parentGroup">
                            <label>Parent ID</label>
                            <input type="text" class="form-control" name="parent_id" id="parent_id" value="" readonly placeholder="Top Level">
                            <small class="text-muted">Click '+ Child' on a group to set this.</small>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>
                        <input type="hidden" name="order" id="order" value="0">
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save to Menu</button>
                            <button type="button" class="btn btn-secondary ml-2" onclick="resetForm()">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: Menu Structure -->
    <div class="col-md-8">
        
        <!-- Header Mega -->
        <div class="card menu-card border-top-primary">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="ft-layout"></i> Header Mega Menu</h4>
            </div>
            <div class="card-body bg-light-gray" style="background:#f9f9f9">
                <ul class="sortable-list" data-location="header_mega">
                    @foreach($groupedItems['header_mega'] ?? [] as $item)
                        @include('admin.menu-items._item', ['item' => $item])
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Footer Col 1 -->
        <div class="card menu-card border-top-info">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="ft-box"></i> Footer Column 1</h4>
            </div>
            <div class="card-body">
                <ul class="sortable-list" data-location="footer_col_1">
                    @foreach($groupedItems['footer_col_1'] ?? [] as $item)
                        @include('admin.menu-items._item', ['item' => $item])
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Footer Col 2 -->
        <div class="card menu-card border-top-warning">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="ft-briefcase"></i> Footer Column 2</h4>
            </div>
            <div class="card-body">
                <ul class="sortable-list" data-location="footer_col_2">
                    @foreach($groupedItems['footer_col_2'] ?? [] as $item)
                        @include('admin.menu-items._item', ['item' => $item])
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Footer Col 3 -->
        <div class="card menu-card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="ft-file-text"></i> Footer Column 3</h4>
            </div>
            <div class="card-body">
                <ul class="sortable-list" data-location="footer_col_3">
                    @foreach($groupedItems['footer_col_3'] ?? [] as $item)
                        @include('admin.menu-items._item', ['item' => $item])
                    @endforeach
                </ul>
            </div>
        </div>
        
    </div>
</div>
@stop

@section('extra_js')
<script>
    function toggleUrlField() {
        if ($('#type').val() === 'group') {
            $('#urlGroup').hide();
            $('#url').prop('required', false);
        } else {
            $('#urlGroup').show();
            $('#url').prop('required', true);
        }
    }

    $(document).ready(function() {
        // Initialize Sortable
        $('.sortable-list').sortable({
            connectWith: '.sortable-list',
            placeholder: 'sortable-placeholder',
            update: function(event, ui) {
                if (this === ui.item.parent()[0]) {
                    saveOrder($(this));
                }
            },
            receive: function(event, ui) {
                // Not allowed to drag between different lists (preventing complex logic for now)
            }
        }).disableSelection();

        $('.sortable-list').sortable("option", "connectWith", false);
        
        $('.sortable-list').sortable("option", "connectWith", false);
        
        // Initial toggle
        handleLocationChange();
    });

    function saveOrder($list) {
        var orders = [];
        var parentId = $list.closest('.sortable-item').data('id') || null;

        $list.children('li').each(function(index) {
            orders.push({
                id: $(this).data('id'),
                order: index + 1,
                parent_id: parentId
            });
        });

        $.ajax({
            url: '{{ route("admin.menu-items.reorder") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                orders: orders
            },
            success: function(response) {
                toastr.success('Order updated successfully');
            },
            error: function() {
                toastr.error('Error updating order');
            }
        });
    }

    function editItem(location, id, title, url, isActive, type, parentId) {
        $('#formTitle').text('Edit Menu Item');
        $('#saveBtn').text('Update Menu');
        $('#menuForm').attr('action', '{{ url("admin/menu-items") }}/' + id);
        $('#formMethod').val('PUT');
        
        $('#title').val(title);
        $('#url').val(url);
        $('#location').val(location);
        $('#type').val(type);
        $('#parent_id').val(parentId);
        $('#is_active').prop('checked', isActive == 1);
        
        handleLocationChange();
        
        // Scroll to form smoothly
        $('html, body').animate({
            scrollTop: $(".sticky-form").offset().top - 100
        }, 500);
    }
    
    function setParent(location, parentId) {
        resetForm();
        $('#formTitle').text('Add Child Link');
        $('#location').val(location);
        $('#parent_id').val(parentId);
        
        handleLocationChange();
        
        // Scroll to form smoothly
        $('html, body').animate({
            scrollTop: $(".sticky-form").offset().top - 100
        }, 500);
    }

    function resetForm() {
        $('#formTitle').text('Add Menu Item');
        $('#saveBtn').text('Save to Menu');
        $('#menuForm').attr('action', '{{ route("admin.menu-items.store") }}');
        $('#formMethod').val('POST');
        
        $('#title').val('');
        $('#url').val('/');
        $('#type').val('link');
        $('#parent_id').val('');
        $('#is_active').prop('checked', true);
        $('#location').val('');
        
        handleLocationChange();
    }

    function handleLocationChange() {
        var loc = $('#location').val();
        if (loc) {
            $('#dependentFields').slideDown();
        } else {
            $('#dependentFields').slideUp();
        }

        if (loc === 'header_mega') {
            $('#optGroup').prop('disabled', true);
            if ($('#type').val() === 'group') {
                $('#type').val('link');
            }
        } else {
            $('#optGroup').prop('disabled', false);
        }
        toggleUrlField();
    }
</script>
@stop
