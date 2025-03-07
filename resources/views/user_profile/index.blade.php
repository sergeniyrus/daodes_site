@extends('template')

@section('title_page', 'User profile')

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

    .button-container {
        display: flex;
        justify-content: center; /* Центрирование кнопок */
        margin-top: 20px; /* Отступ сверху */
    }
</style>

<div class="container">
    <div class="text-center mb-4">
        <h1>User Profile</h1>
    </div>
    <!-- Display session message if it exists -->
    @if(session('info'))
    <div class="alert alert-info" style="text-align: center">{{ session('info') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger" style="text-align: center">{{ session('error') }}</div>
    @endif

    @if($userProfile)
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Avatar and username in one block -->
                <div class="profile-header">
                    <h2 class="user-name">{{ $userProfile->user->name ?? 'No Name' }}</h2>
                    <img src="{{ $userProfile->avatar_url }}" alt="Avatar" class="avatar-img">
                </div>

                <!-- Basic Information -->
                <div class="category-block">
                    <div class="section-title">Basic Information</div>
                    <p class="card-text"><label>Role:</label> {{ $userProfile->role ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Telegram Nickname:</label> <a href="https://t.me/{{ $userProfile->nickname ?? 'Not specified' }}" target="_blank" style="color:aqua">
                        {{ $userProfile->nickname ?? 'Not specified' }}
                    </a></p>
                    <p class="card-text"><label>Gender:</label> {{ $userProfile->gender ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Date of Birth:</label> {{ $userProfile->birth_date ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Timezone:</label> {{ $userProfile->timezone ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Languages:</label> {{ $userProfile->languages ?? 'Not specified' }}</p>
                </div>

                <!-- Education and Skills -->
                <div class="category-block">
                    <div class="section-title">Education and Skills</div>
                    <p class="card-text"><label>Education:</label> {{ $userProfile->education ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Specialization:</label> {{ $userProfile->specialization ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Resume:</label> {{ $userProfile->resume ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Portfolio:</label> {{ $userProfile->portfolio ?? 'Not specified' }}</p>
                </div>

                <!-- Activity and Achievements -->
                <div class="category-block">
                    <div class="section-title">Activity and Achievements</div>
                    <p class="card-text"><label>Trust Level:</label> {{ $userProfile->trust_level ?? 'Not specified' }}</p>
                    <p class="card-text"><label>SBT Tokens:</label> {{ $userProfile->sbt_tokens ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Tasks Completed:</label> {{ $userProfile->tasks_completed ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Tasks Failed:</label> {{ $userProfile->tasks_failed ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Recommendations:</label> {{ $userProfile->recommendations ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Activity Log:</label> {{ $userProfile->activity_log ?? 'Not specified' }}</p>
                    <p class="card-text"><label>Achievements:</label> {{ $userProfile->achievements ?? 'Not specified' }}</p>
                </div>

                <!-- Кнопка в зависимости от того, чей это профиль -->
                <div class="button-container">
                    @if($userProfile->user_id == auth()->id())
                        <!-- Если это профиль текущего пользователя, показываем кнопку "Редактировать" -->
                        <a href="{{ route('user_profile.edit', $userProfile->user_id) }}" class="btn blue_btn">Edit</a>
                    @else
                        <!-- Если это чужой профиль, показываем кнопку "Написать в чат" -->
                        <form method="POST" action="{{ route('chats.createOrOpen', $userProfile->user_id) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn blue_btn">Write to the chat</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="text-center">
            <p>No profile available to display.</p>
        </div>
    @endif
</div>
@endsection