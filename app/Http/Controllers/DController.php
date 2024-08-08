<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DController extends Controller
//функция идёт в базу данных и выводит их на страницу 
{
        public function offers() {        
        $offers = DB::table('offers')->get();
        return view('dao')->with('offers', $offers);
    }
}
