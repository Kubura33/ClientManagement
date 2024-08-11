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
            if ($trziste) {
                //Refaktorisi da vraca samo ugovore sa statusom implementirano
                $companies = Company::with('market')
                    ->where('trziste_id', $trziste)
                    ->whereHas('contract', function ($query) {
                        return $query->where('status_id', 3);
                    })
                    ->get();
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
            $query = Company::query();

            // Apply filters conditionally
            if ($request->filled('ime_firme')) {
                $query->where('ime', $request->ime_firme);
            }

            if ($request->filled('PIB')) {
                $query->where('PIB', $request->PIB);
            }

            if ($request->filled('MB')) {
                $query->where('MB', $request->MB);
            }

            // Include related 'market' and execute query
            $companies = $query
                ->whereHas('contract', function ($query) {
                    return $query->where('status_id', 3);
                })
                ->with('market')->get();

            return Inertia::render('Main/Implementation', ['companies' => $companies]);
        }
    }
