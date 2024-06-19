@extends('template')

@section('title_page')
    <?php
    if ($post == 'offers') {
        $category_name = 'category_offers';
        $comments_tab = 'comments_offers';
        $column = 'offer_id';
        echo 'Предложение №: ' . $id;
    }
    if ($post == 'news') {
        $category_name = 'category_news';
        $comments_tab = 'comments_news';
        $column = 'new_id';
        echo 'Новость №: ' . $id;
    }
    ?>
@endsection

@section('main')
    <br>
    <div class="flex-container">
        <div class="item-1">
            <div class="img_post"><img src="{{ $text->img }}" /></div>
            @auth
                <?php
                $rol = DB::table('users')
                    ->where('name', Auth::user()->name)
                    ->select('rang_access')
                    ->first();
                ?>
                @if ($rol->rang_access >= 3)
                    <div class="admin_menu my-4">
                        <div>
                            <a href="/add_{{$post}}" title="Создать">
                                <img src="/img/icons_post/add.png" alt="Создать">
                            </a>
                        </div>
                        <div>
                            <a href="/edit_{{$post}}/{{ $text->id }}">
                                <img src="/img/icons_post/edit.png" title="Редактировать" alt="Редактировать"></a>
                        </div>
                        {{-- <div>
                            <a href="/delete_new" title="Удалить">
                                <img src="/img/icons_post/delete.png" alt="Удалить"></a>
                        </div> --}}
                    </div>
                @endif
            @endauth
        </div>
        <div class="item-2">
            <div class="title_post">
                <h5>
                    <a href="{{ $text->id }}">{{ $text->title }}</a>
                    <hr class="hr_title">
                </h5>
            </div>
            <div class="content_post">
                <p>
                    {!! html_entity_decode($text->content) !!}
                </p>
            </div>
            <div class="footer-post">
                <div class="infa_post">
                    <div class="viewes_post">
                        <span class="rown"><img src="/img/icons_post/views.png" />&nbsp;</span>
                        <span class="rown">
                            <?php
                            if ($text->views === null) {
                                echo '0';
                            } else {
                                echo $text->views;
                            }
                            ?>
                        </span>
                    </div>
                    <div class="comment_post">
                        <span class="rown"><img src="/img/icons_post/comments.png" />&nbsp;</span>
                        <span class="rown">
                            <?php
                            $commentCount = DB::table($comments_tab)
                                ->where($column, $text->id)
                                ->count();
                            ?>
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
        <?php
        $category_id = $text->category_id; ?>
        @include('partials.block_category')
    </div>
    <br>
@endsection
