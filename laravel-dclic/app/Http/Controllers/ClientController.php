<?php

namespace App\Http\Controllers;
use App\Models\Vehicule;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    //
    public function index()
    {
        return view('dashboards.client');
    }

    public function dashboard()
    {

        return view('dashboards.client');
    }

    public function showVehicules()
    {
        $Vehicules = Vehicule::all();
        return view('clients.vehicules.index', compact('Vehicules'));
    }
}
