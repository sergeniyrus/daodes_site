@extends('template')

@section('title_page')
    {{ __('offers.suggestion_title', ['id' => $offers->id]) }}
@endsection

@section('main')
    <link rel="stylesheet" href="{{ asset('css/offers.css') }}">
    <div class="container my-5">
        <div class="task-title">
            <h5>{{ app()->getLocale() === 'ru' ? $offers->title_ru : $offers->title_en }}</h5>

        </div>
        <div class="offers-header">
            <div class="img_post">
                <img src="{{ $offers->img }}" alt="Image Offers" />
            </div>

            <div class="rows-title">
                <div class="task-info">
                    <p class="task-category">{!! __('offers.category_label') !!} {{ $categoryName }}</p>
                </div>
                <div class="task-info2">
                    <p class="task-data">{!! __('offers.date_label') !!}
                        {{ \Carbon\Carbon::parse($offers->created_at)->format('d/m/y') }}</p>
                    <p class="task-views">{!! __('offers.views_label') !!} {{ $offers->views }}</p>
                    <p class="task-comment">{!! __('offers.comments_label') !!} {{ $commentCount }}</p>
                </div>
                @auth
                    @if (Auth::user()->access_level >= 3)
                        <div class="admin_menu">
                            <div>
                                <a href="{{ route('offers.create') }}" class="des-btn" title="Create">
                                    {!! __('offers.create_button') !!}
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('offers.edit', ['id' => $offers->id]) }}" class="des-btn" title="Edit">
                                    {!! __('offers.edit_button') !!}
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('offers.destroy', ['id' => $offers->id]) }}" class="des-btn"
                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $offers->id }}').submit();">
                                    {!! __('offers.delete_button') !!}
                                </a>
                                <form id="delete-form-{{ $offers->id }}"
                                    action="{{ route('offers.destroy', ['id' => $offers->id]) }}" method="POST"
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

        <div class="offers-content card">
            <p>{!! app()->getLocale() === 'ru' ? $offers->content_ru : $offers->content_en !!}</p>

        </div>

        <div class="offers-content card">
            @if ($offers->state == null || $offers->state == 0)
                @include('offers.spam')
            @elseif ($offers->state == 1)
                @include('offers.discussion')
            @elseif ($offers->state == 2)
                @include('offers.vote')
            @elseif ($offers->state == 3)
                @include('offers.robot')
            @elseif ($offers->state == 4)
                @include('offers.executed')
            @elseif ($offers->state == 5)
                @include('offers.rejected')
            @endif
        </div>

        <div class="comment">
            <div class="z_com">
                <h6>{{ __('offers.discussions', ['count' => $commentCount]) }}</h6>
            </div>

            @if ($commentCount == 0)
                <div class="eror_com">{{ __('offers.be_first') }}</div>
            @else
                @foreach ($comments as $comment)
                    <div class="post_com">
                        <div class="name_com">
                            {{ DB::table('users')->where('id', $comment->user_id)->value('name') }}
                            <div class="date_com">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.y at H:i') }}</div>
                            <div class="text_com">{!! $comment->text !!}</div>
                        </div>
                    </div>
                @endforeach
            @endif

            @auth
                <div class="form_com">
                    <form name="comment" action="{{ route('comments.offers') }}" method="post">
                        @csrf
                        <fieldset>
                            <legend class="z_com">{{ __('offers.write_message') }}</legend>
                            <textarea id="editor" name="text" cols="80" rows="10" placeholder="{{ __('offers.your_opinion') }}"></textarea>
                            <input type="hidden" name="offer_id" value="{{ $offers->id }}" />
                        </fieldset><br>
                        <div style="text-align: center">
                            <button type="submit" class="des-btn">
                                {!! __('offers.save_button') !!}
                            </button>
                        </div><br>
                    </form>
                </div>
            @else
                <div class="login_prompt">
                    {!! __('offers.login_prompt', ['link' => route('login')]) !!}
                </div>
            @endauth
        </div>
    </div>

    {{-- // Initialize CKEditor --}}
    <link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
    <script src="{{ asset('js/ckeditor.js') }}"></script>
@endsection