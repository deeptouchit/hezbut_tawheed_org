{{-- resources/views/themes/hezbut-tawheed/pages/books/partials/grid_items.blade.php --}}

@foreach($books as $book)
    <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="ht-book-item shadow-sm">
            <!-- Badge -->
            @php
                $discountText = '';
                $cleanPrice = preg_replace('/[^\d]/', '', $book->price);
                $cleanOldPrice = preg_replace('/[^\d]/', '', $book->old_price);
                if ($cleanOldPrice && $cleanPrice && intval($cleanOldPrice) > intval($cleanPrice)) {
                    $discount = round(((intval($cleanOldPrice) - intval($cleanPrice)) / intval($cleanOldPrice)) * 100);
                    $discountText = $discount . '% অফ';
                }
            @endphp

            @if($discountText)
                <div class="ht-book-badge-discount"><i class="fas fa-tag me-1"></i>{{ $discountText }}</div>
            @elseif($book->is_bestseller)
                <div class="ht-book-badge-bestseller"><i class="fas fa-fire me-1"></i>Best Selling</div>
            @elseif($book->is_popular)
                <div class="ht-book-badge-popular"><i class="fas fa-star me-1"></i>সেরা পাঠক প্রিয়</div>
            @endif
            
            <!-- Image -->
            <div class="ht-book-img">
                <a href="{{ route('books.show', $book->slug) }}">
                    <img src="{{ $book->image_url }}" alt="{{ $book->title }}" loading="lazy">
                </a>
            </div>
            
            <!-- Info -->
            <div class="ht-book-info text-center" style="align-items: center !important; text-align: center !important;">
                <div class="ht-book-name text-center" style="text-align: center !important;">
                    <a href="{{ route('books.show', $book->slug) }}" title="{{ $book->title }}" class="text-center" style="text-align: center !important; display: block;">
                        {{ $book->title }}
                    </a>
                </div>
                <div class="ht-book-writer text-center" style="text-align: center !important; width: 100%;">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</div>
                <div class="ht-book-pricing text-center" style="text-align: center !important; width: 100%;">
                    @if($book->price !== null && $book->price !== '' && $book->price != '০' && $book->price != '0')
                        মূল্য ৳{{ $book->price }}{{ str_contains($book->price, 'ক') || str_contains($book->price, 'খ') || str_contains($book->price, 'গ') ? '' : '/-' }}
                    @else
                        বিনামূল্যে
                    @endif
                </div>
            </div>
            
            <!-- Button Wrapper -->
            <div class="ht-btn-wrapper">
                <a href="{{ route('books.show', $book->slug) }}" class="ht-details-btn">বিস্তারিত দেখুন</a>
            </div>
        </div>
    </div>
@endforeach
