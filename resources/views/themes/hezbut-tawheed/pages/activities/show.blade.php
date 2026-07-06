@extends('theme::layouts.app')

@section('title', $activity->title . ' - হেযবুত তওহীদ')

@if(!empty($activity->description))
    @section('meta_description', $activity->description)
@endif

@section('content')

    <!-- Banner Header -->
    <div class="py-5 text-white position-relative" style="background: linear-gradient(rgba(0,106,78,0.85), rgba(0,106,78,0.85)), url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1200') no-repeat center center; background-size: cover; border-bottom: 4px solid #10B981;">
        <div class="container py-4 text-center">
            <h1 class="display-5 fw-bold mb-0 text-shadow text-white">{{ $activity->title }}</h1>
        </div>
    </div>

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
    <style>
        .activity-body h1, .activity-body h2, .activity-body h3, .activity-body h4, .activity-body h5, .activity-body h6 {
            color: #006A4E;
            font-weight: 700;
            margin-top: 1.8rem;
            margin-bottom: 1rem;
        }
        .activity-body p {
            margin-bottom: 1.25rem;
            color: #475569;
            text-align: justify;
        }
        .activity-body ul, .activity-body ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
            color: #475569;
        }
        .activity-body li {
            margin-bottom: 0.5rem;
        }
        .activity-body blockquote {
            border-left: 4px solid #10B981;
            background-color: #f8fafc;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            font-style: italic;
            border-radius: 0 8px 8px 0;
        }
        .hover-bg-light:hover {
            background-color: #f8fafc;
        }
        .gap-3.5 {
            gap: 0.85rem !important;
        }
    </style>

@endsection


