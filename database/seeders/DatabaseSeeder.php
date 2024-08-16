<?php

    namespace Database\Seeders;

    use App\Models\Functionalities;
    use App\Models\ImplementationStatus;
    use App\Models\Invoicing;
    use App\Models\Market;
    use App\Models\Package;
    use App\Models\Role;
    use App\Models\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
            // User::factory(10)->create();

            User::insert([
                ['role_id' => 1,
                    'username' => 'Super Admin',
                    'password' => Hash::make('password')],
                ['role_id' => 2,
                    'username' => 'Obican Admin',
                    'password' => Hash::make('password')],
                ['role_id' => 3,
                    'username' => 'B2B Agent',
                    'password' => Hash::make('password')],
                ['role_id' => 4,
                    'username' => 'User',
                    'password' => Hash::make('password')],
            ]);
            Market::insert([
                ['ime_trzista' => 'Srbija'],
                ['ime_trzista' => 'Bosna i Hercegovina'],
                ['ime_trzista' => 'Slovenija']
            ]);
            Role::insert([
                ['role' => 'super_admin'],
                ['role' => 'admin'],
                ['role' => 'b2b'],
                ['role' => 'user'],
            ]);
            ImplementationStatus::insert([
                ['naziv' => 'U toku'],
                ['naziv' => 'Ne javljalju se'],
                ['naziv' => 'Implementirano']
            ]);
            Invoicing::insert([
                ['naziv_fakturisanja' => 'Mesec'],
                ['naziv_fakturisanja' => 'Tri meseca'],
                ['naziv_fakturisanja' => 'Sest meseci'],
                ['naziv_fakturisanja' => 'Godina']
            ]);
            $market = Market::where('ime_trzista', "Srbija")->first();
            Package::insert(
                [
                    [
                        'ime' => 'Basic',
                        'broj_besplatnih_instalacija_godisnje' => 1,
                        'cena' => 39,
                        'trziste_id' => $market->id
                    ],
                    [
                        'ime' => 'Light',
                        'broj_besplatnih_instalacija_godisnje' => 2,
                        'cena' => 49,
                        'trziste_id' => $market->id
                    ],
                    [
                        'ime' => 'Pro',
                        'broj_besplatnih_instalacija_godisnje' => 3,
                        'cena' => 59,
                        'trziste_id' => $market->id
                    ],
                    [
                        'ime' => 'Premium',
                        'broj_besplatnih_instalacija_godisnje' => 4,
                        'cena' => 69,
                        'trziste_id' => $market->id
                    ],
                    [
                        'ime' => 'Platinum',
                        'broj_besplatnih_instalacija_godisnje' => 5,
                        'cena' => 99,
                        'trziste_id' => $market->id
                    ]

                ]
            );

            Functionalities::insert([
                ['funkcionalnost' => 'Automatsko osvežavanje podataka', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Automatski eksport izvoda (txt, xml i mt490 format)', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Automatski eksport izvoda u PDF formatu', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Automatski import naloga', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Automatski eksport dnevnih uplata', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Automatski eksport stanja', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Automatski eksport kursnih lista', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Automatsko razvrstavanje izvoda', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Kriptovani automatski import naloga', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Zaključavanje naloga', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Email notifikacije o prilivima', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Excel izveštaj o prometu i stanju po računima', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Automatsko potpisivanje naloga', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Automatsko slanje potpisanih naloga', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Integracija sa Web Service-om / API-jem', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Zaključvanje odredjenih polja po nalogu', 'trziste_id' => $market->id],
                ['funkcionalnost' => 'Email notifikacije o izvodima i stanjima', 'trziste_id' => $market->id],
            ]);
            Package::where('ime', 'Basic')->first()->functionalities()->attach([1, 2]);
            Package::where('ime', 'Light')->first()->functionalities()->attach([1, 2, 3, 4]);
            Package::where('ime', 'Pro')->first()->functionalities()->attach([1, 2, 3, 4, 5, 6, 7]);
            Package::where('ime', 'Premium')->first()->functionalities()->attach([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);


            foreach (Functionalities::all() as $functionality) {
                Package::where('ime', 'Platinum')->first()->functionalities()->attach([$functionality->id]);
            }


        }
    }
