<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {

      if(Auth::user()->employee()){
         if (Auth::user()->employee()->is_admin) {
            return to_route('dashboardAdmin');
         } else {
            return to_route('dashboardUser');
         }
      } else {
         return to_route('dashboard');
      }
         
    }
}
