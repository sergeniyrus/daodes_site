<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\seed;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SeedController extends Controller
{
    public function index()
    {
      

      $user = Auth::user();
      $userName = $user->name;

      $user_id = auth()->id(); // Получение ID текущего пользователя

      $keyword = DB::table('users')
            ->where('name', $userName)
            ->value('keyword');

      // $onseed = DB::table('seed')
      //       ->where('user_id', $user_id)
      //       ->value('word23');

    return view('seed')->with('keyword', $keyword);


    }







    //Сохранятель сид-фразы

    public function saveSeed(Request $request)
{
    // Получаем данные из запроса для 24 скрытых полей
    $user_id = auth()->id(); // Получение ID текущего пользователя

    $seedword = ['user_id' => $user_id];
    for ($i = 0; $i < 24; $i++) {
        $seedword['word' . $i] = $request->input('word' . $i);   
}
        seed::create($seedword);

     $checkpoint = 'Cохранил'; // Чекпоинт "Cохранил"

    return redirect()->back()->with('checkpoint', $checkpoint);


}

}