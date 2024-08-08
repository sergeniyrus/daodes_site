<div class="left_box">
    @auth
        <?php
      $user = Auth::user();
      $user_id = $user->id;
  
      $id_offer = $id_offer ?? request()->route('id'); // Получаем id предложения из маршрута, если переменная не передана
  
      $hasVoted = DB::table('vote_users')
                    ->where('id_offer', $id_offer)
                    ->where('id_user', $user_id)
                    ->exists();

    $startVoteTime = DB::table('offers')
        ->select('start_vote')
        ->where('id', $id_offer)
        ->first();
        $startVoteTime = $startVoteTime->start_vote;
  
      if (!$hasVoted) {
      ?>
        <div class="vote_box">
            <form action="{{ route('vote.store') }}" class="form-vote" method="post">
                @csrf
                <fieldset class="tbr">
                    <legend>
                        <h4>Голосовать</h4>
                    </legend>
                    <div class="vote_ratio">
                        <label for="choice1" class="img_vote">
                            <input type="radio" id="choice1" class="choice1" name="vote" value="1" class="ratio_l">
                            <img class="img_bt circle" src="{{ asset('/img/icons_post/yes.png') }}" alt="">
                        </label>
                        <label for="choice2" class="img_vote">
                            <input type="radio" id="choice2" class="choice2" name="vote" value="2" class="ratio_r">
                            <img class="img_bt circle" src="{{ asset('/img/icons_post/no.png') }}" alt="">
                        </label>
                    </div>
                    <input type="hidden" name="offer_id" value="{{ $id_offer }}">
                    <input type="image" src="{{ asset('/img/icons_post/voting.png') }}" class="img_bt" title="Голосовать">
                </fieldset>
            </form>
        </div>
        <?php
    } 
    else {
        echo "<div class='msg'>Ваше мнение учтено</div>";
// Проверяем, что переменная $id_offer определена и производим расчёты результатов
if (isset($id_offer)) {
        $totalUsers = DB::table('users')->count();
        $totalUsers -= 2;
    
        $counts = DB::table('vote_users')->select(DB::raw('vote, COUNT(*) as count'))->where('id_offer', $id_offer)->groupBy('vote')->get();
    
        $za = 0;
        $no = 0;
    
        foreach ($counts as $count) {
            switch ($count->vote) {
                case 1:
                    $za = $count->count;
                    break;
                case 2:
                    $no = $count->count;
                    break;
            }
        }
    
        $totalVotes = $za + $no;
        $vozd = $totalUsers - $totalVotes;
    
        $za_percentage = $totalUsers > 0 ? ($za * 100) / $totalUsers : 0;
        $no_percentage = $totalUsers > 0 ? ($no * 100) / $totalUsers : 0;
        $vozd_percentage = $totalUsers > 0 ? ($vozd * 100) / $totalUsers : 0;
    
        $za_percentage = round($za_percentage, 2);
        $no_percentage = round($no_percentage, 2);
        $vozd_percentage = round($vozd_percentage, 2);
    }

    

    ?>

    <br>
    <fieldset class="tbr">
        <legend>
            <h4>Результаты</h4>
        </legend>
        <table class="tbrv">
            <tr>
                <td>За:</td>
                <td class="right_td">{{ $za ?? 0 }}</td>
            </tr>
            <tr>
                <td>
                    <div class="graf">
                        <div style="width:{{ $za_percentage ?? 0 }}%; background-color: green; height: 14px;">
                            <span>&nbsp;</span></div>
                    </div>
                </td>
                <td class="right_td"> {{ $za_percentage ?? 0 }}%</td>
            </tr>
            <tr>
                <td>Против:</td>
                <td class="right_td">{{ $no ?? 0 }}</td>
            </tr>
            <tr>
                <td>
                    <div class="graf">
                        <div style="width:{{ $no_percentage ?? 0 }}%; background-color: red; height: 14px;">
                            <span>&nbsp;</span></div>
                    </div>
                </td>
                <td class="right_td"> {{ $no_percentage ?? 0 }}%</td>
            </tr>
            <tr>
                <td>Не голосовали:</td>
                <td class="right_td">{{ $vozd ?? 0 }}</td>
            </tr>
            <tr>
                <td>
                    <div class="graf">
                        <div style="width:{{ $vozd_percentage ?? 0 }}%; background-color: #555; height: 14px;">
                            <span>&nbsp;</span></div>
                    </div>
                </td>
                <td class="right_td"> {{ $vozd_percentage ?? 0 }}%</td>
            </tr>
        </table>
    </fieldset>
    
    <?php
    }
    ?>
    @else
        <div class="msg">Чтоб голосовать необходимо <a href="/login" class="eror_com">войти</a> </div>
    @endauth

    <div class="msg">
        <h6>До конца голосования осталось:</h6>
        <div id="remaining-time" class="time-remaining"></div><br>
    </div>


    <script>
        // Получаем время начала голосования из PHP
        const startVoteTime = "{{ $startVoteTime }}";
        const intervalHours = 72; // Количество часов для обратного отсчета

        function updateTime() {
            const now = new Date();
            const voteStartDate = new Date(startVoteTime);
            const endDate = new Date(voteStartDate.getTime() + intervalHours * 60 * 60 * 1000);
            const timeDiff = endDate - now;

            if (timeDiff <= 0) {
                document.getElementById('remaining-time').innerHTML = 'Voting period has ended!';
                return;
            }

            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

            document.getElementById('remaining-time').innerHTML = `${hours}h ${minutes}m ${seconds}s`;
        }

        // Обновляем время каждую секунду
        setInterval(updateTime, 1000);
    </script>
</div>
