<div class="left_box">
    @auth
        <?php
        $user = Auth::user();
        $user_id = $user->id;
        
        $offer_id = $offer_id ?? request()->route('id'); // Получаем id предложения из маршрута, если переменная не передана
        
        $hasVoted = DB::table('spam')->where('offer_id', $offer_id)->where('user_id', $user_id)->exists();
        ?>

        @if (!$hasVoted)
            <div class="vote_box">
                <form action="{{ route('spam.store') }}" class="form-vote" method="post">
                    @csrf
                    <fieldset class="tbr">
                        <legend>
                            <h4 style="text-align: center">Спам/не спам</h4>
                        </legend>
                        <div class="vote_ratio">
                            <label for="choice1" class="img_vote">
                                <input type="radio" id="choice1" class="choice1" name="vote" value="1"
                                    class="ratio_l">
                                <img class="img_bt_l" src="{{ asset('/img/icons_post/spam.png') }}" alt="">
                            </label>
                            <label for="choice2" class="img_vote">
                                <input type="radio" id="choice2" class="choice2" name="vote" value="0"
                                    class="ratio_r">
                                <img class="img_bt_r" src="{{ asset('/img/icons_post/nospam.png') }}" alt="">
                            </label>
                        </div>
                        <div class="btn_vote">
                            <input type="hidden" name="offer_id" value="{{ $offer_id }}">
                            <input type="image" src="{{ asset('/img/icons_post/voting.png') }}" class="img_bt"
                                title="Голосовать">
                        </div>
                    </fieldset>
                </form>
            </div>
        @else
            <div class='msg'>Ваше мнение учтено</div>
            <?php
            // Проверяем, что переменная $offer_id определена
            if (isset($offer_id)) {
                $totalUsers = DB::table('users')->count();
                $totalUsers -= 2;
            
                $counts = DB::table('spam')->select(DB::raw('vote, COUNT(*) as count'))->where('offer_id', $offer_id)->groupBy('vote')->get();
            
                $yes = 0;
                $no = 0;
            
                foreach ($counts as $count) {
                    switch ($count->vote) {
                        case 0:
                            $no = $count->count;
                            break;
                        case 1:
                            $yes = $count->count;
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
                        <td>Спам:</td>
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
                        <td>Не спам:</td>
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
            {{-- проверка условий спам не спам --}}
            <?php if ($za_percentage >= 25) {
                // находим гада
                $authorId = DB::table('users')
                    ->where('id', $text->user_id)
                    ->value('id');
            
                // {{-- // Удаление предложения --}}
                DB::table('offers')->where('id', $offer_id)->delete();
                // Добавляем автора в спам список спамеров
                DB::table('spamers')->insert([
                    'user_id' => $authorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            
                echo 'Пост удалён как спам';
            }
            if ($no_percentage >= 25) {
                DB::table('offers')
                    ->where('id', $offer_id)
                    ->update(['state' => 1]);
                echo 'Проверка пройдена';
            }
            ?>
        @endif
    @else
        <div class="msg">Необходимо <a href="/login" class="eror_com">войти</a></div>
    @endauth



</div>
