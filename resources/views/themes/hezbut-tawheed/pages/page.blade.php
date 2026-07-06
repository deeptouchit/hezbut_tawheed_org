@extends('theme::layouts.app')

@section('title', $page->title . ' - হেযবুত তওহীদ')

@if(!empty($page->meta_description))
    @section('meta_description', $page->meta_description)
@endif

@section('content')

    @if(in_array($page->slug, ['emamuzzaman', 'emam-ht', 'attack-on-us', 'sonaimuri-tragedy', 'monogram', 'about-us']))
        <!-- Full-width Elementor Page -->
        <div class="elementor-page-wrapper bg-white">
            {!! $page->content !!}
        </div>
    @else
        <div class="py-5 text-white position-relative" style="background: linear-gradient(rgba(0,106,78,0.85), rgba(0,106,78,0.85)), url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1200') no-repeat center center; background-size: cover; border-bottom: 4px solid #10B981;">
            <div class="container py-4 text-center">
                <h1 class="display-4 fw-bold mb-0 text-shadow text-white">{{ $page->title }}</h1>
            </div>
        </div>

        <!-- Page Content Area -->
        <section class="py-6 bg-off-white">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white" style="border-top: 5px solid #006A4E !important;">
                            <article class="page-body text-start text-dark lh-lg" style="font-family: 'Baloo Da 2', sans-serif;">
                                {!! $page->content !!}
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Style for rendering raw HTML cleanly from CKEditor -->
    <style>
        .page-body h1, .page-body h2, .page-body h3, .page-body h4, .page-body h5, .page-body h6 {
            color: #006A4E;
            font-weight: 700;
            margin-top: 1.8rem;
            margin-bottom: 1rem;
        }
        .page-body p {
            margin-bottom: 1.25rem;
            color: #475569;
            text-align: justify;
        }
        .page-body ul, .page-body ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
            color: #475569;
        }
        .page-body li {
            margin-bottom: 0.5rem;
        }
        .page-body blockquote {
            border-left: 4px solid #10B981;
            background-color: #f8fafc;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            font-style: italic;
            border-radius: 0 8px 8px 0;
        }
        .page-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
    </style>

@endsection


