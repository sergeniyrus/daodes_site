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
            justify-content: center;
            text-align: center;
        }

        .tbrv {
            width: 95%;
            margin: 0 5px 0 5px;
        }

        .right_td {
            text-align: right;
        }

        legend {
            margin: 0 auto;
        }

        input.ratio_l {
            position: relative;
            top: -62px;
            right: 40px;
        }

        input.ratio_r {
            position: relative;
            top: -35px;
            left: 40px;
        }

        .img_bt circle {
            border-radius: 15px;
        }
        .circle{
  border-radius: 50px;
}
        .graf {
            display: inline-block;
            width: 90%;
            border: 1px #eeff00 solid;
            height: 16px;
            margin-top: 5px;
        }

        /* блок формы голосования */
        .vote_box {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-vote {
            width: 100%;
        }

        .tbr fieldset {
            border: none;
            padding: 0;
            margin: 0;
            text-align: center;
            justify-content: center;
        }

        .vote_ratio {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .img_vote {
            position: relative;
            margin: 0 10px;
        }

        .img_vote input[type="radio"] {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }

        .img_vote img {
            display: block;
            width: 100px;
            /* Ширина картинки */
            height: auto;
            border: 3px solid transparent;
            /* Начальный цвет рамки */
            transition: border-color 0.3s;
        }

        .img_vote input[type="radio"]:checked+img {
            border-color: #f00;
            /* Цвет рамки для выбранного элемента */
        }

        .admin_menu {
            display: flex;
            /* Располагает элементы в одну строку */
            gap: 20px;
            /* Расстояние между элементами */
            align-items: center;
            /* Выравнивание по вертикали */
            width: 95%;
            justify-content: center;
        }

        .admin_menu div a img {
            width: 24px;
            /* Размер иконок */
            height: auto;
        }

        .blue_btn {
            /* margin: 0 5% 5% 5%; */
            display: inline-block;
            color: #ffffff;
            font-size: large;
            background: #0b0c18;
            padding: 15px 30px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            box-shadow: 0 0 20px #000;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            gap: 15px;
        }



        .blue_btn:hover {
            box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
            transform: scale(1.05);
            color: #ffffff;
            background: #0b0c18;

        }

        .comment {
  width: 70%;
  height: 100%;
  background-color: #0b0c18ce;
  border: 1px solid transparent;
  border-color: #fff;
  margin: 10px auto 2px auto;
  border-top-left-radius: 100% 100px;
  border-top-right-radius: 100% 100px;
  color: #fff;
}

.post_com {
  width: 70%;
  margin: 20px auto 0px auto; /* Центрирование блока по горизонтали */
  border: 1px solid transparent;
  border-color: #fff; /* Цвет границы */
  border-radius: 20px; /* Закругленные углы */
  padding: 10px; /* Внутренние отступы */
  font-size: min(max(60%, 1.5vw), 80%); /* Размер шрифта */

  text-align: center; /* Центрирование текста внутри блока */
}

.submit-button {
  background: linear-gradient(135deg, #6e8efb, #a777e3); /* Градиентный фон */
  border: none; /* Убираем стандартную рамку */
  color: white; /* Цвет текста */
  padding: 10px 20px; /* Отступы внутри кнопки */
  border-radius: 25px; /* Закругленные углы */
  font-size: 16px; /* Размер шрифта */
  cursor: pointer; /* Курсор в виде руки */
  transition: background 0.3s, transform 0.2s; /* Плавный переход */
}

.submit-button:hover {
  background: linear-gradient(135deg, #5a6efb, #8b6fd8); /* Изменение фона при наведении */
  transform: scale(1.05); /* Увеличение кнопки при наведении */
}

.submit-button:focus {
  outline: none; /* Убираем стандартный контур */
  box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1); /* Добавляем тень при фокусе */
}

.author-info {
  display: flex;
  justify-content: center; /* Центрирование содержимого по горизонтали */
  align-items: center; /* Центрирование содержимого по вертикали */
  margin-bottom: 5px; /* Отступ снизу между строками */
}

.author-name {
  font-weight: bold; /* Полужирное начертание для имени автора */
  margin-right: 10px; /* Отступ справа между именем и датой */
}

.form_com {
  width: 50%;
  margin: 0 auto; /* Центрирование блока по горизонтали */
  text-align: center; /* Центрирование текста и элементов внутри блока */
}

.form_com fieldset {
  border: none; /* Убираем стандартную границу */
  padding: 0; /* Убираем отступы */
}

.form_com legend {
  font-size: min(max(80%, 2vw), 100%);
  margin-bottom: 10px; /* Отступ снизу */
}

.form_com textarea {
  width: 100%; /* Заполняет ширину родительского блока */
  box-sizing: border-box; /* Учитывает внутренние отступы и границу */
  padding: 10px; /* Внутренние отступы */
  border: 1px solid #ccc; /* Цвет границы */
  border-radius: 8px; /* Закругленные углы */
  font-size: min(max(70%, 1.5vw), 100%);
}

.comment-text {
  /*color: #333;  Цвет текста комментария */
  font-size: 1em; /* Размер шрифта для текста комментария */
}

.z_com {
  font-size: min(max(60%, 1.5vw), 80%);
  color: #f8fc02;
  margin-top: 25px;
  text-align: center;
}

.name_com {
  font-size: min(max(60%, 1.5vw), 80%);
  color: #05ff19;
}

.date_com {
  margin-top: 5px;
  font-size: min(max(50%, 1.5vw), 70%);
  color: #f8fc02;
}

.text_com {
  font-size: min(max(70%, 2vw), 100%);
  margin: 10px;
  color: #89d0ec;
}

.eror_com {
  font-size: min(max(80%, 2vw), 120%);
  text-align: center;
  color: aqua;
  margin: 20px 0 20px 0;
}

a.eror_com:after,
a.eror_com:hover {
  color: #0fc51e;
  text-decoration: none;
}

a.eror_com {
  color: #f1d11a;
  text-decoration: none;
}

.form_com {
  width: 50%;
  height: auto;
  margin: 0 auto;
  text-align: center;
}



    </style>
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
                            <a href="{{ route($post . '.add') }}" title="Создать" class="blue_btn">
                                <img src="/img/icons_post/add.png" alt="Создать">
                            </a>
                        </div>
                        <div>
                            <a href="{{ route($post . '.edit', ['id' => $text->id]) }}" title="Редактировать" class="blue_btn"
                                title="Редактировать">
                                <img src="/img/icons_post/edit.png" alt="Редактировать"></a>
                        </div>
                        <div>
                            <!-- Ссылка на удаление с динамическим использованием переменной $post -->
                            <a href="{{ route($post . '.destroy', ['id' => $text->id]) }}" title="Удалить" class="blue_btn"
                                onclick="event.preventDefault(); document.getElementById('delete-form-{{ $text->id }}').submit();">
                                <img src="/img/icons_post/delete.png" alt="Удалить">
                            </a>

                            <!-- Форма для отправки запроса на удаление -->
                            <form id="delete-form-{{ $text->id }}"
                                action="{{ route($post . '.destroy', ['id' => $text->id]) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
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
                            <div class="date_com">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.y в H:i') }}
                            </div>
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
                        <p><input type="submit" value="Отправить"  class="blue_btn"/></p><br>
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
