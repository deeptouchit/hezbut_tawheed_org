@if($leaders->count() > 0)
    <ul>
        @foreach($leaders as $leader)
            <li>
                <div class="org-node-card cursor-pointer drawer-trigger" data-id="{{ $leader->id }}">
                    <div class="overflow-hidden rounded-circle shadow-sm border border-2 border-white mx-auto mb-2" style="width: 50px; height: 50px; background-color: #f1f5f9;">
                        <img src="{{ $leader->image_url }}" alt="{{ $leader->name }}" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                    <div class="fw-bold text-dark text-truncate" style="font-size: 13px; line-height: 1.3; font-family: 'Baloo Da 2', sans-serif; max-width: 150px;">{{ $leader->name }}</div>
                    <div class="text-muted small text-truncate" style="font-size: 10px; font-family: 'Baloo Da 2', sans-serif; max-width: 150px;">{{ $leader->designation }}</div>
                </div>
                @if($leader->children->count() > 0)
                    @include('theme::pages.partials.org_chart_node', ['leaders' => $leader->children])
                @endif
            </li>
        @endforeach
    </ul>
@endif


