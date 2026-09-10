<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DetailRouterController extends Controller
{
    public function index()
    {
        return view('admin.detail');
    }
}