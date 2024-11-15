@extends('template')
@section('title_page')
    Предложения
@endsection
@section('main')
<link rel="stylesheet" href="{{ asset('css/offers.css') }}">
    <div class="main-content">
        <!-- информационные окна коротких событий -->
        @php
    // Инициализация массивов для хранения количества и заголовков для каждого состояния
    $states = [
        0 => ['count' => 0, 'title' => 'На модерации'],
        1 => ['count' => 0, 'title' => 'В обсуждении'],
        2 => ['count' => 0, 'title' => 'На голосовании'],
        3 => ['count' => 0, 'title' => 'В работе'],
        4 => ['count' => 0, 'title' => 'Исполненные'],
        5 => ['count' => 0, 'title' => 'Отклонённые'],
    ];
@endphp

@if ($offers->count())
    @foreach ($offers as $offer)
        @php
            $state = $offer->state ?? 0; // Если state равно null, то считаем его как 0
            $states[$state]['count']++;
        @endphp
    @endforeach
@endif

@foreach ($states as $i => $state)
    @if ($state['count'] > 0)
        <div class="windows">
            <div class="windows-title">
                <a href="#state{{ $i }}" id="state{{ $i }}" onclick="myFunction{{ $i }}()">
                    {{ $state['title'] }}: {{ $state['count'] }}
                </a>
            </div>
            <div id="details{{ $i }}">
                @foreach ($offers as $offer)
                    @if ($offer->state == $i)
                    <div class="windows-new">
                      <div class="windows-new-images">
                          <img src="<?php echo $offer->img; ?>" alt="" />
                      </div>
                      <div class="windows-string">
                          <a href="/offers/<?php echo $offer->id; ?>">
                              <div class="windows-new-title">
                                  <?php echo $offer->title; ?>
                              </div>
                          </a>
                          <div class="windows-new-text">
                              <p>
                                  <?php echo html_entity_decode(substr($offer->content, 0, 500), ENT_QUOTES, 'utf-8'); ?>
                              </p>
                          </div>
                      </div>
                  </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
@endforeach
<script src="{{ asset('js/block.js') }}"></script>

@endsection
