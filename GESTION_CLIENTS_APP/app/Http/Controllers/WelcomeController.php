<?php

namespace App\Http\Controllers;


use App\Models\Technicien;
use App\Models\Service;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    //
     public function welcome(){
        $techniciens = Technicien::all();
        $services = Service::all();
        return view('welcome', compact('techniciens', 'services'));
    }
}

