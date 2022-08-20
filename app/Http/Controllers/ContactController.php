<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    public function view(): View
    {
        return view('pages.contact.contact-view');
    }
}
