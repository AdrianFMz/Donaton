<?php

namespace App\Http\Controllers;

use App\Models\Cause;

class DonationController extends Controller
{
    public function create(string $slug)
    {
        $cause = Cause::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('donaciones.create', compact('cause'));
    }
}