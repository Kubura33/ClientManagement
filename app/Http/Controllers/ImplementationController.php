<?php

    namespace App\Http\Controllers;

    use App\Models\Company;
    use App\Models\Market;
    use Illuminate\Http\Request;
    use Inertia\Inertia;

    class ImplementationController extends Controller
    {
        public function index(Market $market)
        {
            $trziste = $market->id;
            if ($trziste) {
                $companies = Company::with('market')
                    ->where('trziste_id', $trziste)
                    ->whereHas('contract', function ($query) {
                        return $query->where('status_id', 3);
                    })
                    ->get();
                return Inertia::render('Main/Implementation', [
                    'companies' => $companies,
                    'market' => $trziste]);
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
            $query->where('trziste_id', $request->trziste);
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

            return Inertia::render('Main/Implementation', [
                'companies' => $companies,
                'market' => $request->trziste

            ]);
        }
    }
