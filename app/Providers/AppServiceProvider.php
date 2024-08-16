<?php

    namespace App\Providers;

    use App\Models\Contract;
    use App\Observers\ContractObserver;
    use Illuminate\Support\Facades\Gate;
    use Illuminate\Support\ServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            //
        }

        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            Contract::observe(ContractObserver::class);
            Gate::define('can-access', function ($user, $page) {
                if ($user->role_id == 1) {
                    return true;
                } else if ($user->role_id == 2){
                    $cantAccessPages = ["korisnici", "paketi", 'kreiraj-trziste'];

                    return !in_array($page, $cantAccessPages);
                }else if($user->role_id == 3){
                    $cantAccessPages = ["korisnici", "paketi", 'kreiraj-trziste'];

                    return !in_array($page, $cantAccessPages);
                }else if($user->role_id == 4){
                    $cantAccessPages = ["korisnici", "paketi", 'kreiraj', 'kreiraj-trziste'];
                    return !in_array($page, $cantAccessPages);
                }
            });

            Gate::define('has-market', function ($user, $trziste) {
               if($user->role_id == 1){
                   return true;
               } else {
                   return $user->markets()->where('markets.id', $trziste)->exists(); // Check if the user is associated with the specific market

               }
            });
    }
    }
