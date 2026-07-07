@foreach($libraryImages as $item)
    <div class="media-card" id="card-{{ $item->id }}">
        <div class="media-img-wrapper">
            <!-- Type Badge -->
            @if($item->is_custom)
                <span class="badge bg-purple media-badge"><i class="fas fa-cloud me-1"></i> কাস্টম</span>
            @else
                <span class="badge bg-primary media-badge"><i class="fas fa-blog me-1"></i> ব্লগ</span>
            @endif

            <!-- Selection Indicators -->
            <div style="position: absolute; top: 10px; right: 10px; z-index: 2; display: flex; gap: 5px;">
                @if($item->show_on_homepage)
                    <span class="badge bg-success" title="হোমপেজে সক্রিয়" style="border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="fas fa-home" style="font-size: 10px;"></i>
                    </span>
                @endif
                @if($item->show_on_gallery)
                    <span class="badge bg-info" title="গ্যালারি পেজে সক্রিয়" style="border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; padding: 0; color: white;">
                        <i class="fas fa-images" style="font-size: 10px;"></i>
                    </span>
                @endif
            </div>

            <img src="{{ asset($item->image_path) }}" alt="{{ $item->title }}" class="media-img" loading="lazy">
        </div>
        <div class="media-info">
            <div class="media-title" title="{{ $item->title ?? 'শিরোনামহীন ছবি' }}">
                {{ $item->title ?? 'শিরোনামহীন ছবি' }}
            </div>
            <div class="media-actions d-flex flex-column gap-1">
                <div class="d-flex gap-1 w-100">
                    <!-- Homepage Toggle -->
                    <button class="btn btn-xs btn-sm flex-grow-1 toggle-homepage {{ $item->show_on_homepage ? 'btn-success' : 'btn-outline-success' }}" data-id="{{ $item->id }}" title="হোমপেজ গ্যালারিতে দেখান/বাদ দিন">
                        <i class="fas fa-home me-1"></i> হোমপেজ
                    </button>
                    
                    <!-- Gallery Page Toggle -->
                    <button class="btn btn-xs btn-sm flex-grow-1 toggle-gallerypage {{ $item->show_on_gallery ? 'btn-info text-white' : 'btn-outline-info' }}" data-id="{{ $item->id }}" title="গ্যালারি পেজে দেখান/বাদ দিন">
                        <i class="fas fa-images me-1"></i> গ্যালারি পেজ
                    </button>
                </div>

                <!-- Delete Custom Uploads -->
                @if($item->is_custom)
                    <button class="btn btn-xs btn-sm btn-outline-danger w-100 delete-custom" data-id="{{ $item->id }}">
                        <i class="fas fa-trash-alt me-1"></i> লাইব্রেরি থেকে মুছুন
                    </button>
                @endif
            </div>
        </div>
    </div>
@endforeach
