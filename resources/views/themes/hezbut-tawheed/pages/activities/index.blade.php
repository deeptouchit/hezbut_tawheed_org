@extends('theme::layouts.app')

@section('title', 'আমাদের কার্যক্রম - হেযবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদ আন্দোলনের দেশব্যাপী বিভিন্ন সামাজিক, সেবামূলক ও সচেতনতা কার্যক্রম')

@section('content')

    <!-- Banner Header -->
    <div class="py-5 text-white position-relative" style="background: linear-gradient(rgba(0,106,78,0.85), rgba(0,106,78,0.85)), url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1200') no-repeat center center; background-size: cover; border-bottom: 4px solid #10B981;">
        <div class="container py-4 text-center">
            <h1 class="display-4 fw-bold mb-0 text-shadow text-white">আমাদের কার্যক্রম</h1>
            <p class="lead mt-2 mb-0 opacity-90 text-white" style="font-family: 'Baloo Da 2', sans-serif;">মানবতার সেবা ও সমাজ সংস্কারে আমাদের সামাজিক উদ্যোগসমূহ</p>
        </div>
    </div>

    <!-- Activities Grid Section -->
    <section class="py-6 bg-off-white">
        <div class="container">
            <div class="row g-4">
                @forelse($activities as $activity)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-grow transition" style="border-top: 4px solid #006A4E !important;">
                            <div class="position-relative" style="height: 220px; overflow: hidden;">
                                <img src="{{ $activity->image_url }}" alt="{{ $activity->title }}" class="w-100 h-100 object-fit-cover transition" style="transition: transform 0.4s ease;">
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="fw-bold text-dark-green mb-2 lh-base" style="font-family: 'Baloo Da 2', sans-serif;">{{ $activity->title }}</h5>
                                <p class="text-muted small lh-lg flex-grow-1" style="text-align: justify; font-family: 'Baloo Da 2', sans-serif;">
                                    {{ Str::limit($activity->description, 160) }}
                                </p>
                                <div class="mt-3 pt-3 border-top border-light">
                                    <a href="{{ route('activities.show', $activity->slug) }}" class="btn btn-gold btn-sm px-3 rounded-pill fw-bold w-100">
                                        বিস্তারিত পড়ুন <i class="fas fa-arrow-right ms-1" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-list-check fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">বর্তমানে কোনো কার্যক্রম পাওয়া যায়নি</h4>
                        <p class="text-muted small">এডমিন প্যানেলে লগইন করে নতুন কার্যক্রম যুক্ত করুন।</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($activities instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $activities->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $activities->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>

    <style>
        .hover-grow:hover img {
            transform: scale(1.05);
        }
    </style>

@endsection


