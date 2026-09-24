@foreach($blogs as $blog)
    @if((isset($category) && $category->slug === 'live-update') || request()->is('*live-update*'))
        @include('theme::pages.blog.partials.live_update_card', ['blog' => $blog])
    @else
        @include('theme::pages.blog.partials.blog_card', ['blog' => $blog])
    @endif
@endforeach
