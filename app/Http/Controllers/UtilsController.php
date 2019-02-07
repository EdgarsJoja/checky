<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilsController extends Controller
{
    /**
     * Get CSRF token
     *
     * @return false|string
     */
    public function getCsrfToken()
    {
        return json_encode(csrf_token());
    }
}
