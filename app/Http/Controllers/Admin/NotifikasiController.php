<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('admin.notifikasi');
    }
}