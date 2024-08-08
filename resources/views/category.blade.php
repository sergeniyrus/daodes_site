@extends('template')

@section('title_page')
<?php
if ($post == "offers") {
    $category_name = "category_offers";
    $comments_tab = 'comments_offers';
    $column = 'id_offer';
    echo "Темы предложений";
}
if ($post == "news"){
    $category_name = "category_news";
    $comments_tab = 'comments_news';
    $column = 'new_id';
    echo "Темы новостей";
}
?>
@endsection

@section('main')
<br>
@if ($texts->count())
    @foreach ($texts as $text)
        @php
            if ($post == "offers") {
                $link = "/page/offers/{$text->id}";
            }
            if ($post == "news"){
                $link = "/page/news/{$text->id}";
            }
        @endphp

        <div class="flex-container">
            <div class="item-1">
                <div class="img_post"><img src="{{ $text->img }}" /></div>
            </div>
            <div class="item-2">
                <div class="title_post">
                    <h5>
                        <a href="{{ $link }}">{{ $text->title }}</a>
                        <hr class="hr_title">
                    </h5>
                </div>
                <div class="content_post">
                    <p>
                        {!! Str::limit(html_entity_decode($text->content), 250) !!}
                        <a class="all_post" href="{{ $link }}">Дальше</a>
                    </p>
                </div>
                <div class="footer-post">
                    <div class="infa_post">
                        <div class="viewes_post">
                            <span class="rown"><img src="/img/icons_post/views.png" />&nbsp;</span>
                            <span class="rown">
                                @if ($text->views === null) 0
                                @else {{ $text->views }}
                                @endif
                            </span>
                        </div>
                        <div class="comment_post">
                            <span class="rown"><img src="/img/icons_post/comments.png" />&nbsp;</span>
                            <span class="rown">
                                @php
                                    $commentCount = DB::table($comments_tab)
                                        ->where($column, $text->id)
                                        ->count();
                                @endphp
                                {{ $commentCount }}
                            </span>
                        </div>
                        <div class="data_post">
                            <span class="rown"><img src="/img/icons_post/data.png" />&nbsp;</span>
                            <span class="rown">
                                {{ \Carbon\Carbon::parse($text->created_at)->format('d/m/y') }}
                            </span>
                        </div>
                        <div class="author_post">
                            <span class="rown"><img src="/img/icons_post/author.png" />&nbsp;</span>
                            <span class="rown">
                                @php
                                    $authorName = DB::table('users')
                                        ->where('id', $text->user_id)
                                        ->value('name');
                                @endphp
                                {{ $authorName }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- //темы категории --}}
            @php
                $category_id = $text->category_id;
            @endphp
            @include('partials.block_category')
        </div>
        <br>
    @endforeach
@endif
@endsection
