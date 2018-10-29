<?php

namespace App\Http\Controllers;

use App\Item;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    /**
     * @return $this
     */
    public function index()
    {
        return view('main')->with([
            'user' => Auth::user() ?: [],
            'urls' => [
                'login' => route('login.redirect'),
                'logout' => route('logout'),
                'itemSave' => route('item.save'),
                'itemUpdate' => route('item.update'),
                'itemDelete' => route('item.delete')
            ],
            'options' => [
                'authorized' => Auth::check()
            ],
            'items' => $this->getItems()
        ]);
    }

    /**
     * Get user items
     *
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    protected function getItems()
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = User::find(Auth::id());

            return $user->items()->get();
        }

        return [];
    }
}
