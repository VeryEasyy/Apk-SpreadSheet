<?php

namespace App\Http\Controllers\Dokument;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report_Lock;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportLockController extends Controller
{
        public function lock(Request $request)
    {
        $request->validate([
            'sheets_id' => 'required|integer',
            'cell' => 'required|string|max:10',
        ]);

        // Auto release lock jika sudah lebih dari 1 menit
        Report_Lock::where('sheets_id', $request->sheet_id)
            ->where('locked_at', '<', Carbon::now()->subMinute())
            ->delete();


        // cek cell sudah dikunci atau belum
        $existing = Report_Lock::where('sheets_id', $request->sheets_id)
            ->where('cell', $request->cell)
            ->first();

        // Jika sel sudah dikunci user lain
        if ($existing && $existing->locked_by != Auth::id()) {
            return response()->json([
                'status' => 'locked',
                'message' => "Sel sedang diedit oleh {$existing->user->name}",
                'locked_by' => $existing->user->name,
                'locked_at' => $existing->locked_at
            ], 423); // 423 = Locked
        }

        // Jika sel sudah dikunci oleh user yang sama → update time
        if ($existing && $existing->locked_by == Auth::id()) {
            $existing->update(['locked_at' => now()]);
            return response()->json(['status' => 'updated']);
        }

        // Buat lock baru
        Report_Lock::create([
            'sheets_id' => $request->sheets_id,
            'cell' => $request->cell,
            'locked_by' => Auth::id(),
            'locked_at' => now()
        ]);

        return response()->json(['status' => 'locked']);
    }

    /**
     * Unlock a cell
     */
    public function unlock(Request $request)
    {
        $request->validate([
            'sheets_id' => 'required|integer',
            'cell' => 'required|string|max:10',
        ]);

        Report_Lock::where('sheets_id', $request->sheets_id)
            ->where('cell', $request->cell)
            ->where('locked_by', Auth::id())
            ->delete();

        return response()->json(['status' => 'unlocked']);
    }

    /**
     * Check lock status of a cell
     */
    public function check(Request $request)
    {
        $request->validate([
            'sheets_id' => 'required|integer',
            'cell' => 'required|string|max:10',
        ]);

        $Lock = Report_Lock::where('sheets_id', $request->sheets_id)
            ->where('cell', $request->cell)
            ->first();

        if (!$Lock) {
            return response()->json(['locked' => false]);
        }

        return response()->json([
            'locked' => true,
            'locked_by' => $Lock->user->name,
            'locked_at' => $Lock->locked_at,
            'is_mine' => $Lock->locked_by == Auth::id()
        ]);
    }
}
