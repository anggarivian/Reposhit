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

        if ($user && $user->roles_id == 2 && $user->mahasiswa) {
            $mahasiswaId = $user->mahasiswa->id;
            $notifikasis = Notifikasi::where('mahasiswa_id', $mahasiswaId)
                            ->latest()
                            ->take(5)
                            ->get();

            $notifikasiCount = Notifikasi::where('mahasiswa_id', $mahasiswaId)->count();
        }

        $view->with([
            'notifikasis' => $notifikasis,
            'notifikasiCount' => $notifikasiCount,
        ]);
    });
}
}
