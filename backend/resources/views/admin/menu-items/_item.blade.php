<li class="sortable-item" data-id="{{ $item->id }}">
    <div class="item-content w-100">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="item-details">
                <span class="item-title">
                    <span class="status-indicator {{ $item->is_active ? 'status-active' : 'status-inactive' }}" title="{{ $item->is_active ? 'Active' : 'Inactive' }}"></span>
                    @if($item->type === 'group') <i class="ft-folder text-warning mr-1"></i> @else <i class="ft-link text-info mr-1"></i> @endif
                    {{ $item->title }}
                </span>
                @if($item->type === 'link')
                    <span class="item-url">{{ $item->url }}</span>
                @endif
            </div>
            <div class="item-actions">
                @if($item->type === 'group')
                    <button type="button" class="btn btn-sm btn-outline-success" 
                            onclick="setParent('{{ $item->location }}', {{ $item->id }})">
                        <i class="ft-plus"></i> Child
                    </button>
                @endif
                <button type="button" class="btn btn-sm btn-outline-primary" 
                        onclick="editItem('{{ $item->location }}', {{ $item->id }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->url ?? '') }}', {{ $item->is_active ? 1 : 0 }}, '{{ $item->type }}', '{{ $item->parent_id ?? '' }}')">
                    <i class="ft-edit"></i>
                </button>
                <form action="{{ route('admin.menu-items.destroy', $item->id) }}" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this menu item?');">
                        <i class="ft-trash-2"></i>
                    </button>
                </form>
            </div>
        </div>

        @if($item->type === 'group')
            <ul class="sortable-list pl-4 mt-2 border-left-dashed" data-location="{{ $item->location }}" style="min-height: 20px; border-left: 1px dashed #ccc;">
                @foreach($item->children as $child)
                    @include('admin.menu-items._item', ['item' => $child])
                @endforeach
            </ul>
        @endif
    </div>
</li>
