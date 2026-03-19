<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Models\Cause;
use App\Models\Donation;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function create(string $slug)
    {
        $cause = Cause::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $clientRef = (string) Str::uuid();

        return view('donaciones.create', compact('cause', 'clientRef'));
    }

    public function store(StoreDonationRequest $request, string $slug)
    {
        $cause = Cause::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        Donation::firstOrCreate(
            ['client_ref' => $request->input('client_ref')],
            [
                'user_id' => auth()->id(),
                'cause_id' => $cause->id,
                'amount_mxn' => (float) $request->input('amount_mxn'),
                'message' => $request->input('message'),
                'status' => 'pending',
            ]
        );

        return redirect()
            ->route('donaciones.mine')
            ->with('success', 'Donativo registrado (pendiente). En Sprint 3 lo conectamos a Mercado Pago.');
    }

    public function mine()
    {
        $causes = Cause::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        $donations = Donation::query()
            ->where('user_id', auth()->id())
            ->with(['cause', 'payments'])
            ->latest()
            ->get();

        $totalsByCauseId = $donations
            ->groupBy('cause_id')
            ->map(fn ($group) => (float) $group->sum('amount_mxn'));

        return view('donaciones.mine', compact('causes', 'donations', 'totalsByCauseId'));
    }
}