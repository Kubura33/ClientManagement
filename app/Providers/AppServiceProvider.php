<?php

    namespace App\Providers;

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
            Gate::define('can-access', function ($user, $page) {
                if ($user->id == 1) {
                    return true;
                } else if ($user->id == 2){
                    $cantAccessPages = ["korisnici", "paketi"];

                    return !in_array($page, $cantAccessPages);
                }else if($user->id == 3){
                    $cantAccessPages = ["korisnici", "paketi"];

                    return !in_array($page, $cantAccessPages);
                }else if($user->id == 4){
                    $cantAccessPages = ["korisnici", "paketi", 'kreiraj'];
                    return !in_array($page, $cantAccessPages);
                }
        });
    }
    }
