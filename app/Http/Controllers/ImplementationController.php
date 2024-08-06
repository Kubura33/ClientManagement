<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImplementationController extends Controller
{
    public function index()
    {
        $trziste = session('trziste');
        if($trziste){
            //Refaktorisi da vraca samo ugovore sa statusom implementirano
            $companies = Company::with('market')->where('trziste_id', $trziste)->get();
            return Inertia::render('Main/Implementation', ['companies' => $companies]);
        }
        return redirect()->route('dashboard');

    }


    public function search(Request $request)
    {
        $request->validate([
            'klijent' => ['string', 'nullable'],
            'ime_firme' => ['string', 'nullable'],
            'PIB' => ['string', 'size:9', 'nullable'],
            'MB' => ['string', 'size:8', 'nullable']
        ]);

    }
}
