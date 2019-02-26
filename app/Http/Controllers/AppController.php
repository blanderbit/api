<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AppController extends Controller
{
    public function index()
    {
        return view('first_page');
    }
}
