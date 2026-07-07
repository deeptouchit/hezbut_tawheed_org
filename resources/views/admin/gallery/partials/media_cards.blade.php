@foreach($libraryImages as $item)
    <div class="media-card" id="card-{{ $item->id }}">
        <div class="media-img-wrapper">
            <!-- Badges -->
            @if($item->is_custom)
                <span class="badge bg-purple media-badge"><i class="fas fa-cloud me-1"></i> কাস্টম</span>
            @else
                <span class="badge bg-primary media-badge"><i class="fas fa-blog me-1"></i> ব্লগ ইমেজ</span>
            @endif

            <!-- Active indicator -->
            @if($item->is_active)
                <div class="active-indicator" title="গ্যালারিতে সক্রিয় আছে">
                    <i class="fas fa-check"></i>
                </div>
            @endif

            <img src="{{ asset($item->image_path) }}" alt="{{ $item->title }}" class="media-img" loading="lazy">
        </div>
        <div class="media-info">
            <div class="media-title" title="{{ $item->title ?? 'শিরোনামহীন ছবি' }}">
                {{ $item->title ?? 'শিরোনামহীন ছবি' }}
            </div>
            <div class="media-actions">
                <!-- Add/Remove Toggle -->
                <button class="btn btn-xs btn-sm flex-grow-1 toggle-active {{ $item->is_active ? 'btn-danger' : 'btn-success' }}" data-id="{{ $item->id }}">
                    @if($item->is_active)
                        <i class="fas fa-times-circle me-1"></i> বাদ দিন
                    @else
                        <i class="fas fa-check-circle me-1"></i> গ্যালারিতে দিন
                    @endif
                </button>

                <!-- Delete Custom Uploads -->
                @if($item->is_custom)
                    <button class="btn btn-xs btn-sm btn-outline-danger delete-custom" data-id="{{ $item->id }}" title="লাইব্রেরি থেকে মুছুন">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
@endforeach
