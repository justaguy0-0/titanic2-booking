<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Логика дашборда, например:
        return view('admin.dashboard.index'); // Ссылка на шаблон дашборда
    }
}
