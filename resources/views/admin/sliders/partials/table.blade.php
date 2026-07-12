<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover align-middle mb-0 norwap">
        <thead>
            <tr class="text-center">
                <th >ID</th>
                <th>ছবি</th>
                <th>শিরোনাম</th>
                {{-- <th>লিংক ও বাটন তথ্য</th> --}}
                <th>পজিশন</th>
                <th>ক্রম</th>
                <th>মেয়াদ</th>
                <th>স্ট্যাটাস</th>
                <th>অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sliders as $slider)
            <tr>
                <td class="text-center align-middle p-0">{{ $slider->id }}</td>
                <td class="text-center align-middle p-0">
                    @if($slider->image && file_exists(public_path($slider->image)))
                        <img src="{{ asset($slider->image) }}" alt="{{ $slider->title }}"
                             style="width: 80px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    @else
                        <div class="bg-light border rounded d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 48px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                    @endif
                </td>
                <td class="align-middle">
                    <div class="font-weight-bold text-dark mb-0" style="font-size: 14px; font-family: 'Baloo Da 2', sans-serif;">{{ $slider->title ?? 'নামহীন' }}</div>
                    {{-- @if($slider->sub_title)
                        <div class="text-muted small mt-1" style="font-size: 12px; font-family: 'Baloo Da 2', sans-serif;">{{ $slider->sub_title }}</div>
                    @endif --}}
                </td>
                {{-- <td class="align-middle" style="font-size: 13px;">
                    @if($slider->link)
                        <div class="text-truncate mb-1" style="max-width: 240px;" title="{{ $slider->link }}">
                            <i class="fas fa-link text-info mr-1"></i> <span class="text-secondary">লিংক:</span> <code>{{ $slider->link }}</code>
                        </div>
                    @endif
                    @if($slider->button_link)
                        <div class="text-truncate" style="max-width: 240px;" title="{{ $slider->button_link }}">
                            <i class="fas fa-mouse-pointer text-success mr-1"></i> <span class="text-secondary">বাটন:</span> <code>{{ $slider->button_link }}</code>
                            @if($slider->button_text)
                                <small class="text-muted">({{ $slider->button_text }})</small>
                            @endif
                        </div>
                    @endif
                    @if(!$slider->link && !$slider->button_link)
                        <span class="text-muted italic small">কোনো লিংক নেই</span>
                    @endif
                </td> --}}
                <td class="text-center align-middle p-0">
                    @if($slider->position == 'homepage')
                        <span class="badge py-1 px-3 rounded-pill font-weight-normal" style="font-size: 11px; background-color: rgba(0, 123, 255, 0.1); color: #007bff;">হোমপেজ</span>
                    @elseif($slider->position == 'banner')
                        <span class="badge py-1 px-3 rounded-pill font-weight-normal" style="font-size: 11px; background-color: rgba(40, 167, 69, 0.1); color: #28a745;">ব্যানার</span>
                    @else
                        <span class="badge py-1 px-3 rounded-pill font-weight-normal" style="font-size: 11px; background-color: rgba(255, 193, 7, 0.1); color: #ffc107;">পপআপ</span>
                    @endif
                </td>
                <td class="text-center align-middle p-0">
                    <div class="input-group input-group-sm mx-auto" style="width: 75px;">
                        <input type="number" class="form-control sort-order-input text-center px-1"
                               value="{{ $slider->sort_order }}" min="0" style="border-radius: 4px 0 0 4px;">
                        <button class="btn btn-outline-secondary update-order" data-id="{{ $slider->id }}" type="button" style="border-radius: 0 4px 4px 0; border-left: none;" title="সংরক্ষণ">
                            <i class="fas fa-save text-success"></i>
                        </button>
                    </div>
                </td>
                <td class="text-center align-middle p-0" >
                    @if($slider->start_date || $slider->end_date)
                        <div class="d-flex flex-column text-muted">
                            @if($slider->start_date)
                                <span><i class="far fa-calendar-alt text-success mr-1"></i> {{ $slider->start_date->format('d M Y') }}</span>
                            @endif
                            @if($slider->end_date)
                                <span class="mt-1"><i class="far fa-calendar-times text-danger mr-1"></i> {{ $slider->end_date->format('d M Y') }}</span>
                            @endif
                        </div>
                    @else
                        <span class="badge bg-light text-muted border px-2 py-1">সার্বজনীন</span>
                    @endif
                </td>
                <td class="text-center align-middle p-0">
                    <button class="btn btn-sm toggle-status {{ $slider->status ? 'btn-success' : 'btn-secondary' }}"
                            data-id="{{ $slider->id }}" style="font-size: 11px; min-width: 75px;">
                        {{ $slider->status ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </button>
                </td>
                <td class="text-center align-middle p-0">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-light text-info border" title="সম্পাদনা">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-light text-danger delete-slider border" data-id="{{ $slider->id }}" data-title="{{ $slider->title ?? 'স্লাইডার' }}" title="মুছে ফেলুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5 bg-white">
                    <i class="fas fa-images fa-3x mb-3 text-muted d-block"></i>
                    <h5 class="text-dark font-weight-bold">কোনো স্লাইডার পাওয়া যায়নি</h5>
                    <p class="text-muted mb-3">ওয়েবসাইটের সৌন্দর্য বৃদ্ধি করতে প্রথম স্লাইডার যুক্ত করুন।</p>
                    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary btn-sm px-4 py-2" style="border-radius: 4px;">
                        <i class="fas fa-plus mr-1"></i> প্রথম স্লাইডার যোগ করুন
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($sliders->isNotEmpty())
<div class="d-flex justify-content-between align-items-center p-3 border-top bg-light" style="border-radius: 0 0 8px 8px;">
    <div class="text-muted" style="font-size: 13px;">
        মোট {{ $sliders->total() }}টি স্লাইডারের মধ্যে {{ $sliders->firstItem() }}-{{ $sliders->lastItem() }} দেখানো হচ্ছে
    </div>
    <div>
        {{ $sliders->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endif
