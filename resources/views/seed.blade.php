{{-- //страница создания сид фразы --}}
@extends('template')
@section('title_page')
    SeedPhraze
@endsection
@section('main')
<main>
    {{-- <?php
    $onseed = DB::table('seed')
            ->where('user_id', $userId)
            ->value('keyword');

            ?> --}}
<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center">
        Ваша Сид-Фраза<br>
        обязательно её сохраните<br><br>
                {{-- //генерируем сид-фразу --}}
                <?php
                $n = 23;
                $lines = file('../public/base.txt');
                shuffle($lines);
                $value = [];
                $value = array_rand($lines, $n);
                
                foreach ($value as $line_num => $line) {
                }
                
                $seed = $lines[$value[0]] . $lines[$value[1]] . $lines[$value[2]] . $lines[$value[3]] . $lines[$value[4]] . $lines[$value[5]] . $lines[$value[6]] . $lines[$value[7]] . $lines[$value[8]] . $lines[$value[9]] . $lines[$value[10]] . $lines[$value[11]] . $lines[$value[12]] . $lines[$value[13]] . $lines[$value[14]] . $lines[$value[15]] . $lines[$value[16]] . $lines[$value[17]] . $lines[$value[18]] . $lines[$value[19]] . $lines[$value[20]] . $lines[$value[21]] . $lines[$value[22]] . $keyword;
                ?>
                <form method="post" action="{{ route('saveseed') }}">
                    @csrf
        <div class="tabseed">
                <textarea name="seed_text" rows="24"><?php echo $seed; ?>        
                </textarea>
        </div>
        <?php for($i = 0; $i < 23; $i++): ?>
        <input type="hidden" name="word<?php echo $i; ?>" value="<?php echo $lines[$value[$i]]; ?>">
    <?php endfor; ?>  
    <input type="hidden" name="word23" value="<?php echo $keyword; ?>">    
        <br>
        <button type="submit">Сохранить</button>
        
</form>
                {{-- <button onclick="copytext('#myseed')"> Копировать в буфер </button> --}}
    </div>
</div>
</x-app-layout>
    </main>
@endsection
