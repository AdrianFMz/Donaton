<?php

namespace App\Http\Controllers;

use App\Models\Cause;

class CauseController extends Controller
{
    public function index()
    {
        $causes = Cause::query()->where('is_active', true)->orderBy('title')->get();
        return view('causas.index', compact('causes'));
    }

    public function show(string $slug)
    {
        $cause = Cause::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('causas.show', compact('cause'));
    }
}