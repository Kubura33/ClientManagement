<?php

    namespace App\Http\Controllers;

    use App\Models\Market;
    use App\Models\Role;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Gate;
    use Illuminate\Support\Facades\Hash;
    use Inertia\Inertia;
    use Mockery\Exception;

    class UserController extends Controller
    {
        public function create()
        {
            if (!Gate::allows('can-access', 'korisnici')) {
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $roles = Role::all();
            return Inertia::render('Users/AddNewUser', ['roles' => $roles]);
        }

        public function store(Request $request)
        {
            if (!Gate::allows('can-access', 'korisnici')) {
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
                'role' => ['required'],

            ]);


            try {
                $user = User::create([
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'role_id' => $request->role
                ]);


            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Korisnik nije dodat, pokusajte ponovo');
            }

            return redirect()->route('dashboard')->with('success', 'Korisnik uspesno kreiran');
        }

        public function show()
        {
            if (!Gate::allows('can-access', 'korisnici')) {
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $users = User::where('role_id', '!=', 1)->get();
            $users->load(['role', 'markets']);
            $roles = Role::all();
            $trzista = Market::all();
            return Inertia::render('Users/UserShow', [
                'users' => $users,
                'trzista' => $trzista,
                'roles' => $roles
            ]);
        }

        public function update(Request $request)
        {
            if (!Gate::allows('can-access', 'korisnici')) {
                return redirect()->route('dashboard')->with('error', "Ne mozete da pristupiti ovoj stranici");
            }
            $users = $request->data;

            // Fetch all users to be updated
            $usersToUpdate = User::whereIn('id', collect($users)->pluck('id'))->get();

            foreach ($usersToUpdate as $userToEdit) {
                $userData = collect($users)->firstWhere('id', $userToEdit->id);

                $userToEdit->update([
                    'role_id' => $userData['role_id']
                ]);

                // Sync markets instead of detach/attach
                $marketIds = collect($userData['markets'])->pluck('id');
                $userToEdit->markets()->sync($marketIds);
            }
            return redirect()->back()->with('success', "Izmena je uspesna");
        }

        public function delete()
        {

        }
    }
