<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConverterController extends Controller
{
    /**
     * @param string $id
     * @return Factory|View|Application
     */
    public function show(string $id): Factory|View|Application
    {
        return view('converter.show');
    }

    /**
     * @param string $id
     * @return Factory|View|Application
     */
    public function progress(string $id): Factory|View|Application
    {
        return view('converter.progress');
    }

    /**
     * @return Factory|View|Application
     */
    public function home(): Factory|View|Application
    {
        return view('converter.home');
    }

    /**
     * @param string $id
     * @return void
     */
    public function download(string $id)
    {
        return;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function load(Request $request): RedirectResponse
    {
        return redirect()->route('progress');
    }

}
