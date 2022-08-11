<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class AdminUserController extends Controller
{
    public function list(): View
    {
        return view('pages.admin.user-list');
    }
}
