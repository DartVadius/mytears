<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return array
     */
    public function index()
    {
        return [
            'api' => 'home'
        ];
    }
}
