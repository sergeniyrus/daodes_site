<div class="left_box">
    @auth
        <?php
        $user = Auth::user();
        $user_id = $user->id;
        
        // Получаем id предложения из маршрута, если переменная не передана
        $offer_id = $offer_id ?? request()->route('id');
        
        // Проверяем, оставил ли пользователь комментарий к этому предложению
        $hasCommented = DB::table('comments_offers')->where('offer_id', $offer_id)->where('user_id', $user_id)->exists();
        
        // Проверяем, голосовал ли пользователь за это предложение
        $hasVoted = DB::table('discussions')->where('offer_id', $offer_id)->where('user_id', $user_id)->exists();
        ?>

        @if (!$hasVoted)
            @if ($hasCommented)
                <div class="vote-box">
                    <form action="{{ route('discussion.store') }}" method="post">
                        @csrf
                        <fieldset class="tbr">
                            <legend>
                                <h4>Готов голосовать?</h4>
                            </legend>
                            <div class="vote_ratio">
                                <label for="choice1" class="img_vote">
                                    <input type="radio" id="choice1" class="choice1" name="vote" value="1"
                                        class="ratio_l">
                                    <img class="img_bt circle" src="{{ asset('/img/icons_post/yes.png') }}" alt="">
                                </label>
                                <label for="choice2" class="img_vote">
                                    <input type="radio" id="choice2" class="choice2" name="vote" value="0"
                                        class="ratio_r">
                                    <img class="img_bt circle" src="{{ asset('/img/icons_post/no.png') }}" alt="">
                                </label>
                            </div>
                            <input type="hidden" name="offer_id" value="{{ $offer_id }}">
                            <input type="image" src="{{ asset('/img/icons_post/voting.png') }}" class="img_bt"
                                title="Голосовать">
                        </fieldset>
                    </form>
                </div>
            @else
                <div class="msg">Вы ещё не высказали своё мнение в комментариях. Пожалуйста выскажитесь.</div>
            @endif
        @else
            <div class="msg">Ваше мнение учтено</div>


            <?php
            // Проверяем, что переменная $offer_id определена
            if (isset($offer_id)) {
                $totalUsers = DB::table('users')->count();
                $totalUsers -= 2;
            
                $counts = DB::table('discussions')->select(DB::raw('vote, COUNT(*) as count'))->where('offer_id', $offer_id)->groupBy('vote')->get();
            
                $yes = 0;
                $no = 0;
            
                foreach ($counts as $count) {
                    switch ($count->vote) {
                        case 0:
                            $yes = $count->count;
                            break;
                        case 1:
                            $no = $count->count;
                            break;
                    }
                }
            
                $totalVotes = $yes + $no;
                $vozd = $totalUsers - $totalVotes;
            
                $za_percentage = $totalUsers > 0 ? ($yes * 100) / $totalUsers : 0;
                $no_percentage = $totalUsers > 0 ? ($no * 100) / $totalUsers : 0;
                $vozd_percentage = $totalUsers > 0 ? ($vozd * 100) / $totalUsers : 0;
            
                $za_percentage = round($za_percentage, 2);
                $no_percentage = round($no_percentage, 2);
                $vozd_percentage = round($vozd_percentage, 2);
            }
            ?><fieldset class="tbr">
                <legend>
                    <h4>Результаты:</h4>
                </legend>
                <table class="tbrv">
                    <tr>
                        <td>Отклоняем:</td>
                        <td class="right_td">{{ $yes ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="graf">
                                <div style="width:{{ $za_percentage }}%; background-color: red; height: 14px;">
                                    <span>&nbsp;</span></div>
                            </div>
                        </td>
                        <td class="right_td"> {{ $za_percentage ?? 2 }}%</td>
                    </tr>
                    <tr>
                        <td>Голосуем:</td>
                        <td class="right_td">{{ $no }}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="graf">
                                <div style="width:{{ $no_percentage }}%; background-color: green; height: 14px;">
                                    <span>&nbsp;</span></div>
                            </div>
                        </td>
                        <td class="right_td"> {{ $no_percentage }}%</td>
                    </tr>
                    <tr>
                        <td>Не смотрели:</td>
                        <td class="right_td">{{ $vozd ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="graf">
                                <div style="width:{{ $vozd_percentage ?? 0 }}%; background-color: #555; height: 14px;">
                                    <span>&nbsp;</span>
                                </div>
                            </div>
                        </td>
                        <td class="right_td"> {{ $vozd_percentage ?? 0 }}%</td>
                    </tr>
                </table>
            </fieldset>
            {{-- проверка условий прохождения --}}
            <?php if ($za_percentage >= 25) {
                // {{-- // отправляем в отклонённые --}}
                DB::table('offers')
                    ->where('id', $offer_id)
                    ->update(['state' => 5]);
            
                echo 'Предложение отклонено по итогам обсуждений';
            }
            if ($no_percentage >= 25) {
                DB::table('offers')
                    ->where('id', $offer_id)
                    ->update([
                        'state' => 2,
                        'start_vote' => now(), // Или можно использовать now()
                    ]);
                echo 'Запускаем голосование!';
            }
            ?>
        @endif
    @else
        <div class="msg">Необходимо <a href="/login" class="eror_com">войти</a></div>
    @endauth
</div>
