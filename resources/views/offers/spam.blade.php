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
        /* border-radius: 50%; */
    }

    .vote_ratio {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
    }

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
        /* Ширина картинки */
        height: auto;
        border: 3px solid transparent;
        /* Начальный цвет рамки */
        transition: border-color 0.3s;
    }

    .img_vote input[type="radio"]:checked+img {
        border-color: #f00;
        /* Цвет рамки для выбранного элемента */
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
</style>

<div class="left_box">
    @auth
        <?php
        $user = Auth::user();
        $user_id = $user->id;
        
        $offer_id = $offer_id ?? request()->route('id'); // Get the offer ID from the route if the variable is not passed
        
        $hasVoted = DB::table('spam')->where('offer_id', $offer_id)->where('user_id', $user_id)->exists();
        ?>

        @if (!$hasVoted)
            <h1 style="text-align: center; font-size:1.5rem">Spam / Not Spam</h1>
            <div class="vote_box">
                <form action="{{ route('spam.store') }}" class="form-vote" method="post">
                    @csrf

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
                            title="Vote">
                    </div>
                </form>
            </div>
        @else
            <h1 style="text-align: center; font-size:1.5rem">Your opinion has been recorded</h1>
            <?php
            // Check if the $offer_id variable is defined
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

                <h1 style="text-align: center; font-size:1.5rem">Results:</h1>

                <table class="results-table">
                    <tr>
                        <td>Spam:</td>
                        <td class="text-right">{{ $yes ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="progress-bar">
                                <div class="progress" style="width:{{ $za_percentage ?? 0 }}%; background-color: red;">
                                    <span>&nbsp;</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-right">{{ $za_percentage ?? 0 }}%</td>
                    </tr>
                    <tr>
                        <td>Not Spam:</td>
                        <td class="text-right">{{ $no ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="progress-bar">
                                <div class="progress" style="width:{{ $no_percentage ?? 0 }}%; background-color: green;">
                                    <span>&nbsp;</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-right">{{ $no_percentage ?? 0 }}%</td>
                    </tr>
                    <tr>
                        <td>Not Viewed:</td>
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

            </fieldset>
            {{-- Check spam/not spam conditions --}}
            <?php if ($za_percentage >= 25) {
                // Find the author
                $authorId = DB::table('users')->where('id', $text->user_id)->value('id');
            
                // Delete the offer
                DB::table('offers')->where('id', $offer_id)->delete();
                // Add the author to the spammers list
                DB::table('spamers')->insert([
                    'user_id' => $authorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            
                echo 'Post deleted as spam';
            }
            if ($no_percentage >= 25) {
                DB::table('offers')
                    ->where('id', $offer_id)
                    ->update(['state' => 1]);
                echo 'Verification passed';
            }
            ?>
        @endif
    @else
        <div class="msg">You need to <a href="/login" class="eror_com">log in</a></div>
    @endauth
</div>
