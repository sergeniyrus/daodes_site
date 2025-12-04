<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserKeyController extends Controller
{
    public function getPublicKeys(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
        ]);

        $ids = $request->input('user_ids');

        $keys = User::with('profile')
            ->whereIn('id', $ids)
            ->get()
            ->mapWithKeys(function ($user) {
                return [
                    $user->id => $user->profile?->public_key
                ];
            });

        return response()->json($keys);
    }
}
