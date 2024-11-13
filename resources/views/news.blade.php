<!-- Главная страница списка новостей (без пагинации) -->
@extends('template')

@section('title_page')
    Новости
@endsection

@section('main')
<style>
    .flex-container {
  width: 90%;
  height: 100%;
  background-color: #0b0c18ce;
  border: 1px solid transparent;
  border-color: #fff;
  margin: 1px auto 50px auto;
  border-bottom-right-radius: 100% 100px;
  border-bottom-left-radius: 100% 100px;
  display: flex;
}
.item-1 {
  width: 20%;
  height: auto;
  vertical-align: top;
  padding: 10px;
}

.item-2 {
  width: 60%;
  vertical-align: top;
}

.item-3 {
  width: 20%;
  font-size: min(max(50%, 1vw), 80%);
  font-family: Georgia, "Times New Roman", Times, serif;
  text-decoration: none;
  text-align: justify;
}
img_post {
  width: 100%;
  height: auto;
  border: 1px solid #b4b4b4;
  background-size: cover;
}

.title_post {
  width: 100%;
  height: auto;
  font-size: min(max(80%, 2vw), 150%);
  text-align: center;
}

.hr_title {
  width: 90%;
  margin: 5px 5% 0 5%;
}

.title_post a:active,
.title_post a:hover {
  text-decoration: none;
  color: #0faa1c;
}

.title_post a {
  text-decoration: none;
  color: #f1d11a;
}

a.all_post:active,
a.all_post:hover {
  text-decoration: none;
  color: #2427df;
}

a.all_post {
  text-decoration: none;
  color: #2427df;
}

.category_post {
  width: 100%;
  font-family: Georgia, "Times New Roman", Times, serif;
  font-size: min(max(80%, 3vw), 160%);
  color: rgb(0, 68, 255);
  text-align: center;
}

.text_post {
  width: 100%;
  height: auto;
  font-size: min(max(80%, 2vw), 120%);
  padding: 1px 5px 1px 0;
  text-align: justify;
}

.text_post a {
  color: #036efa;
}

.footer-post {
  justify-self: center;
}

.infa_post {
  display: flex;
  /* height: 40px; */
  line-height: 40px;
  /* justify-items: center; */
  align-items: center;
  justify-content: space-between;
  margin: 100px 0 15px 0;
  font-size: min(max(40%, 1vw), 80%);

}

.viewes_post,
.comment_post,
.data_post {
  width: auto;

}

.author_post {
  width: auto;
}

.rown {
  height: auto;
  justify-content: space-between;
}

.left_box {
  width: 100%;
  font-size: min(max(50%, 1.5vw), 90%);
  color: aqua;
}


</style>
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
                                            ->where('news_id', $new->id)
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
