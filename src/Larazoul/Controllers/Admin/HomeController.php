<?php

namespace Larazoul\Larazoul\Larazoul\Controllers\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
       return view('larazoul::admin.home.index');
    }
}
