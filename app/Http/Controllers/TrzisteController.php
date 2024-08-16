<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TrzisteController extends Controller
{
    public function index()
    {
        $markets = Market::all();
        return Inertia::render('Trzista/TrzistaIndex', [
            'markets' => $markets
        ]);
    }

    public function create()
    {
        if(!Gate::allows('can-access', 'kreiraj-trziste')){
            return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
        }
        return Inertia::render('Trzista/TrzistaCreate');
    }

    public function store(Request $request)
    {
        if(!Gate::allows('can-access', 'kreiraj-trziste')){
            return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
        }
        $request->validate([
            'trziste' => ['required', 'string'],
        ]);
        $marketExists = DB::table('markets')
            ->whereRaw("LOWER(ime_trzista) = ?", [strtolower($request->trziste)])
            ->exists();
        if($marketExists){
            return redirect()->back()->with('error', "Trziste sa ovim imenom vec postoji");
        }
        $trziste = Market::create([
            'ime_trzista' => $request->trziste,
        ]);

        return redirect()->route('paket.create', ['market' => $trziste->id])->with('succes', "Trziste uspesno dodato");
    }
}
