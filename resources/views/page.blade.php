@extends('template')

@section('title_page')
    <?php
    if ($post == 'offers') {
        $category_name = 'category_offers';
        $comments_tab = 'comments_offers';
        $column = 'id_offer';
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
                        <div>
                            <a href="/delete_new" title="Удалить">
                                <img src="/img/icons_post/delete.png" alt="Удалить"></a>
                        </div>
                    </div>
                @endif
            @endauth
            {{-- подключаем блок голосования или обсуждения --}}
            @if ($post == 'offers')
                @if (view()->exists($viewFile))
                    @include($viewFile)
                @else
                    <p>Блок в разработке.</p>
                @endif
            @endif
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

    @if ($post == 'offers' && $text->state == 1)
        <div class="comment">
            <div class="z_com">
                <p>Обсуждений: {{ $commentCount }}</p>
            </div>

            @if ($commentCount == 0)
                <div class="eror_com">Будьте первым, кто напишет своё мнение.</div>
            @else
                @foreach ($comments as $comment)
                    <div class="post_com">
                        <div class="name_com">
                            {{ DB::table('users')->where('id', $comment->id_user)->value('name') }}
                            <div class="date_com">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.y в H:i') }}</div>
                            <div class="text_com">{{ $comment->text }}</div>
                        </div>
                    </div>
                @endforeach
            @endif

            @auth
                <div class="form_com">
                    <br>
                    <form name="comment" action="{{ route('comments.store') }}" method="post">
                        @csrf
                        <fieldset>
                            <legend> Написать сообщение </legend><br>
                            <textarea name="text" cols="80" rows="10" placeholder="Ваше мнение ..." style="color: black;"></textarea>
                            <br><br>
                            <input type="hidden" name="id_offer" value="{{ $text->id }}" />
                        </fieldset><br>
                        <p><input type="submit" value="Отправить" /></p><br>
                    </form>
                </div>
            @else
                <div class="login_prompt">
                    <p>Чтобы оставить комментарий, <a href="{{ route('login') }}" class="login-link">войдите в систему</a>.</p>
                </div>
            @endauth
        </div>
    @endif
@endsection
