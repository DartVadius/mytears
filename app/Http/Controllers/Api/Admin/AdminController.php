<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AdminController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return array
     */
    public function index()
    {
        return response()->json(['response' => 'admin'], Response::HTTP_OK);
    }
}
