@extends('template')

@section('title_page', 'Профиль пользователя')

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

    .btn.blue_btn {
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

    .btn.blue_btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        background: #0b0c18;
    }
</style>

<div class="container">
    <div class="text-center mb-4">
        <h1>Профиль пользователя</h1>
    </div>
<!-- Вывод сообщения из сессии, если оно есть -->
@if(session('info'))
<div class="alert alert-info" style="text-align: center">{{ session('info') }}</div>
@elseif(session('error'))
<div class="alert alert-danger" style="text-align: center">{{ session('error') }}</div>
@endif

    @if($userProfile)
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Аватар и имя пользователя в одном блоке -->
                <div class="profile-header">
                    <h2 class="user-name">{{ $userProfile->user->name ?? 'Без имени' }}</h2>
                    <img src="{{ $userProfile->avatar_url }}" alt="Аватар" class="avatar-img">
                </div>

                <!-- Основная информация -->
                <div class="category-block">
                    <div class="section-title">Основная информация</div>
                    <p class="card-text"><label>Роль:</label> {{ $userProfile->role ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Никнейм Telegramm:</label> {{ $userProfile->nickname ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Пол:</label> {{ $userProfile->gender ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Дата рождения:</label> {{ $userProfile->birth_date ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Часовой пояс:</label> {{ $userProfile->timezone ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Языки:</label> {{ $userProfile->languages ?? 'Не указано' }}</p>
                </div>

                <!-- Образование и навыки -->
                <div class="category-block">
                    <div class="section-title">Образование и навыки</div>
                    <p class="card-text"><label>Образование:</label> {{ $userProfile->education ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Специализация:</label> {{ $userProfile->specialization ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Резюме:</label> {{ $userProfile->resume ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Портфолио:</label> {{ $userProfile->portfolio ?? 'Не указано' }}</p>
                </div>

                <!-- Активность и достижения -->
                <div class="category-block">
                    <div class="section-title">Активность и достижения</div>
                    {{-- <p class="card-text"><label>Адрес кошелька:</label> {{ $userProfile->wallet_address ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Рейтинг:</label> {{ $userProfile->rating ?? 'Не указано' }}</p> --}}
                    <p class="card-text"><label>Уровень доверия:</label> {{ $userProfile->trust_level ?? 'Не указано' }}</p>
                    <p class="card-text"><label>SBT-токены:</label> {{ $userProfile->sbt_tokens ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Задачи выполнены:</label> {{ $userProfile->tasks_completed ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Задачи провалены:</label> {{ $userProfile->tasks_failed ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Рекомендации:</label> {{ $userProfile->recommendations ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Лог активности:</label> {{ $userProfile->activity_log ?? 'Не указано' }}</p>
                    <p class="card-text"><label>Достижения:</label> {{ $userProfile->achievements ?? 'Не указано' }}</p>
                </div>

                <!-- Кнопка для перехода к редактированию профиля -->
                <a href="{{ route('user_profile.edit', $userProfile->user_id) }}" class="btn blue_btn">Редактировать</a>
            </div>
        </div>
    @else
        <div class="text-center">
            <p>Нет доступного профиля для отображения.</p>
        </div>
    @endif
</div>
@endsection
