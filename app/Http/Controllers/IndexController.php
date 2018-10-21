<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        return view('main')->with([
            'user' => Auth::user() ?: new User(),
            'urls' => [
                'login' => route('login.redirect'),
                'logout' => route('logout')
            ],
            'options' => [
                'authorized' => Auth::check()
            ]
        ]);
    }
}
