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

    class PackageController extends Controller
    {
        public function create()
        {
            if(!Gate::allows('can-access', 'paketi')){
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $markets = Market::all();
            $packages = Package::with(['functionalities', 'market'])->get();
            $existing_funcs = Functionalities::all();
            return Inertia::render('Package/Package', [
                'packages' => $packages,
                'markets' => $markets,
                'existingFuncs' => $existing_funcs
            ]);
        }

        public function store(Request $request)
        {
            if(!Gate::allows('can-access', 'paket')){
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $request->validate([
                'ime_paketa' => ['required', 'string', 'max:255', Rule::unique('packages', 'ime')],
                'cena' => ['required', 'numeric'],
                'broj_besplatnih_instalacija_godisnje' => ['required', 'numeric'],
                'funkcionalnosti' => ['required', 'array'],
                'customFunkcionalnosti' => ['array'],
                'trzista' => ['required']
            ]);
            try {
                DB::transaction(function () use ($request) {
                    $package = Package::create([
                        'ime' => $request->ime_paketa,
                        'cena' => $request->cena,
                        'broj_besplatnih_instalacija_godisnje' => $request->broj_besplatnih_instalacija_godisnje ?? 0,
                    ]);

                    foreach ($request->funkcionalnosti as $funk) {
                        if (isset($funk['id'])) {
                            $package->functionalities()->attach($funk['id']);
                        }
                    }
                    foreach ($request->customFunkcionalnosti as $cf){
                        $func = Functionalities::create([
                            'funkcionalnost' => $cf['funkcionalnost'],
                        ]);
                        $package->functionalities()->attach($func->id);
                    }
                    foreach ($request->trzista as $trziste) {
                        if (isset($trziste['id'])) {
                            Market::where('id', $trziste['id'])->first()->packages()->attach($package->id);
                        }
                    }
                });

                return redirect()->back()->with('success', 'Paket je uspesno dodat');

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Izmena neuspesna. Pokusajte ponovo ' . $e->getMessage());

            }


        }

        public function update(Request $request, Package $package)
        {
            if(!Gate::allows('can-access', 'paket')){
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $request->validate([
               'ime_paketa' => ['required', 'string', 'max:255', Rule::unique('packages', 'ime')->ignore($package->id)],
               'cena_paketa' => ['required', 'numeric'],
               'broj_big' => ['numeric'],
                'funkcionalnosti' => ['required', 'array'],
                'customFunkcionalnosti' => ['array'],
                'trzista' => ['required']
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
            foreach ($request->customFunkcionalnosti as $cf) {
                $func = Functionalities::create([
                    'funkcionalnost' => $cf['funkcionalnost'],
                ]);
                $package->functionalities()->attach($func->id);
            }
            $package->market()->detach();
            foreach ($request->trzista as $trziste) {
                if (isset($trziste['id'])) {
                    $package->market()->attach($trziste['id']);
                }
            }
            $package->save();
            return redirect()->back()->with('success', 'Paket je uspesno sacuvan');
        }
    }
