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
                    'email' => 'superadmin@example.com',
                    'username' => 'Super Admin',
                    'password' => Hash::make('password')],
                ['role_id' => 2,
                    'email' => 'admin@example.com',
                    'username' => 'Obican Admin',
                    'password' => Hash::make('password')],
                ['role_id' => 3,
                    'email' => 'b2b@example.com',
                    'username' => 'B2B Agent',
                    'password' => Hash::make('password')],
                ['role_id' => 4,
                    'email' => 'user@example.com',
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
            Package::insert(
                [
                    [
                        'ime' => 'Basic',
                        'cena' => '39',
                        'broj_besplatnih_instalacija_godisnje' => 1
                    ],
                    [
                        'ime' => 'Light',
                        'cena' => '49',
                        'broj_besplatnih_instalacija_godisnje' => 2
                    ],
                    [
                        'ime' => 'Pro',
                        'cena' => '59',
                        'broj_besplatnih_instalacija_godisnje' => 3
                    ],
                    [
                        'ime' => 'Premium',
                        'cena' => '69',
                        'broj_besplatnih_instalacija_godisnje' => 4
                    ],
                    [
                        'ime' => 'Platinum',
                        'cena' => '99',
                        'broj_besplatnih_instalacija_godisnje' => 5
                    ]

                ]
            );
            Functionalities::insert([
                ['funkcionalnost' => 'Automatsko osvežavanje podataka'],
                ['funkcionalnost' => 'Automatski eksport izvoda (txt, xml i mt490 format)'],
                ['funkcionalnost' => 'Automatski eksport izvoda u PDF formatu'],
                ['funkcionalnost' => 'Automatski import naloga'],
                ['funkcionalnost' => 'Automatski eksport dnevnih uplata'],
                ['funkcionalnost' => 'Automatski eksport stanja'],
                ['funkcionalnost' => 'Automatski eksport kursnih lista'],
                ['funkcionalnost' => 'Automatsko razvrstavanje izvoda'],
                ['funkcionalnost' => 'Kriptovani automatski import naloga'],
                ['funkcionalnost' => 'Zaključavanje naloga'],
                ['funkcionalnost' => 'Email notifikacije o prilivima'],
                ['funkcionalnost' => 'Excel izveštaj o prometu i stanju po računima'],
                ['funkcionalnost' => 'Automatsko potpisivanje naloga'],
                ['funkcionalnost' => 'Automatsko slanje potpisanih naloga'],
                ['funkcionalnost' => 'Integracija sa Web Service-om / API-jem'],
                ['funkcionalnost' => 'Zaključvanje odredjenih polja po nalogu'],
                ['funkcionalnost' => 'Email notifikacije o izvodima i stanjima'],
            ]);
            Package::where('ime', 'Basic')->first()->functionalities()->attach([1, 2]);
            Package::where('ime', 'Light')->first()->functionalities()->attach([1, 2, 3, 4]);
            Package::where('ime', 'Pro')->first()->functionalities()->attach([1, 2, 3, 4, 5, 6, 7]);
            Package::where('ime', 'Premium')->first()->functionalities()->attach([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);

            foreach (Functionalities::all() as $functionality) {
                Package::where('ime', 'Platinum')->first()->functionalities()->attach([$functionality->id]);
            }
            Package::where('ime', 'Basic')->first()->market()->attach([1, 2, 3]);
            Package::where('ime', 'Light')->first()->market()->attach([1, 2, 3]);
            Package::where('ime', 'Pro')->first()->market()->attach([1, 2, 3]);
            Package::where('ime', 'Premium')->first()->market()->attach([1, 2, 3]);
            Package::where('ime', 'Platinum')->first()->market()->attach([1, 2, 3]);


        }
    }
