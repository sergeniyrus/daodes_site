@extends('template')
@section('title_page')
    Team
@endsection
@section('main')
<main>
  <div class="team-section" style="background-color: #1a1a1a; color: #ffffff; padding: 50px;">
      <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 40px;">Наша команда</h2>
      <div class="team-members" style="display: flex; justify-content: space-around; flex-wrap: wrap;">
          @php
              $teamMembers = [
                  [
                      'photo' => 'img/main/img_avatar.jpg',
                      'name' => 'Иван Иванов',
                      'description' => 'Frontend разработчик',
                      'contact' => 'ivan@example.com'
                  ],
                  [
                      'photo' => 'img/main/img_avatar.jpg',
                      'name' => 'Мария Петрова',
                      'description' => 'Дизайнер интерфейсов',
                      'contact' => 'maria@example.com'
                  ],
                  [
                      'photo' => 'img/main/img_avatar.jpg',
                      'name' => 'Алексей Смирнов',
                      'description' => 'Backend разработчик',
                      'contact' => 'alexey@example.com'
                  ],
                  [
                      'photo' => 'img/main/img_avatar.jpg',
                      'name' => 'Екатерина Волкова',
                      'description' => 'Менеджер проектов',
                      'contact' => 'ekaterina@example.com'
                  ],
                  [
                      'photo' => 'img/main/img_avatar.jpg',
                      'name' => 'Дмитрий Кузнецов',
                      'description' => 'Тестировщик',
                      'contact' => 'dmitry@example.com'
                  ]
              ];
          @endphp

          @foreach($teamMembers as $member)
              <div class="team-member" style="text-align: center; margin: 20px; max-width: 250px;">
                  <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                  <h3 style="font-size: 1.5rem; margin: 15px 0 10px;">{{ $member['name'] }}</h3>
                  <p style="font-size: 1rem; margin-bottom: 10px;">{{ $member['description'] }}</p>
                  <p style="font-size: 0.9rem; color: #cccccc;">{{ $member['contact'] }}</p>
              </div>
          @endforeach
      </div>
  </div>
</main>
@endsection
