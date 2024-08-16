<?php

    namespace App\Http\Controllers;

    use App\Models\Functionalities;
    use App\Models\Market;
    use App\Models\Package;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Gate;
    use Illuminate\Validation\Rule;
    use Inertia\Inertia;
    use Inertia\Response;

    class PackageController extends Controller
    {
        public function create(Market $market)
        {
            if (!Gate::allows('can-access', 'paketi')) {
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $markets = Market::all();
            $packages = Package::with(['functionalities', 'market'])->get();
            $existing_funcs = $market->functionalities;
            return Inertia::render('Package/Package', [
                'packages' => $packages,
                'markets' => $markets,
                'existingFuncs' => $existing_funcs,
                'chosenMarket' => $market,
            ]);
        }

        public function store(Request $request)
        {
            if (!Gate::allows('can-access', 'paket')) {
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $request->validate([
                'ime_paketa' => ['required', 'string', 'max:255', Rule::unique('packages', 'ime')],
                'cena' => ['required', 'numeric'],
                'broj_big' => ['required', 'numeric'],
                'trziste' => ['required']
            ]);
            $packageExists = DB::table('packages')
                ->whereRaw('LOWER(ime) = ? AND trziste_id = ?', [strtolower($request->ime_paketa), $request->trziste['id']])
                ->first();
            if($packageExists){
                return redirect()->back()->with('error', "Paket sa ovim imenom na ovom trzistu vec postoji");
            }
            try {
                DB::transaction(function () use ($request) {
                    $package = Package::create([
                        'trziste_id' => $request->trziste['id'],
                        'ime' => $request->ime_paketa,
                        'cena' => $request->cena,
                        'broj_besplatnih_instalacija_godisnje' => $request->broj_big ?? 0,
                    ]);


                });

                return redirect()->back()->with('success', 'Paket je uspesno dodat');

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Izmena neuspesna. Pokusajte ponovo ' . $e->getMessage());

            }


        }

        public function edit(Market $market)
        {
            return Inertia::render('Package/EditPackage', [
                'market' => $market,
                'packages' => Package::with(['functionalities', 'market'])->get(),
                'existingFuncs' => Functionalities::all(),
            ]);
        }

        public function update(Request $request, Package $package)
        {
            if (!Gate::allows('can-access', 'paket')) {
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $request->validate([
                'ime_paketa' => ['required', 'string', 'max:255', Rule::unique('packages', 'ime')->ignore($package->id)],
                'cena_paketa' => ['required', 'numeric'],
                'broj_big' => ['numeric'],
                'funkcionalnosti' => ['required', 'array'],
                'trziste' => ['required']
            ]);
            $package->update([
                'ime' => $request->ime_paketa,
                'cena' => $request->cena_paketa,
                'broj_besplatnih_instalacija_godisnje' => $request->broj_big,

            ]);
            $package->functionalities()->detach();
            foreach ($request->funkcionalnosti as $funk) {
                if (isset($funk['id'])) {
                    $package->functionalities()->attach($funk['id']);
                }
            }
            dd($request->trziste['id']);
            $package->market()->attach($request->trziste['id']);

            $package->save();
            return redirect()->back()->with('success', 'Paket je uspesno sacuvan');
        }


    }
