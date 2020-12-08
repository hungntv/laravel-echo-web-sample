<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    protected function sendMessage(Request $request) {
    	return $request->user();
    }
}
