<style>
    /* блок формы голосования */
    .left_box {
        width: 100%;
        font-size: min(max(50%, 1.5vw), 90%);
        color: aqua;
        justify-content: center;
        text-align: center;
    }

    .vote_box {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .form-vote {
        width: 100%;
    }

    /* Радио-кнопки с картинками */
    .img_vote {
        position: relative;
        margin: 0 10px;
    }

    .img_vote input[type="radio"] {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }

    .img_vote img {
        display: block;
        width: 100px;
        height: auto;
        border: 3px solid transparent;
        transition: border-color 0.3s;
    }

    .img_vote input[type="radio"]:checked+img {
        border-color: #f00;
        border-radius: 50%;
    }

    /* Таблица с результатами */
    .results-table {
        /* width: 100%; */
        border-collapse: collapse;
        margin: 20px auto;
        font-size: 1rem;
        color: gold;
    }

    .results-table td {
        padding: 10px;
        vertical-align: middle;
    }

    .results-table .text-right {
        text-align: right;
        font-weight: bold;
    }

    /* Прогресс-бары */
    .progress-bar {
        width: 100%;
        background-color: #f3f3f3;
        border: 1px solid gold;
        border-radius: 5px;
        overflow: hidden;
        height: 14px;
        margin: 5px 0;
    }

    .progress {
        height: 100%;
        text-align: center;
        line-height: 14px;
        color: transparent;
        transition: width 0.3s ease;
    }

    /* Общая стилизация */
    .result-container {
        width: 95%;
        margin: 20px auto;
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .text-center {
        text-align: center;
        font-size: 1.2rem;
        color: gold;
        margin-bottom: 10px;
    }
    .tbr fieldset {
    border: none;
    padding: 0;
    margin: 0;
    text-align: center;
    justify-content: center;
}

.vote_ratio {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
}
</style>

<div class="left_box">
    @auth
        <?php
        $user = Auth::user();
        $user_id = $user->id;

        $offer_id = $offer_id ?? request()->route('id'); // Получаем id предложения из маршрута, если переменная не передана

        $hasVoted = DB::table('offer_votes')
                    ->where('offer_id', $offer_id)
                    ->where('user_id', $user_id)
                    ->exists();

        $startVoteTime = DB::table('offers')
            ->select('start_vote')
            ->where('id', $offer_id)
            ->first();
        $startVoteTime = $startVoteTime->start_vote;

        if (!$hasVoted) {
        ?>
        <div class="vote_box">
            <form action="{{ route('vote.store') }}" class="form-vote" method="post">
                @csrf
                <fieldset class="tbr">
                    <h1 style="text-align: center; font-size:1.5rem">Голосовать</h1>
                    <div class="vote_ratio">
                        <label for="choice1" class="img_vote">
                            <input type="radio" id="choice1" name="vote" value="1" class="ratio_l">
                            <img class="img_bt circle" src="{{ asset('/img/icons_post/yes.png') }}" alt="">
                        </label>
                        <label for="choice2" class="img_vote">
                            <input type="radio" id="choice2" name="vote" value="2" class="ratio_r">
                            <img class="img_bt circle" src="{{ asset('/img/icons_post/no.png') }}" alt="">
                        </label>
                    </div>
                    <input type="hidden" name="offer_id" value="{{ $offer_id }}">
                    <input type="image" src="{{ asset('/img/icons_post/voting.png') }}" class="img_bt" title="Голосовать">
                </fieldset>
            </form>
        </div>
        <?php
        } else {
        ?>
        <div class="msg">
            <h1 style="text-align: center; font-size:1.5rem">Ваше мнение учтено</h1>
        </div>
        <?php
            if (isset($offer_id)) {
                $totalUsers = DB::table('users')->count() - 2;

                $counts = DB::table('offer_votes')->select(DB::raw('vote, COUNT(*) as count'))
                    ->where('offer_id', $offer_id)
                    ->groupBy('vote')
                    ->get();

                $za = $counts->where('vote', 1)->sum('count');
                $no = $counts->where('vote', 2)->sum('count');

                $totalVotes = $za + $no;
                $vozd = $totalUsers - $totalVotes;

                $za_percentage = $totalUsers > 0 ? round(($za * 100) / $totalUsers, 2) : 0;
                $no_percentage = $totalUsers > 0 ? round(($no * 100) / $totalUsers, 2) : 0;
                $vozd_percentage = $totalUsers > 0 ? round(($vozd * 100) / $totalUsers, 2) : 0;
            }
        ?>

        <h1 style="text-align: center; font-size:1.5rem">Результаты</h1>
        <table class="results-table">
            <tr>
                <td>За:</td>
                <td class="text-right">{{ $za ?? 0 }}</td>
            </tr>
            <tr>
                <td>
                    <div class="progress-bar">
                        <div class="progress" style="width:{{ $za_percentage ?? 0 }}%; background-color: green;">
                            <span>&nbsp;</span>
                        </div>
                    </div>
                </td>
                <td class="text-right">{{ $za_percentage ?? 0 }}%</td>
            </tr>
            <tr>
                <td>Против:</td>
                <td class="text-right">{{ $no ?? 0 }}</td>
            </tr>
            <tr>
                <td>
                    <div class="progress-bar">
                        <div class="progress" style="width:{{ $no_percentage ?? 0 }}%; background-color: red;">
                            <span>&nbsp;</span>
                        </div>
                    </div>
                </td>
                <td class="text-right">{{ $no_percentage ?? 0 }}%</td>
            </tr>
            <tr>
                <td>Не голосовали:</td>
                <td class="text-right">{{ $vozd ?? 0 }}</td>
            </tr>
            <tr>
                <td>
                    <div class="progress-bar">
                        <div class="progress" style="width:{{ $vozd_percentage ?? 0 }}%; background-color: #555;">
                            <span>&nbsp;</span>
                        </div>
                    </div>
                </td>
                <td class="text-right">{{ $vozd_percentage ?? 0 }}%</td>
            </tr>
        </table>

        <?php
        }
        ?>
    @else
        <div class="msg">Чтоб голосовать необходимо <a href="/login" class="eror_com">войти</a></div>
    @endauth

    <div class="msg">
        <h6>До конца голосования осталось:</h6>
        <div id="remaining-time" class="time-remaining"></div><br>
    </div>

    <script>
        const startVoteTime = new Date("{{ $startVoteTime }} UTC"); // Явно указать UTC
        const intervalHours = 72;

        function updateTime() {
            const now = new Date();
            const voteStartDate = new Date(startVoteTime);
            const endDate = new Date(voteStartDate.getTime() + intervalHours * 60 * 60 * 1000);
            const timeDiff = endDate - now;

            if (timeDiff <= 0) {
                document.getElementById('remaining-time').innerHTML = 'Период голосования закончился!';
                return;
            }

            const totalHours = Math.floor(timeDiff / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

            document.getElementById('remaining-time').innerHTML = `${totalHours}h ${minutes}m ${seconds}s`;
        }

        setInterval(updateTime, 1000);
    </script>
</div>

