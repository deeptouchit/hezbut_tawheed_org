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
    <style>
        .page-body {
            font-family: 'Baloo Da 2', 'SolaimanLipi', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            font-size: 1.15rem;
            line-height: 1.85;
            color: #1e293b !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        .page-body h1, .page-body h2, .page-body h3, .page-body h4, .page-body h5, .page-body h6 {
            color: #0f172a !important;
            font-weight: 700;
            margin-top: 2.2rem;
            margin-bottom: 1.2rem;
            line-height: 1.4;
        }
        .page-body h1 { font-size: 2.25rem; }
        .page-body h2 { font-size: 1.85rem; }
        .page-body h3 { font-size: 1.5rem; }
        
        .page-body p {
            margin-bottom: 1.5rem;
            color: #1e293b !important;
            text-align: justify;
        }
        .page-body ul, .page-body ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
            color: #1e293b !important;
        }
        .page-body li {
            margin-bottom: 0.5rem;
        }
        .page-body blockquote {
            border-left: 4px solid #006A4E;
            background-color: #f8fafc;
            padding: 1.25rem 1.75rem;
            margin: 2rem 0;
            font-style: italic;
            border-radius: 0 8px 8px 0;
            color: #475569;
        }
        .page-body img {
            max-width: 100%;
            height: auto !important;
            border-radius: 12px;
            margin: 1.5rem 0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
        }
        
        /* Table Responsiveness */
        .page-body table {
            width: 100% !important;
            margin: 2rem 0;
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        .page-body th, .page-body td {
            padding: 0.85rem 1.2rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        .page-body th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #0f172a;
        }
        
        /* TinyMCE Alignments and Wrappers */
        .page-body .alignleft {
            float: left;
            margin: 0.5rem 1.5rem 1.5rem 0 !important;
        }
        .page-body .alignright {
            float: right;
            margin: 0.5rem 0 1.5rem 1.5rem !important;
        }
        .page-body .aligncenter {
            display: block;
            margin-left: auto !important;
            margin-right: auto !important;
            text-align: center;
        }
        
        /* Custom card components rendered within pages */
        .page-body .card {
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .page-body .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.08) !important;
        }
        
        /* Mobile responsive overrides */
        @media (max-width: 991px) {
            .page-body p {
                text-align: left;
            }
        }
        @media (max-width: 768px) {
            .page-body .alignleft, .page-body .alignright {
                float: none !important;
                display: block;
                margin-left: auto !important;
                margin-right: auto !important;
                max-width: 100%;
                text-align: center;
                margin-bottom: 1.5rem !important;
            }
            .page-body {
                font-size: 1.05rem;
            }
            .page-body h1 { font-size: 1.8rem; }
            .page-body h2 { font-size: 1.5rem; }
            .page-body h3 { font-size: 1.3rem; }
        }
    </style>

@endsection
