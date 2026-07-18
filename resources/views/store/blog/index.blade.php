@extends('layouts.store')

@section('title', 'المدونة | ' . config('store.name'))
@section('meta_description', 'مقالات ونصائح من عجيبة عن الأزياء المحتشمة، التنسيق، والعناية بالأناقة اليومية.')
@section('active_nav', 'blog')

@section('content')
<section class="store-section store-blog" aria-labelledby="blog-title">
    <div class="store-blog__hero">
        <p class="store-catalog__eyebrow">من عالم عجيبة</p>
        <h1 id="blog-title" class="store-section__title">المدونة</h1>
        <p class="store-catalog__lead">
            نصائح ستايل، أفكار تنسيق، وكل ما يساعدكِ تختاري إطلالتك بثقة.
        </p>
    </div>

    <div class="store-blog__toolbar">
        <form method="GET" action="{{ route('blog.index') }}" class="store-catalog__search store-blog__search">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="ابحثي في المقالات...">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
        </form>

        <div class="store-blog__filters" role="navigation" aria-label="تصنيفات المدونة">
            <a href="{{ route('blog.index') }}" class="store-blog__filter {{ !request('category') && !request()->routeIs('blog.category') ? 'is-active' : '' }}">الكل</a>
            @foreach($categories as $cat)
                <a href="{{ route('blog.category', $cat) }}"
                   class="store-blog__filter {{ (request('category') === $cat->slug || (isset($category) && $category->id === $cat->id) || (request()->routeIs('blog.category') && optional(request()->route('category'))->slug === $cat->slug)) ? 'is-active' : '' }}">
                    {{ $cat->name }}
                    @if($cat->blogs_count)
                        <span>{{ $cat->blogs_count }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    @if($blogs->count())
        <div class="store-blog__grid">
            @foreach($blogs as $post)
                @include('store.blog._card', ['post' => $post])
            @endforeach
        </div>

        @if($blogs->hasPages())
            <div class="store-catalog__pagination">
                {{ $blogs->links() }}
            </div>
        @endif
    @else
        <p class="store-blog__empty">لا توجد مقالات حالياً — عودي قريباً.</p>
    @endif
</section>
@endsection
