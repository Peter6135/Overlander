<?php

namespace App\Http\Controllers;

class LocaleController extends Controller
{
    public function switch(string $locale)
    {
        session(['locale' => $locale]);

        return back();
    }
}
