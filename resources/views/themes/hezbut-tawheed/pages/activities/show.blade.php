@extends('theme::layouts.app')

@section('title', $activity->title . ' - হেযবুত তওহীদ')

@if(!empty($activity->description))
    @section('meta_description', $activity->description)
@endif

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => $activity->title,
        'subtitle' => $activity->description ?? '',
        'badge_text' => 'আমাদের কার্যক্রম',
        'badge_icon' => 'fas fa-tasks'
    ])

    <!-- Detail View Section -->
    <section class="py-6 bg-off-white">
        <div class="container">
            <div class="row">
                <!-- Left: Main Article Content -->
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white" style="border-top: 5px solid #006A4E !important;">
                        
                        <!-- Featured Image -->
                        @if($activity->image)
                            <div class="mb-4 text-center">
                                <img src="{{ $activity->image_url }}" alt="{{ $activity->title }}" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 450px; object-fit: cover;">
                            </div>
                        @endif

                        <!-- Metadata -->
                        <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-3 border-bottom border-light">
                            <span><i class="far fa-calendar-alt me-1"></i> {{ $activity->created_at->format('d M, Y') }}</span>
                            <span><i class="far fa-clock me-1"></i> {{ $activity->created_at->format('h:i A') }}</span>
                        </div>

                        <!-- Full Content Body -->
                        <article class="activity-body text-start text-dark lh-lg" style="font-family: 'Baloo Da 2', sans-serif;">
                            {!! $activity->content !!}
                        </article>
                    </div>
                </div>

                <!-- Right: Recent Activities Sidebar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white" style="position: sticky; top: 90px; border-top: 5px solid #10B981 !important;">
                        <h5 class="fw-bold text-dark-green mb-4 pb-2 border-bottom border-light">অন্যান্য কার্যক্রম</h5>
                        
                        <div class="d-flex flex-column gap-3.5">
                            @forelse($recentActivities as $recent)
                                <a href="{{ route('activities.show', $recent->slug) }}" class="text-decoration-none d-flex align-items-start gap-3 p-2 rounded hover-bg-light transition">
                                    <img src="{{ $recent->image_url }}" alt="{{ $recent->title }}" class="rounded object-fit-cover" style="width: 70px; height: 50px; min-width: 70px;">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1 small lh-base" style="font-family: 'Baloo Da 2', sans-serif;">{{ Str::limit($recent->title, 55) }}</h6>
                                        <span class="text-muted small" style="font-size: 0.75rem;">{{ $recent->created_at->format('d M, Y') }}</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-muted small mb-0">অন্য কোনো কার্যক্রম পাওয়া যায়নি।</p>
                            @endforelse
                        </div>

                        <div class="mt-4 pt-3 border-top border-light">
                            <a href="{{ route('activities.index') }}" class="btn btn-outline-dark-green btn-sm w-100 fw-bold rounded-pill">
                                সব কার্যক্রম দেখুন <i class="fas fa-list ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom CSS for CKEditor content within dynamic activities -->
    

@endsection


