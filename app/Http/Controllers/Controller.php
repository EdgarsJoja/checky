<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\User;
use Illuminate\Support\Facades\Auth;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
