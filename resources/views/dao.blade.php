@extends('template')
@section('title_page')
    Предложения
@endsection
@section('main')
<style>
.main-content {
  width: 100%;
  margin: 35px auto 10px auto;
  display: flex;
  justify-content: center;
  align-content: center; 
  flex-direction: column;
  align-items: center;
}

.windows {
  max-width: 80%;
  min-width: 380px;
  height: 100%;
  background-color: #1f234bce;
  border: 1px solid rgb(255, 255, 255);
  border-radius: 20px;
  margin:  0px auto 20px auto;
  padding: 0 10px 0 10px;
  display: flex;
  flex-direction: column;
  justify-content: center; /* Вертикальное выравнивание */
  align-items: center; /* Горизонтальное выравнивание */
}
.windows-title {
  text-align: center;
  margin: 10px;
  font-size: min(max(70%, 2vw), 100%);
  font-weight: 700;
}
#details0, #details1, #details2, #details3, #details4, #details5, #details6{
  display: none;
}
#state0, #state1, #state2, #state3, #state4, #state5 {
  font-size: min(max(70%, 2vw), 100%);
}
.windows-new {
  background: rgb(228, 225, 225);
  border-radius: 15px;
  width: 100%;
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.windows-string {
    display: block;
    text-align: center;
  }
  
.windows-new-title {
  margin: 5px;
  text-align: center;
  color: #fb048c;
  font-size: min(max(70%, 5vw), 90%);
  font-weight: 700;
}

.windows-new-images {
  margin: 10px;
  width: 80%;
  max-width: 300px;
  object-fit: cover;
}

.windows-new-text {
  margin: 10px;
  color: black;
  font-size: min(max(50%, 12vw), 80%);
  text-align: start;
  margin-bottom: 10px;
}
</style>
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
