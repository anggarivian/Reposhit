<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
public function boot()
{
    View::composer('*', function ($view) {
        $user = Auth::user();
        $notifikasis = collect();
        $notifikasiCount = 0;

        if ($user && $user->roles_id === 2) {
            $mahasiswaId = $user->id;
            $notifikasis = Notifikasi::where('mahasiswa_id', $mahasiswaId)->latest()->get();

            $notifikasiCount = Notifikasi::where('mahasiswa_id', $mahasiswaId)->count();
        }

        $view->with(compact('notifikasis', 'notifikasiCount', 'user'));
    });
}
}
