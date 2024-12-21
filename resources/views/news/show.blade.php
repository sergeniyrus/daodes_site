@extends('template')

@section('title_page')
    Новость № {{ $news->id }}
@endsection

@section('main')
<link rel="stylesheet" href="{{ asset('css/news.css') }}">
    <div class="container my-5">
        <div class="news-header">
            
            <div class="img_post">
                <img src="{{ $news->img }}" alt="Image for {{ $news->title }}" />
            </div>

            <div class="rows-title">
                <div class="task-title">
                <h5>{{ $news->title }}</h5>
            </div>
                <div class="task-info">
                    <p class="task-category"><i class="fas fa-folder-open"></i> {{ $categoryName }}</p>
                </div>
                <div class="task-info2">
                    <p class="task-data"><i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($news->created_at)->format('d/m/y') }}</p>
                    <p class="task-views"><i class="fas fa-eye"></i> {{ $news->views }}</p>
                    <p class="task-comment"><i class="fa fa-comments" aria-hidden="true"></i> {{ $commentCount }}</p>
                </div>
                @auth
                    @if (Auth::user()->access_level >= 3)
                        <div class="admin_menu">
                            <div>
                                <a href="{{ route('news.add') }}" class="blue_btn" title="Создать">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('news.edit', ['id' => $news->id]) }}" class="blue_btn" title="Редактировать">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('news.destroy', ['id' => $news->id]) }}" class="blue_btn"
                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $news->id }}').submit();">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <form id="delete-form-{{ $news->id }}"
                                    action="{{ route('news.destroy', ['id' => $news->id]) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <hr class="hr_title">

        <div class="news-content card">
            <p>{!! $news->content !!}</p>
        </div>

        <div class="comment">
            <div class="z_com">
                <h1>Обсуждений: {{ $commentCount }}</h1>
            </div>

            @if ($commentCount == 0)
                <div class="eror_com">Будьте первым, кто напишет своё мнение.</div>
            @else
                @foreach ($comments as $comment)
                    <div class="post_com">
                        <div class="name_com">
                            {{ DB::table('users')->where('id', $comment->user_id)->value('name') }}
                            <div class="date_com">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.y в H:i') }}
                            </div>
                            <div class="text_com">{!! $comment->text !!}</div>
                        </div>
                    </div>
                @endforeach
            @endif

            @auth
            <div class="form_com">
                <form name="comment" action="{{ route('comments.news') }}" method="post">
                    @csrf
                    <fieldset>
                        <legend class="z_com"> Написать сообщение </legend>
                        <textarea id="editor" name="text" cols="80" rows="10" placeholder="Ваше мнение ..."></textarea>
                        <input type="hidden" name="news_id" value="{{ $news->id }}" />
                    </fieldset><br>
                    <div style="text-align: center">
                        <button type="submit" class="blue_btn">
                            <i class="fa fa-save"></i> Сохранить
                        </button>
                    </div><br>
                </form>
            </div>
            
            @else
                <div class="login_prompt">
                    <p>Чтобы оставить комментарий, <a href="{{ route('login') }}" class="login-link">войдите в систему</a>.</p>
                </div>
            @endauth
        </div>

    </div>
    {{-- // Инициализация CKEditor --}}
    <link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
    <script src="{{ asset('js/ckeditor.js') }}"></script>
@endsection
