<?php

namespace App\Http\Controllers\Dokument;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Report;
use App\Models\Report_Cell;
use App\Models\Report_Edit_Log;
use App\Models\Report_Sheets;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // Halaman Laporan
    public function index()
    {
        $users = User::orderBy('name')->get();

        $query = Report::with([
            'owner',
            'sheets.cells.updatedBy',
            'logs',
            'logs.editor'
        ])->latest();


        // filter judul
        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        // filter status    
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // filter pembuat
        if (request('owner')) {
            $query->where('owner_id', request('owner'));
        }

        $laporan = $query->get();


        return view('dokumen.laporan.index', compact('laporan', 'users'));
    }

    // tambah data laporan 
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:draft,published,archived'
        ]);

        $report = Report::create([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status,
            'owner_id'    => Auth::id(),
        ]);

        Report_Sheets::create([
            'report_id' => $report->id,
            'sheet_name' => 'Sheet 1',
            'order'      => 1
        ]);

        return redirect()->route('dokumen.laporan')
            ->with('success', 'Laporan berhasil ditambahkan!');
    }

    // UPDATE DATA Laporan
    public function update(Request $request, $id)
    {
        $laporan = Report::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:draft,published,archived'
        ]);

        $laporan->update([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil diupdate');
    }



    // hapus data Laporan
    public function delete($id)
    {
        Report::findOrFail($id)->delete();

        return redirect()->route('dokumen.laporan')
            ->with('success', 'Laporan berhasil dihapus!');
    }


    // =========================
    // SPREADSHEET
    // =========================

    // view Spreadsheet 
    public function sheet($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $report = Report::findOrFail($id);

        $sheet = $report->sheets()->firstOrCreate([
            'sheet_name' => 'Sheet 1'
        ], [
            'order' => 1
        ]);

        $cells = Report_Cell::where('sheet_id', $sheet->id)->get()->keyBy('cell');

        $cols = range('A', 'J'); // 10 kolom
        $rows = range(1, 20);     // 20 baris

        return view('dokumen.spreadsheet.index', compact(
            'report',
            'sheet',
            'cells',
            'cols',
            'rows'
        ));
    }

    // =========================
    // VIEW (readonly) Spreadsheet
    // =========================
    public function viewSheet($id)
    {
        $report = Report::with('sheets.cells')->findOrFail($id);

        $sheet = $report->sheets()->firstOrFail();

        $cells = Report_Cell::where('sheet_id', $sheet->id)
            ->get()
            ->keyBy('cell');

        $cols = range('A', 'J');
        $rows = range(1, 20);

        return view('dokumen.spreadsheet.spreadsheetView', compact(
            'report',
            'sheet',
            'cells',
            'cols',
            'rows'
        ));
    }

    // =========================
    // EXPORT PDF
    // =========================
    public function exportPdf($id)
    {
        $report = Report::with('sheets.cells')->findOrFail($id);
        $sheet = $report->sheets()->firstOrFail();

        $cells = Report_Cell::where('sheet_id', $sheet->id)
            ->get()
            ->keyBy('cell');

        $cols = range('A', 'J');
        $rows = range(1, 20);

        $pdf = PDF::loadView('dokumen.spreadsheet.viewPdf', compact(
            'report',
            'sheet',
            'cells',
            'cols',
            'rows'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream($report->title . '.pdf');
    }




    // CELL
    /////////////////

    // update data cell
    public function updateCell(Request $request)
    {

        $request->validate([
            'sheet_id' => 'required',
            'cell' => 'required',
            'value' => 'nullable'
        ]);

        $CekCell = Report_Cell::where('sheet_id', $request->sheet_id)
            ->where('cell', $request->cell)->first();


        $OldValue = $CekCell->value ?? null;

        // simpan perubahan old value ke new value
        if ($OldValue !== $request->value) {

            // ambil sheet untuk dapet report id
            $sheet = Report_Sheets::find($request->sheet_id);


            Report_Edit_Log::create([
                'report_id' => $sheet->report_id,
                'sheets_id' => $request->sheet_id,
                'cell'      => $request->cell,
                'old_value' => $OldValue,
                'new_value' => $request->value,
                'edited_by' => Auth::id(),
            ]);
        }


        // simpan atau update tabel cell utama
        Report_Cell::updateOrCreate(
            [
                'sheet_id' => $request->sheet_id,
                'cell' => $request->cell,
            ],
            [
                'value' => $request->value,
                'updated_by' => Auth::id()
            ]
        );

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'status' => 'required|in:draft,published,archived'
        ]);

        $status = $validated['status'];

        // Simpan status ke session (untuk sementara, tidak ke database)
        $sessionKey = "report_status_{$id}";
        session([$sessionKey => $status]);

        // Label status untuk notifikasi
        $statusLabels = [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived'
        ];

        // Redirect kembali ke halaman laporan dengan notifikasi
        return redirect()->route('dokumen.laporan')
            ->with('success', 'Laporan berhasil disimpan sebagai ' . $statusLabels[$status]);
    }
}
