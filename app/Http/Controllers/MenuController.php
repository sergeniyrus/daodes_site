<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Загружает соответствующее меню (мобильное или десктопное).
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function loadMenu(Request $request)
    {
        $view = $request->query('view');

        // Проверяем, что запрашиваемый шаблон разрешен
        if (in_array($view, ['menu.mobile', 'menu.desktop'])) {
            return view($view);
        }

        // Если запрашиваемый шаблон не разрешен, возвращаем ошибку
        return response('Invalid menu view', 400);
    }
}