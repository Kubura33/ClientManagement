<?php

namespace App\Http\Controllers;

use App\Models\Functionalities;
use App\Models\Market;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use function PHPUnit\Framework\isEmpty;

class FunctionalitiesController extends Controller
{
    public function create(Market $market)
    {
        if (!Gate::allows('can-access', 'paket')) {
            return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
        }
        return Inertia::render('Functionalities/NewFunctionality',[
            'market' => $market,
            'existingFuncs' => $market->functionalities
        ]);
    }

    public function store(Request $request)
    {
        if (!Gate::allows('can-access', 'paket')) {
            return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
        }
        if(!count($request->functionalities)){
            return redirect()->back()->with('error', 'Niste uneli ni jednu funkcionalnost');
        }else{
            foreach ($request->functionalities as $functionality) {
                Functionalities::create([
                    'funkcionalnost' => $functionality['funkcionalnost'],
                    'trziste_id' => $request->market
                ]);
            }
            return redirect()->back()->with('success', 'Funkcionalnosti uspesno dodate');
        }
    }
    public function functionalityToPackage(Market $market)
    {
        if (!Gate::allows('can-access', 'paket')) {
            return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
        }
        $packages = Package::where('trziste_id', $market->id)->with('functionalities')->get();

        $existing_funcs = $market->functionalities;
        return Inertia::render('Functionalities/FunctionalityToPackage', [
            'packages' => $packages,
            'existingFuncs' => $existing_funcs,
            'market' => $market,
        ]);
    }

    public function storeFuncToPackage(Request $request)
    {
        if (!Gate::allows('can-access', 'paket')) {
            return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
        }
       $request->validate([
            'trziste' => ['required'],
            'paket_id' => ['required'],
            'customFuncs' => ['nullable'],
        ]);
        $package = Package::find($request->paket_id);
        if (empty($request->input('funkcionalnosti'))) {
            $request->validate([
                'customFuncs' => ['required'],
            ]);
        }else{
            $functionalityIds = collect($request->funkcionalnosti)->pluck('id')->toArray();
            $package->functionalities()->sync($functionalityIds);
        }
        foreach ($request->input('customFuncs') as $functionality) {
            $newFunc = Functionalities::create([
                'funkcionalnost' => $functionality['funkcionalnost'],
                'trziste_id' => $request->trziste['id'],
            ]);
            $package->functionalities()->attach($newFunc->id);
        }
        return redirect()->back()->with('success', 'Funkcionalnosti uspesno dodate');
    }
}
