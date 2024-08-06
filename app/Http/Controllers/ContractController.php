<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\ContractRequest;
    use App\Models\Client;
    use App\Models\Company;
    use App\Models\Connection;
    use App\Models\Contact;
    use App\Models\Contract;
    use App\Models\ImplementationStatus;
    use App\Models\Invoicing;
    use App\Models\Market;
    use Illuminate\Support\Facades\Date;
    use Illuminate\Support\Facades\DB;
    use Inertia\Inertia;
    use function Laravel\Prompts\error;

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

        public function store(ContractRequest $request)
        {

            $client = Client::create([
                'ime' => $request->klijent
            ]);
            $company = Company::create([
                'trziste_id' => session('trziste'),
                'klijent_id' => $client->id,
                'ime' => $request->input('ime_firme'),
                'PIB' => $request->input('PIB'),
                'MB' => $request->input('MB'),

            ]);
            Connection::create([
                'firma_id' => $company->id,
                'konekcija' => $request->connection
            ]);
            foreach ($request->contacts as $contact) {
                Contact::create([
                    'firma_id' => $company->id,
                    'ime_prezime' => $contact['ime_prezime'],
                    'telefon' => $contact['telefon1'],
                    'telefon_2' => $contact['telefon2'],
                    'email' => $contact['email1'],
                    'email_2' => $contact['email2'],
                ]);
            }
            $ugovor = Contract::create([
                'firma_id' => $company->id,
                'fakturisanje_id' => $request->tip_fakturisanja,
                'broj_aneksa' => $request->aneks,
                'status_id' => $request->implementation_status,
                'paket_id' => $request->package,
                'funkcionalnosti' => json_encode($request->functionalities),
                'status_implementiranja' => $request->tip_implementacije,
                'broj_ugovora' => $request->ugovor,
                'godina_ugovora' => Date::make($request->date)->format('Y'),
                'datum_implementacije' => $request->date,
                'iznos_fakture' => $request->iznos_fakture

            ]);
            return redirect()->route('index', ['id' => session('trziste')]);
        }

        public function show($id)
        {
            $trziste = session('trziste');
            $statuses = ImplementationStatus::all();
            $fakturisanje = Invoicing::all();
            if ($trziste) {
                $packages = Market::where('id', $trziste)->first()->packages()->with('functionalities')->get();
            } else {
                return redirect()->route('dashboard')->with('message', 'Morate izabrati trziste!');
            }
            $contract = Contract::where('firma_id', $id)->with(['company.client', 'package', 'status', 'company.contacts', 'company.connection'])->first();
            if (!$contract) {
                return redirect()->back()->with('error', "Izabrani ugovor ne postoji u bazi!");
            }
            $contract->funkcionalnosti = json_decode($contract->funkcionalnosti, true);
            return Inertia::render('Main/EditContractForm', ['contract' => $contract, 'packages' => $packages,
                'statuses' => $statuses,
                'fakturisanje' => $fakturisanje,]);
        }

        public function search()
        {

        }

        public function update(ContractRequest $request, Contract $contract)
        {
            try {
                DB::transaction(function () use ($request, $contract) {
                    if ($contract->company->client)
                        $contract->company->client->update([
                            'ime' => $request->klijent
                        ]);
                    if ($contract->company) {
                        $contract->company->update([
                            'trziste_id' => session('trziste'),
                            'ime' => $request->input('ime_firme'),
                            'PIB' => $request->input('PIB'),
                            'MB' => $request->input('MB'),

                        ]);
                        $contract->company->connection->update([
                            'konekcija' => $request->connection
                        ]);
                        foreach ($request->contacts as $contact) {
                            $existingContact = $contract->company->contacts()->where('id', $contact['id'])->first();
                            if ($existingContact) {
                                $existingContact->update([
                                    'ime_prezime' => $contact['ime_prezime'],
                                    'telefon' => $contact['telefon'],
                                    'telefon_2' => $contact['telefon_2'],
                                    'email' => $contact['email'],
                                    'email_2' => $contact['email_2'],
                                ]);
                            }
                        }
                        $contract->update([
                            'fakturisanje_id' => $request->tip_fakturisanja,
                            'broj_aneksa' => $request->aneks,
                            'status_id' => $request->implementation_status,
                            'paket_id' => $request->package,
                            'funkcionalnosti' => json_encode($request->functionalities),
                            'status_implementiranja' => $request->tip_implementacije,
                            'broj_ugovora' => $request->ugovor,
                            'godina_ugovora' => Date::make($request->date)->format('Y'),
                            'datum_implementacije' => $request->date,
                            'iznos_fakture' => $request->iznos_fakture

                        ]);
                    }

                });

                return redirect()->route('implementirano')->with('success', 'Izmena je uspesna');
            } catch (\Exception $e) {


                return redirect()->back()->with('error', 'Izmena neuspesna. Pokusajte ponovo ' . $e->getMessage());
            }


        }

        public function filterContracts($filter)
        {

            $companies = [];
            if($filter == 1){
                $contracts = ImplementationStatus::where('naziv', 'U toku')->first()->contract->load('company');
            }
            else if($filter == 2){
                $contracts = ImplementationStatus::where('naziv', 'Ne javljalju se')->first()->contract->load('company');
            }
            else if($filter == 3){
                $contracts = ImplementationStatus::where('id', 3)->first()->contract->load('company');
            }
            forEach($contracts as $contract){
                $companies[] = $contract->company;
            }
            return Inertia::render('Main/ShowContracts', ['companies' => $companies, 'filter' => $filter]);
        }
    }

