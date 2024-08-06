<?php

    namespace App\Http\Controllers;

    use App\Models\Role;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Log;
    use Inertia\Inertia;
    use Mockery\Exception;
    use Illuminate\Support\Str;

    class UserController extends Controller
    {
        public function create()
        {
            $roles = Role::all();
            return Inertia::render('Users/AddNewUser', ['roles' => $roles]);
        }

        public function store(Request $request)
        {
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'role' => ['required'],

            ]);
            $password = Str::random(12);
            Log::info($password);

            try{
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($password),
                    'role_id' => $request->role,]);


            }catch (Exception $e){
                return redirect()->back()->with('error', 'Korisnik nije dodat, pokusajte ponovo');
            }

            return redirect()->route('dashboard')->with('success', 'Korisnik uspesno kreiran');
        }

        public function delete()
        {

        }
    }
