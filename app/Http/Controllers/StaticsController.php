<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class StaticsController extends Controller
{
    /**
     * The welcome page
     *
     * @return Factory|View|Application
     */
    public function welcome(): Factory|View|Application
    {
        return view('welcome');
    }

    /**
     * The changelog page
     *
     * @return Factory|View|Application
     */
    public function changelog(): Factory|View|Application
    {
        return view('changelog');
    }


    public function limits()
    {
        return response()->json(
            config('pr0verter')
        );
    }
}
