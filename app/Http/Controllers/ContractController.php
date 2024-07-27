<?php

    namespace App\Http\Controllers;

    use App\Models\Company;
    use App\Models\ImplementationStatus;
    use App\Models\Invoicing;
    use App\Models\Market;
    use Illuminate\Http\Request;
    use Inertia\Inertia;

    class ContractController extends Controller
    {
        public function index($id)
        {
            session(['trziste' => $id]);

            return Inertia::render('Main/Index');
        }

        public function create()
        {

            $trziste = session('trziste');
            $statuses = ImplementationStatus::all();
            $fakturisanje = Invoicing::all();
            if ($trziste) {
                $packages = Market::where('id', $trziste)->first()->packages()->with('functionalities')->get();
            } else {
                return redirect()->route('dashboard')->with('message', 'Morate izabrati trziste!');
            }

            return Inertia::render('Main/ContractForm', ['packages' => $packages,
                'statuses' => $statuses,
                'fakturisanje' => $fakturisanje,
            ]);
        }

        public function store(Request $request)
        {

            $request->validate([
                'klijent' => ['string', 'max:255', 'nullable'],
                'ime_firme' => ['required', 'string', 'max:255'],
                'PIB' => ['string', 'max:9', 'required', 'unique:'.Company::class],
                'MB' => ['string', 'max:8', 'required', 'unique:'.Company::class],
                'package' => ['required'],
                'functionalities' => ['required', 'array'],
                'connection' => ['required', 'max:1000'],
                'contacts' => ['required', 'array'],
                'implementation_status' => ['required'],
                'date' => ['required_if:implementation_status, 3', 'date'],
                'tip_implementacije' =>['required_if:implementation_status, 3'],
                'ugovor' => ['required', 'string'],
                'aneks' => ['string'],
                'tip_fakturisanja' => ['required'],
                'iznos_fakture' => ['required']
            ]);
        }
    }
