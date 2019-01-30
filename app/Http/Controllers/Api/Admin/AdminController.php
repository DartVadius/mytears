<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return array
     */
    public function index()
    {
        return [
            'response' => 'admin'
        ];
    }
}
