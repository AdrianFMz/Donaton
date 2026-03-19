<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use Illuminate\Http\Request;

class CauseAdminController extends Controller
{
    public function index()
    {
        $causes = Cause::orderBy('title')->get();
        return view('admin.causes.index', compact('causes'));
    }

    public function edit(Cause $cause)
    {
        return view('admin.causes.edit', compact('cause'));
    }

    public function update(Request $request, Cause $cause)
    {
        $data = $request->validate([
            'title' => ['required','string','max:120'],
            'short_description' => ['required','string','max:300'],

            'problem_description' => ['nullable','string','max:5000'],
            'since_date' => ['nullable','date'],
            'use_of_funds' => ['nullable','string','max:5000'],
            'impact' => ['nullable','string','max:5000'],
            ]);

        $cause->update($data);

        return redirect()->route('admin.causes.index')
            ->with('success', 'Causa actualizada correctamente.');
                }
}