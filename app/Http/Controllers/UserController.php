<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->input('name');

        return \App\User::where('name', 'LIKE', "$name%")
            ->take(5)
            ->get()
            ->map(function($user) {
                return ['name' => $user->name];
            });
    }
    
    public function store()
    {
        request()->validate([
            'avatar' => ['required', 'image']
        ]);

        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars','public')
        ]);

        return response ([], 204);
    }


}
