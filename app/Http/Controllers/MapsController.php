<?php

namespace App\Http\Controllers;

use App\Jobs\GetMapImages;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class MapsController extends Controller
{
    public function show_form(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('main');
    }

    public function main(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        GetMapImages::dispatch($request->all());
        return view('main')->with('success', 'ok');
    }
}
