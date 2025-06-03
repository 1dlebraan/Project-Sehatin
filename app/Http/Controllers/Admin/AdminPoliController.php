<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPoliController extends Controller
{
    public function index()
    {
        return view('admin.admin-daftarpoli');
    }
}
