@extends('template')

@section('title_page')
    {{ __('news.news_item_title', ['id' => $news->id]) }}
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
                    <p class="task-category">{!! __('news.category_label') !!} {{ $categoryName }}</p>
                </div>
                <div class="task-info2">
                    <p class="task-data">{!! __('news.date_label') !!}
                        {{ \Carbon\Carbon::parse($news->created_at)->format('d/m/y') }}</p>
                    <p class="task-views">{!! __('news.views_label') !!} {{ $news->views }}</p>
                    <p class="task-comment">{!! __('news.comments_label') !!} {{ $commentCount }}</p>
                </div>
                @auth
                    @if (Auth::user()->access_level >= 3)
                        <div class="admin_menu">
                            <div>
                                <a href="{{ route('news.create') }}" class="des-btn" title="Create">
                                    {!! __('news.create_button') !!}
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('news.edit', ['id' => $news->id]) }}" class="des-btn" title="Edit">
                                    {!! __('news.edit_button') !!}
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('news.destroy', ['id' => $news->id]) }}" class="des-btn"
                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $news->id }}').submit();">
                                    {!! __('news.delete_button') !!}
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

        <div class="news-content ">
            <div class="card">
                <div class="news-text">{!! $news->content !!}</div>
            </div>
        </div>

        <div class="comment">
            <div class="z_com">
                <h6>{{ __('news.discussions', ['count' => $commentCount]) }}</h6>
            </div>
        
            @if ($commentCount == 0)
                <div class="eror_com">{{ __('news.be_first') }}</div>
            @else
                @foreach ($comments as $comment)
                    <div class="post_com">
                        <div class="name_com">
                            {{ e(DB::table('users')->where('id', $comment->user_id)->value('name')) }}
                            <div class="date_com">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.y at H:i') }}</div>
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
                            <legend class="z_com">{{ __('news.write_message') }}</legend>
                            <textarea id="editor" name="text" cols="80" rows="10" placeholder="{{ __('news.your_opinion') }}"></textarea>
                            <input type="hidden" name="news_id" value="{{ e($news->id) }}" />
                        </fieldset>
                        <br>
                        <div style="text-align: center">
                            <button type="submit" class="des-btn">
                                {!! __('news.save_button') !!}
                            </button>
                        </div>
                        <br>
                    </form>
                </div>
            @else
                <div class="login_prompt">
                    {!! __('news.login_prompt', ['link' => route('login')]) !!}
                </div>
            @endauth
        </div>
    </div>

    {{-- // Initialize CKEditor --}}
    <link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
    <script src="{{ asset('js/ckeditor.js') }}"></script>
@endsection