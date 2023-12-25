<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {
         if (Auth::user()->is_admin) {
            return to_route('dashboardAdmin');
         } else {
            return to_route('dashboardUser');
         }
    }
}
