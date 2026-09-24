<ol class="dd-list">
    @foreach($leaders as $leader)
        <li class="dd-item" data-id="{{ $leader->id }}">
            <div class="dd-handle d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ $leader->image_url }}" alt="{{ $leader->name }}" class="rounded me-2" style="width: 32px; height: 32px; object-fit: cover;">
                    <div>
                        <span class="fw-bold text-dark">{{ $leader->name }}</span>
                        <span class="badge bg-secondary ms-2" style="font-size: 10px;">{{ $leader->designation }}</span>
                    </div>
                </div>
                <span class="text-muted"><i class="fas fa-arrows-alt"></i></span>
            </div>
            @if($leader->children->count() > 0)
                @include('admin.leaders.partials.hierarchy_node', ['leaders' => $leader->children])
            @endif
        </li>
    @endforeach
</ol>
