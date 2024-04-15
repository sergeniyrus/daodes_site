@extends('template')
@section('title_page')
Предложения
@endsection
@section('main')
<div class="main-content">
    <!-- информационные окна коротких событий -->
@php
    $off_0 = 0;
    $off_1 = 0;
    $off_2 = 0;
    $off_3 = 0;
    $off_4 = 0;
    $off_5 = 0;
@endphp
@if ($offers->count())
    @foreach($offers as $offer)
        @if ($offer->state == NULL || $offer->state == 0)
            @php $off_0++; $off_title_0 ="На модерации"; @endphp
        @elseif ($offer->state == 1)
            @php $off_1++; $off_title_1 ="В обсуждении"; @endphp
        @elseif ($offer->state == 2)
            @php $off_2++; $off_title_2 ="На голосовании"; @endphp 
        @elseif ($offer->state == 3)
            @php $off_3++;  $off_title_3 ="В работе"; @endphp 
        @elseif ($offer->state == 4)
            @php $off_4++;  $off_title_4 ="Исполненные"; @endphp 
        @elseif ($offer->state == 5)
            @php $off_5++; $off_title_5 ="Отклонённые"; @endphp
        @endif
    @endforeach
@endif
@for ($i = 0; $i <= 5; $i++)    
<div class="windows">
    <div class="windows-title">
        <h2>
<a href="#state<?php echo $i; ?>" id="state<?php echo $i; ?>" onclick="myFunction<?php echo $i;?>()">
    <?php if (($off_0 > 0) && ($i == 0)) { echo $off_title_0 . ": " . $off_0;} ?>
    <?php if (($off_1 > 0) && ($i == 1)) { echo $off_title_1 . ": " . $off_1;} ?>
    <?php if (($off_2 > 0) && ($i == 2)) { echo $off_title_2 . ": " . $off_2;} ?>
    <?php if (($off_3 > 0) && ($i == 3)) { echo $off_title_3 . ": " . $off_3;} ?>
    <?php if (($off_4 > 0) && ($i == 4)) { echo $off_title_4 . ": " . $off_4;} ?>
    <?php if (($off_5 > 0) && ($i == 5)) { echo $off_title_5 . ": " . $off_5;} ?>
</a>
    </h2>
</div>
<div id="details<?php echo $i;?>">  
    @foreach ($offers as $offer)
        @if ($offer->state == $i)            
            @include('partials.offers', ['offer' => $offer])            
        @endif
    @endforeach
</div></div>
@endfor
</div>
@endsection