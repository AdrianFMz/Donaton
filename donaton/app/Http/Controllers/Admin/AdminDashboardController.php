<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Donation;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $causeId = $request->query('cause_id');
        $status  = $request->query('status'); // paid|pending|failed|cancelled
        $from    = $request->query('from');   // YYYY-MM-DD
        $to      = $request->query('to');     // YYYY-MM-DD

        $q = Donation::query()->with(['cause', 'user', 'payments']);

        if ($causeId) $q->where('cause_id', $causeId);
        if ($status)  $q->where('status', $status);
        if ($from)    $q->whereDate('created_at', '>=', $from);
        if ($to)      $q->whereDate('created_at', '<=', $to);

        // Tabla: últimos donativos
        $latest = (clone $q)->latest()->take(20)->get();

        // Métricas por causa (solo pagados)
        $byCause = Donation::query()
            ->selectRaw('cause_id, COUNT(*) as total_count, SUM(amount_mxn) as total_amount')
            ->where('status', 'paid')
            ->groupBy('cause_id')
            ->with('cause')
            ->get();

        $totalPaid = Donation::where('status', 'paid')->sum('amount_mxn');
        $countPaid = Donation::where('status', 'paid')->count();

        $causes = Cause::where('is_active', true)->orderBy('title')->get();

        return view('admin.dashboard', compact(
            'latest', 'byCause', 'totalPaid', 'countPaid', 'causes', 'causeId', 'status', 'from', 'to'
        ));
    }
}