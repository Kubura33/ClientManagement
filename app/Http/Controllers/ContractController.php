<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\ContractRequest;
    use App\Models\Client;
    use App\Models\Company;
    use App\Models\Connection;
    use App\Models\Contact;
    use App\Models\Contract;
    use App\Models\Functionalities;
    use App\Models\ImplementationStatus;
    use App\Models\Invoicing;
    use App\Models\Market;
    use App\Models\Package;
    use Illuminate\Support\Facades\Gate;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Date;
    use Illuminate\Support\Facades\DB;
    use Inertia\Inertia;

    class ContractController extends Controller
    {
        public function index(Market $id)
        {
            return Inertia::render('Main/Index', ['market' => $id]);
        }

        public function create(Market $id)
        {

            if(!Gate::allows('can-access', 'kreiraj')){
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }

            $trziste = $id->id;
            if(!Gate::allows('has-market', $trziste)){
                return redirect()->back()->with('error', "Ne mozete kreirati klijenta na ovom trzistu jer niste assignovani na njega");
            }
            $statuses = ImplementationStatus::all();
            $existingFunctionalities = Functionalities::where('trziste_id', $trziste)->get();
            $fakturisanje = Invoicing::all();

            if ($trziste) {
                $packages = Package::where('trziste_id', $trziste)->with('functionalities')->get();
            } else {
                return redirect()->route('dashboard')->with('message', 'Morate izabrati trziste!');
            }

            return Inertia::render('Main/ContractForm', ['packages' => $packages,
                'statuses' => $statuses,
                'fakturisanje' => $fakturisanje,
                'existingFunctionalities' => $existingFunctionalities,
                'market' => $id
            ]);
        }

        public function store(ContractRequest $request)
        {
            if(!Gate::allows('can-access', 'kreiraj')){
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }

            try {
                DB::transaction(function () use ($request) {
                    $client = Client::where(DB::raw('LOWER(ime)'), strtolower($request->klijent))->get();
                    if ($client->isEmpty()) {
                        $client = Client::create([
                            'ime' => $request->klijent
                        ]);
                    } else {
                        $client = $client->first(); // Get the first match
                    }
                    $company = Company::create([
                        'trziste_id' => $request->trziste_id,
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
                            'telefon' => $contact['phone'] ?? "TEST",
                            'telefon_2' => $contact['phone2'],
                            'email' => $contact['email'],
                            'email_2' => $contact['email2'],
                        ]);
                    }
                    $ugovor = Contract::create([
                        'firma_id' => $company->id,
                        'fakturisanje_id' => $request->tip_fakturisanja,
                        'broj_aneksa' => $request->aneks,
                        'status_id' => $request->implementation_status,
                        'paket_id' => $request->package,
                        'status_implementiranja' => $request->tip_implementacije,
                        'broj_ugovora' => $request->ugovor,
                        'godina_ugovora' => Date::make($request->godina_ugovora)->format('Y'),
                        'datum_implementacije' => $request->date,
                        'iznos_fakture' => $request->iznos_fakture,
                        'broj_preostalih_instalacija' => $request->broj_preostalih_instalacija,
                        'broj_besplatnih_instalacija_godisnje' => $request->broj_preostalih_instalacija,

                    ]);
                    foreach ($request->functionalities as $fc) {
                        if (isset($fc['id'])){
                            $ugovor->functionalities()->attach($fc['id'], ['uradjeno' => $fc['isDone'] ?? 0]);
                        }

                    }
                    if (isset($request->customFunctionalities)) {
                        foreach ($request->customFunctionalities as $fc) {
                            $func = Functionalities::create([
                                'funkcionalnost' => $fc['funkcionalnost'],
                                'trziste_id' => $request->trziste_id
                            ]);
                            $ugovor->functionalities()->attach($func->id, ['uradjeno' => $func['isDone'] ?? 0] );
                        }
                    }
                    if($request->newDates){
                        foreach ($request->newDates as $date){
                            DB::table('installation_log')->insert([
                                'ugovor_id' => $ugovor->id,
                                'datum_instalacije' => $date,
                                'instalaciju_izvrsio' => auth()->user()->username,
                            ]);
                            $ugovor->broj_preostalih_instalacija -= 1;
                        }
                    }
                    $ugovor->save();

                });
                return redirect()->route('dashboard')->with('success', 'Ugovor uspesno kreiran');
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', "Neuspesno kreiranje ugovora {$exception->getMessage()}");
            }


        }

        public function show($id)
        {
            $contract = Contract::where('firma_id', $id)->with(['company.client', 'package', 'status', 'company.contacts', 'company.connection', 'functionalities'])->first();
            if (!$contract){
                return redirect()->route('dashboard')->with('error', "Izabrani ugovor ne postoji");
            }
            $trziste = $contract->company->market;
            $statuses = ImplementationStatus::all();
            $fakturisanje = Invoicing::all();
            $existingFunctionalities = Functionalities::all();
            $companies = [];
            if ($trziste) {
                $packages = Market::where('id', $trziste->id)->first()->packages()->with('functionalities')->get();
            } else {
                return redirect()->route('dashboard')->with('message', 'Morate izabrati trziste!');
            }
            $contract = Contract::where('firma_id', $id)->with(['company.client', 'package', 'status', 'company.contacts', 'company.connection', 'functionalities'])->first();
            $client = $contract->company->client;
            if ($client->companies->count() > 1) {
                // Fetch all companies related to the client, excluding the client's own company
                $companies = Company::where('klijent_id', $client->id)
                    ->with('contract')
                    ->where('id', '!=', $contract->firma_id) // Exclude the client's own company
                    ->get();
            }
            $indexedCompanies = [];
            foreach ($companies as $company){
                $indexedCompanies[$company->trziste_id] = $company;
            }
            $dates = DB::table('installation_log')
                ->where('ugovor_id', $contract->id) // Optional: add any conditions you need
                ->pluck('datum_instalacije');
            if (!$contract) {
                return redirect()->back()->with('error', "Izabrani ugovor ne postoji u bazi!");
            }
            return Inertia::render('Main/EditContractForm', ['contract' => $contract, 'packages' => $packages,
                'statuses' => $statuses,
                'fakturisanje' => $fakturisanje,
                'existingFunctionalities' => $existingFunctionalities,
                'otherContracts' => $indexedCompanies,
                'market' => $trziste,
                'datumiBesplatnihInstalacija' => $dates
            ]);
        }

        public function search()
        {

        }

        public function update(ContractRequest $request, Contract $contract)
        {
            $trziste = $contract->company->trziste_id;
            if(!Gate::allows('has-market', $trziste)){
                return redirect()->back()->with('error', "Ne mozete kreirati klijenta na ovom trzistu jer niste assignovani na njega");
            }

            try {
                DB::transaction(function () use ($request, $contract) {
                    //Client
                    if ($contract->company->client)
                        $contract->company->client->update([
                            'ime' => $request->klijent
                        ]);
                    //Company
                    if ($contract->company) {
                        if(auth()->user()->id == 1){
                            $contract->company->updateCompany([
                                'ime' => $request->input('ime_firme'),
                                'PIB' => $request->input('PIB'),
                                'MB' => $request->input('MB'),

                            ]);
                        }else if( auth()->user()->id == 2){
                            $contract->company->updateCompany([
                                'ime' => $request->input('ime_firme'),
                            ]);
                        }

                        //Connection
                        $contract->company->connection->update([
                            'konekcija' => $request->connection
                        ]);
                        //Contacts
                        foreach ($request->contacts as $contact) {
                            if(isset($contact['id']) && $contact['id']){
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
                          else {
                                $newContact = Contact::create([
                                    'firma_id' => $contract->company->id,
                                    'ime_prezime' => $contact['ime_prezime'],
                                    'telefon' => $contact['phone'] ?? "TESTING",
                                    'telefon_2' => $contact['phone2'],
                                    'email' => $contact['email'],
                                    'email_2' => $contact['email2']
                                ]);
                            }
                        }
                        $contract->functionalities()->detach();
                        //Functionalities
                        foreach ($request->functionalities as $fc) {
                            if (isset($fc['id'])){

                                $contract->functionalities()->attach($fc['id'], [ 'uradjeno' => $fc['pivot']['uradjeno']]);
                            }else{

                                $func = Functionalities::create([
                                    'funkcionalnost' => $fc['funkcionalnost']
                                ]);
                                $contract->functionalities()->attach($func->id, ['uradjeno' => $fc['pivot']['uradjeno']]);
                            }
                        }
                        //Contract
                        $contract->update([
                            'fakturisanje_id' => $request->tip_fakturisanja,
                            'broj_aneksa' => $request->aneks,
                            'status_id' => $request->implementation_status,
                            'paket_id' => $request->package,
                            'status_implementiranja' => $request->tip_implementacije ?? null,
                            'broj_ugovora' => $request->ugovor,
                            'godina_ugovora' => $request->godina_ugovora ?? date('Y'),
                            'datum_implementacije' => $request->date,
                            'iznos_fakture' => $request->iznos_fakture,
                            'broj_besplatnih_instalacija_godisnje' => $request->broj_besplatnih_instalacija_godisnje ?? 0

                        ]);

                        if($request->implementation_status != 3){
                            $contract->status_implementiranja = null;
                        }
                        if($request->newDates){
                            if($contract->broj_preostalih_instalacija == 0){
                                return redirect()->back()->with('error', "Korisnik nema preostalih godisnjih instalacija");
                            }
                            foreach ($request->newDates as $date){
                                DB::table('installation_log')->insert([
                                    'ugovor_id' => $contract->id,
                                    'datum_instalacije' => $date,
                                    'instalaciju_izvrsio' => auth()->user()->username,
                                ]);
                            }
                            $contract->touch();
                        }
                    }
                    $contract->save();
                });

                return redirect()->back()->with('success', 'Izmena je uspesna');
            } catch (\Exception $e) {


                return redirect()->back()->with('error', 'Izmena neuspesna. Pokusajte ponovo ' . $e->getMessage());
            }


        }

        public function filterContracts($filter, Request $request)
        {
            $companies = [];
            $contracts = Contract::all();

            if ($filter == 1) {
                $contracts = ImplementationStatus::where('naziv', 'U toku')->first()->contract->load('company.market');
            } else if ($filter == 2) {
                $contracts = ImplementationStatus::where('naziv', 'Ne javljalju se')->first()->contract->load('company.market');
            } else if ($filter == 3) {
                $contracts = ImplementationStatus::where('id', 3)->first()->contract->load('company.market');
            }
            foreach ($contracts as $contract) {
                if($filter != 3){
                    if($contract->status_id == $filter){
                        $contract->company->status = $contract->status->naziv;
                        $companies[] = $contract->company;
                    }else{
                        $companies = Company::orderBy('ime_firme')->get();
                        $companies->load('market');
                        $companies->map(function ($company) {
                            if($company->contract->status_id == 3){
                                $company->status = $company->contract->status_implementiranja ?? "No status";
                            }else{
                                $company->status = $company->contract->status->naziv ?? "No status";
                            }

                        });
                    }

                }else {
                    if(strtolower($contract->status_implementiranja) == "ceka se neka funkcionalnost"){
                        $contract->company->status = $contract->status_implementiranja;
                        $companies[] = $contract->company;
                    }
                }

            }
            return Inertia::render('Main/ShowContracts', ['companies' => $companies, 'filter' => $filter]);
        }



    }

