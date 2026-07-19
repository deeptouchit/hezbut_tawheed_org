@foreach($blogs as $blog)
    @include('theme::pages.blog.partials.blog_card', ['blog' => $blog])
@endforeach
