<?php

    namespace App\Http\Controllers;

    use App\Models\Functionalities;
    use App\Models\Market;
    use App\Models\Package;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Validation\Rule;
    use Inertia\Inertia;

    class PackageController extends Controller
    {
        public function create()
        {
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
            $request->validate([
                'ime_paketa' => ['required', 'string', 'max:255', Rule::unique('packages', 'ime')],
                'cena_paketa' => ['required', 'numeric'],
                'broj_big' => ['required', 'numeric'],
                'funkcionalnosti' => ['required', 'array'],
                'trzista' => ['required']
            ]);
            try {
                DB::transaction(function () use ($request) {
                    $package = Package::create([
                        'ime' => $request->ime_paketa,
                        'cena' => $request->cena_paketa,
                        'broj_besplatnih_instalacija_godisnje' => $request->broj_big,
                    ]);

                    foreach ($request->funkcionalnosti as $funk) {
                        if (isset($funk['id'])) {
                            $package->functionalities()->attach($funk['id']);
                        } else {
                            $newFunc = Functionalities::create([
                                'funkcionalnost' => $funk['funkcionalnost'],
                            ]);
                            $package->functionalities()->attach($newFunc->id);
                        }
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
            dd([$request->all(), $package]);
        }
    }
