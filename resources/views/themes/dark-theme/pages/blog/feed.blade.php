<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>হেজবুত তওহীদ ফিড</title>
    <link>{{ route('blog') }}</link>
    <description>হেজবুত তওহীদ নিউজ পোর্টাল ও ব্লগের সর্বশেষ সংবাদ ফিড</description>
    <language>bn</language>
    <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
    <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml" />

    @foreach($blogs as $blog)
        <item>
            <title><![CDATA[{{ $blog->title }}]]></title>
            <link>{{ route('blog.detail', $blog->slug) }}</link>
            <guid isPermaLink="true">{{ route('blog.detail', $blog->slug) }}</guid>
            <description><![CDATA[{{ Str::limit(strip_tags($blog->short_description ?? $blog->content), 300) }}]]></description>
            <content:encoded><![CDATA[{!! $blog->content !!}]]></content:encoded>
            <dc:creator>{{ $blog->author->name ?? 'হেজবুত তওহীদ অ্যাডমিন' }}</dc:creator>
            <pubDate>{{ ($blog->published_at ?? $blog->created_at)->toRssString() }}</pubDate>
            @if($blog->featured_image_url)
                <enclosure url="{{ $blog->featured_image_url }}" length="100000" type="image/jpeg" />
            @endif
        </item>
    @endforeach
</channel>
</rss>
