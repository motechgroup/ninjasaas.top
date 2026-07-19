<?xml version="1.5" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>SaaSNinja Technical Publications</title>
    <link>{{ route('blog') }}</link>
    <description>Weekly software manuals, Linux configs, database modeling, and Laravel security advisories from SaaSNinja team.</description>
    <language>en-us</language>
    <atom:link href="{{ route('rss.feed') }}" rel="self" type="application/rss+xml" />

    @foreach ($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ route('blog.show', $post->slug) }}</link>
            <guid>{{ route('blog.show', $post->slug) }}</guid>
            <pubDate>{{ $post->published_at ? $post->published_at->toRssString() : $post->created_at->toRssString() }}</pubDate>
            <description><![CDATA[{!! $post->summary !!}]]></description>
        </item>
    @endforeach
</channel>
</rss>
