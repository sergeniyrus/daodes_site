<?php

namespace App\Http\Controllers;

class DController extends Controller
//функция идёт в базу данных и выводит их на страницу 
{
        public function whitepaper() {        
        
        return view('wp');
    }
}
