@extends('theme::layouts.app')

@section('title', $page->title . ' - হেযবুত তওহীদ')

@if(!empty($page->meta_description))
    @section('meta_description', $page->meta_description)
@endif

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => $page->title,
        'subtitle' => $page->meta_description ?? '',
        'badge_text' => 'অফিশিয়াল পাতা',
        'badge_icon' => 'fas fa-file-alt'
    ])

    <!-- Page Content Area -->
    <section class="py-5 bg-off-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-11">
                    <div class="page-content-wrapper p-2 p-md-4">
                        <article class="page-body text-start text-dark lh-lg">
                            {!! $page->content !!}

                            @if($page->slug === 'about-us' && isset($teamMembers) && count($teamMembers) > 0)
                                <!-- Dynamic Executive Committee -->
                                <div class="mt-5 pt-5 border-top">
                                    <h3 class="mb-5 text-center fw-bold" style="color: #0f172a; font-size: 2rem;">কেন্দ্রীয় কার্যনির্বাহী কমিটি</h3>
                                    <div class="row g-4 justify-content-center">
                                        @foreach($teamMembers as $member)
                                            <div class="col-lg-4 col-md-6 col-sm-10">
                                                <div class="card text-center border shadow-sm rounded-4 overflow-hidden bg-white h-100" style="transition: all 0.3s ease;">
                                                    <div style="aspect-ratio: 1; overflow: hidden; background: #f8fafc;">
                                                        <img src="{{ asset($member->image_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400') }}" alt="{{ $member->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                    <div class="card-body p-4 bg-white">
                                                        <h5 class="fw-bold text-dark mb-2" style="font-size: 1.25rem; color: #0f172a !important;">{{ $member->name }}</h5>
                                                        <p class="text-muted small mb-0 fw-semibold">{{ $member->designation }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Style for rendering raw HTML cleanly from Visual Editor -->
    

@endsection
