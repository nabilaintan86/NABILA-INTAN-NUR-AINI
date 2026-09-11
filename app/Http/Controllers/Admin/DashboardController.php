<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;

class DashboardController extends Controller
{
    public function index()
    {
        $devices = Device::all();

        return view('dashboard', compact('devices'));
    }
}
