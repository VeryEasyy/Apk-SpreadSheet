<?php

namespace App\Http\Controllers\Dokument;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Report_Cell;
use App\Models\Report_Sheets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // Halaman Laporan
    public function index()
    {

         $laporan = Report::with([
        'owner',
        'sheets.cells.updatedBy' 
    ])->latest()->get();

        return view('dokumen.laporan.index', compact('laporan'));
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
            'report', 'sheet', 'cells', 'cols', 'rows'
        ));
    }



    //////////////////
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


}
