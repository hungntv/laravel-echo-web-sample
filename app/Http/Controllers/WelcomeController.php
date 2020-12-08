<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    protected function wellcome(Request $request) {
    	return view('welcome');
    }
}
