@extends('template')
@section('title_page')
    Предложения
@endsection
@section('main')
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
                        @include('partials.offers', ['offer' => $offer])
                    @endif
                @endforeach
            </div>
        </div>
    @endif
@endforeach
<script>
  // раскрытие блоков предложений

function myFunction0() {
var b = document.getElementById("details0");
  if (b.style.display === 'block')
    b.style.display = 'none';
  else
    b.style.display = 'block'
}

function myFunction1() {
var b = document.getElementById("details1");
  if (b.style.display === 'block')
    b.style.display = 'none';
  else
    b.style.display = 'block'
}

function myFunction2() {
var b = document.getElementById("details2");
  if (b.style.display === 'block')
    b.style.display = 'none';
  else
    b.style.display = 'block'
}

function myFunction3() {
var b = document.getElementById("details3");
  if (b.style.display === 'block')
    b.style.display = 'none';
  else
    b.style.display = 'block'
}

function myFunction4() {
var b = document.getElementById("details4");
  if (b.style.display === 'block')
    b.style.display = 'none';
  else
    b.style.display = 'block'
}

function myFunction5() {
var b = document.getElementById("details5");
  if (b.style.display === 'block')
    b.style.display = 'none';
  else
    b.style.display = 'block'
}

function myFunction6() {
var b = document.getElementById("details6");
  if (b.style.display === 'block')
    b.style.display = 'none';
  else
    b.style.display = 'block'
}
</script>
@endsection
