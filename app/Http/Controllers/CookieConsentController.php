<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class CookieConsentController extends Controller
{
    public function accept(Request $request)
    {
        $cookie = cookie('cookie_consent', 'accepted', 60 * 24 * 365);
        
        return response()
            ->json(['success' => true])
            ->withCookie($cookie);
    }

    public function reject(Request $request)
    {
        $cookie = cookie('cookie_consent', 'rejected', 60 * 24 * 365);
        
        return back()
            ->withCookie($cookie)
            ->with('cookie_consent', __('cookie.message_rejected'));
    }

    public function policy()
    {
       
        return view('policies.cookie');
    }
}