<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LogHistoryController extends Controller
{
    public function index()
    {
        return view('admin.log-history');
    }
}