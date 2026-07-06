{{-- resources/views/themes/hezbut-tawheed/pages/books/partials/grid_items.blade.php --}}

@foreach($books as $book)
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="ht-book-item">
            <!-- Badge -->
            @php
                $discountText = '';
                $cleanPrice = preg_replace('/[^\d]/', '', $book->price);
                $cleanOldPrice = preg_replace('/[^\d]/', '', $book->old_price);
                if ($cleanOldPrice && $cleanPrice && intval($cleanOldPrice) > intval($cleanPrice)) {
                    $discount = round(((intval($cleanOldPrice) - intval($cleanPrice)) / intval($cleanOldPrice)) * 100);
                    $discountText = $discount . '% অফ';
                } else {
                    $discountText = 'সেরা পাঠক প্রিয়';
                }
            @endphp
            <div class="ht-book-badge">{{ $discountText }}</div>
            
            <!-- Image -->
            <div class="ht-book-img">
                <a href="{{ route('books.show', $book->slug) }}">
                    <img src="{{ $book->image_url }}" alt="{{ $book->title }}" loading="lazy">
                </a>
            </div>
            
            <!-- Info -->
            <div class="ht-book-info">
                <div class="ht-book-name">
                    <a href="{{ route('books.show', $book->slug) }}" title="{{ $book->title }}">
                        {{ $book->title }}
                    </a>
                </div>
                <div class="ht-book-writer">{{ $book->writer ?? 'হেযবুত তওহীদ' }}</div>
                <div class="ht-book-pricing">
                    @if($book->price !== null && $book->price !== '')
                        মূল্য ৳{{ $book->price }}{{ str_contains($book->price, 'ক') || str_contains($book->price, 'খ') || str_contains($book->price, 'গ') || $book->price == '০' || $book->price == '0' ? '' : '/-' }}
                    @else
                        ফ্রি / PDF
                    @endif
                </div>
            </div>
            
            <!-- Button -->
            <a href="{{ route('books.show', $book->slug) }}" class="ht-add-cart-btn">বিস্তারিত দেখুন</a>
        </div>
    </div>
@endforeach
