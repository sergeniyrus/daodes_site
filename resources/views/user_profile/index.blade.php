@extends('template')
@section('title_page', __('user_profile.title_page'))
@section('main')
    <style>
        .container {
            padding: 15px;
            margin: 0 auto;
            max-width: 800px;
            background-color: rgba(30, 32, 30, 0.753);
            border-radius: 15px;
            border: 1px solid #d7fc09;
            color: #f8f9fa;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin-top: 30px;
        }

        .card {
            border-radius: 10px;
            border: 1px solid #f8f9fa;
            background-color: #2b2c2e;
            margin-bottom: 20px;
            padding: 15px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            margin-bottom: 15px;
        }

        .profile-header .avatar-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #d7fc09;
        }

        .user-name {
            font-size: 1.5rem;
            color: #d7fc09;
            flex: 1;
        }

        .section-title {
            font-size: 1.2rem;
            color: #d7fc09;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #d7fc09;
            padding-bottom: 5px;
            text-align: center;
        }

        .card-text {
            font-size: 1rem;
            margin-bottom: 8px;
        }

        .card-text label {
            font-weight: bold;
            color: #d7fc09;
        }

        .category-block {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #343a40;
            border-radius: 8px;
        }

        .btn.des-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1rem;
            background: #0b0c18;
            padding: 10px 20px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn.des-btn:hover {
            box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
            transform: scale(1.05);
            background: #0b0c18;
        }

        .button-container {
            display: flex;
            justify-content: center;
            /* Центрирование кнопок */
            margin-top: 20px;
            /* Отступ сверху */
        }
    </style>

    <div class="container">
        <div class="text-center mb-4">
            <h1>{{ __('user_profile.user_profile') }}</h1>
        </div>

        @if ($userProfile)
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Avatar and username in one block -->
                    <div class="profile-header">
                        <h2 class="user-name">{{ $userProfile->user->name ?? __('user_profile.not_specified') }}</h2>
                        <img src="{{ $userProfile->avatar_url }}" alt="Avatar" class="avatar-img">
                    </div>

                    <!-- Basic Information -->
                    <div class="category-block">
                        <div class="section-title">{{ __('user_profile.basic_information') }}</div>
                        <p class="card-text"><label>{{ __('user_profile.role') }}:</label>
                            {{ $userProfile->role ?? __('user_profile.not_specified') }}</p>
                            
                        <p class="card-text">
                            <label>{{ __('user_profile.telegram_nickname') }}:</label>
                            @if ($userProfile->nickname)
                                <a href="https://t.me/{{ $userProfile->nickname }}" target="_blank" style="color:aqua">
                                    {{ $userProfile->nickname }}
                                </a>
                            @else
                                <span style="color: #6c757d;">{{ __('user_profile.not_specified') }}</span>
                            @endif
                        </p>

                        <p class="card-text"><label>{{ __('user_profile.gender') }}:</label>
                            {{ $userProfile->gender ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.date_of_birth') }}:</label>
                            {{ $userProfile->birth_date ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.timezone') }}:</label>
                            {{ $userProfile->timezone ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.languages') }}:</label>
                            {{ $userProfile->languages ?? __('user_profile.not_specified') }}</p>
                    </div>

                    <!-- Education and Skills -->
                    <div class="category-block">
                        <div class="section-title">{{ __('user_profile.education_and_skills') }}</div>
                        <p class="card-text"><label>{{ __('user_profile.education') }}:</label>
                            {{ $userProfile->education ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.specialization') }}:</label>
                            {{ $userProfile->specialization ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.resume') }}:</label>
                            {{ $userProfile->resume ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.portfolio') }}:</label>
                            {{ $userProfile->portfolio ?? __('user_profile.not_specified') }}</p>
                    </div>

                    <!-- Activity and Achievements -->
                    <div class="category-block">
                        <div class="section-title">{{ __('user_profile.activity_and_achievements') }}</div>
                        <p class="card-text"><label>{{ __('user_profile.trust_level') }}:</label>
                            {{ $userProfile->trust_level ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.sbt_tokens') }}:</label>
                            {{ $userProfile->sbt_tokens ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.tasks_completed') }}:</label>
                            {{ $userProfile->tasks_completed ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.tasks_failed') }}:</label>
                            {{ $userProfile->tasks_failed ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.recommendations') }}:</label>
                            {{ $userProfile->recommendations ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.activity_log') }}:</label>
                            {{ $userProfile->activity_log ?? __('user_profile.not_specified') }}</p>
                        <p class="card-text"><label>{{ __('user_profile.achievements') }}:</label>
                            {{ $userProfile->achievements ?? __('user_profile.not_specified') }}</p>
                    </div>

                    <!-- Кнопка в зависимости от того, чей это профиль -->
                    <div class="button-container">
                        @if ($userProfile->user_id == auth()->id())
                            <!-- Если это профиль текущего пользователя, показываем кнопку "Редактировать" -->
                            <a href="{{ route('user_profile.edit', $userProfile->user_id) }}"
                                class="des-btn">{{ __('user_profile.edit') }}</a>
                        @else
                            <!-- Если это чужой профиль, показываем кнопку "Написать в чат" -->
                            <form method="POST" action="{{ route('chats.createOrOpen', $userProfile->user_id) }}"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="des-btn">{{ __('user_profile.write_to_chat') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="text-center">
                <p>{{ __('user_profile.no_profile_available') }}</p>
            </div>
        @endif
    </div>
@endsection
