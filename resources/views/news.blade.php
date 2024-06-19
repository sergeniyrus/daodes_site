<!-- Главная страница списка новостей (без пагинации) -->
@extends('template')

@section('title_page')
    Новости
@endsection

@section('main')
    <br>
    @if ($news->count())
        @foreach ($news as $new)
            <div class="flex-container">
                <div class="item-1">
                    <div class="img_post"><img src="{{ $new->img }}" /></div>
                </div>
                <div class="item-2">
                    <div class="title_post">
                        <h5>
                            <a href="/page/news/{{ $new->id }}">{{ $new->title }}</a>
                            <hr class="hr_title">
                        </h5>
                    </div>
                    <div class="content_post">
                        <p>
                            {!! Str::limit(html_entity_decode($new->content), 250) !!}
                            <a class="all_post" href="/page/news/{{ $new->id }}">Дальше</a>
                        </p>
                    </div>
                    <div class="footer-post">
                        <div class="infa_post">
                            <div class="viewes_post">
                                <span class="rown"><img src="/img/icons_post/views.png" />&nbsp;</span>
                                <span class="rown">
                                    @if ($new->views === null)
                                        0
                                    @else
                                        {{ $new->views }}
                                    @endif
                                </span>
                            </div>
                            <div class="comment_post">
                                <span class="rown"><img src="/img/icons_post/comments.png" />&nbsp;</span>
                                <span class="rown">
                                    @php
                                        $commentCount = DB::table('comments_news')
                                            ->where('new_id', $new->id)
                                            ->count();
                                    @endphp
                                    {{ $commentCount }}
                                </span>
                            </div>
                            <div class="data_post">
                                <span class="rown"><img src="/img/icons_post/data.png" />&nbsp;</span>
                                <span class="rown">
                                    {{ \Carbon\Carbon::parse($new->created_at)->format('d/m/y') }}
                                </span>
                            </div>
                            <div class="author_post">
                                <span class="rown"><img src="/img/icons_post/author.png" />&nbsp;</span>
                                <span class="rown">
                                    @php
                                        $authorName = DB::table('users')
                                            ->where('id', $new->user_id)
                                            ->value('name');
                                    @endphp
                                    {{ $authorName }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- //темы категгории --}}
                @php
                    $post = 'news';
                    $category_name = 'category_news';
                    $category_id = $new->category_id;
                @endphp
                @include('partials.block_category')
            </div>
            <br>
        @endforeach
    @endif
@endsection
