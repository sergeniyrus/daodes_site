<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PageController extends Controller
{
    public function page_sort($post, $id)
    {
        $text = null;
        $comments = [];
        $viewFile = null; // Переменная для хранения имени файла представления
        $startVoteTime = null; // Переменная для хранения времени начала голосования

        // Проверка на существование записи в базе данных
        if ($post == 'news') {
            $text = DB::table('news')->where('id', $id)->first();
            if (!$text) {
                return redirect()->to('https://daodes.space/news');
            }
            // +1 просмотр к новости
            DB::table('news')->where('id', $id)->increment('views');
        }

        if ($post == 'offers') {
            $text = DB::table('offers')->where('id', $id)->first();
            if (!$text) {
                return redirect()->to('https://daodes.space/dao');
            }
            // +1 просмотр к оферу
            DB::table('offers')->where('id', $id)->increment('views');

            // Получение комментариев для предложений
            $comments = DB::table('comments_offers')->where('offer_id', $id)->get();

            // Получаем время начала голосования
            $startVoteTime = $text->start_vote;

            // Определяем имя представления на основе значения state
            switch ($text->state) {
                case null:
                    $viewFile = 'offers.spam';
                    break;
                case 1:
                    $viewFile = 'offers.discussion';
                    break;
                case 2:
                    $viewFile = 'offers.vote';
                    break;
                case 3:
                    $viewFile = 'offers.robot';
                    break;
                case 4:
                    $viewFile = 'offers.executed';
                    break;
                case 5:
                    $viewFile = 'offers.rejected';
                    break;
                default:
                    $viewFile = 'offers.default'; // Альтернативное представление, если значение state не определено
                    break;
            }
        }

        return view('page')->with([
            'text' => $text,
            'post' => $post,
            'id' => $id,
            'comments' => $comments,
            'viewFile' => $viewFile,
            'startVoteTime' => $startVoteTime, // Передаем время начала голосования
        ]);
    }
}
