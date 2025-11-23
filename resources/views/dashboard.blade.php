@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<h3 class="fw-bold">Dashboard Laporan</h3>
<p class="text-muted">Selamat datang kembali di sistem laporan spreadsheet.</p>

<div class="row mt-4">

    {{-- Total Reports --}}
    <div class="col-md-3 mb-3">
        <div class="card p-3 shadow-sm border-0">
            <h6 class="text-muted">Total Reports</h6>
            <h3 class="fw-bold">15</h3>
            <span class="badge bg-primary">+2 minggu ini</span>
        </div>
    </div>

    {{-- Total Sheets --}}
    <div class="col-md-3 mb-3">
        <div class="card p-3 shadow-sm border-0">
            <h6 class="text-muted">Total Sheets</h6>
            <h3 class="fw-bold">87</h3>
            <span class="badge bg-success">Stabil</span>
        </div>
    </div>

    {{-- Edit Logs Today --}}
    <div class="col-md-3 mb-3">
        <div class="card p-3 shadow-sm border-0">
            <h6 class="text-muted">Edits Today</h6>
            <h3 class="fw-bold">124</h3>
            <span class="badge bg-warning text-dark">Aktif</span>
        </div>
    </div>

    {{-- Active Locks --}}
    <div class="col-md-3 mb-3">
        <div class="card p-3 shadow-sm border-0">
            <h6 class="text-muted">Locked Cells</h6>
            <h3 class="fw-bold">8</h3>
            <span class="badge bg-danger">Editing</span>
        </div>
    </div>

</div>


{{-- Edit Activity Chart Placeholder --}}
<div class="card p-4 mt-4 shadow-sm border-0">
    <h4 class="mb-2">Aktivitas Edit Mingguan</h4>
    <p class="text-muted">Statistik edit sel untuk 7 hari terakhir</p>

    <div class="text-center py-5 bg-light rounded" style="height: 200px;">
        <p class="text-muted">[ Grafik Line Chart Placeholder ]</p>
    </div>
</div>


{{-- Recent Report List --}}
<div class="card p-4 mt-4 shadow-sm border-0">
    <h4 class="mb-3">Daftar Report Terbaru</h4>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nama Report</th>
                <th>Jumlah Sheet</th>
                <th>Last Edited</th>
                <th>Progress</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Monthly Production Report</td>
                <td>12 Sheet</td>
                <td>2 jam lalu</td>
                <td>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 72%;"></div>
                    </div>
                </td>
                <td><button class="btn btn-primary btn-sm">Open</button></td>
            </tr>

            <tr>
                <td>Maintenance Check Report</td>
                <td>8 Sheet</td>
                <td>Kemarin</td>
                <td>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 49%;"></div>
                    </div>
                </td>
                <td><button class="btn btn-primary btn-sm">Open</button></td>
            </tr>

            <tr>
                <td>Daily Logs Report</td>
                <td>15 Sheet</td>
                <td>5 hari lalu</td>
                <td>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: 30%;"></div>
                    </div>
                </td>
                <td><button class="btn btn-primary btn-sm">Open</button></td>
            </tr>
        </tbody>
    </table>
</div>


{{-- Recent Edit Activity --}}
<div class="card p-4 mt-4 shadow-sm border-0">
    <h4 class="mb-3">Aktivitas Terbaru</h4>

    <ul class="list-group">
        <li class="list-group-item">
            <strong>Agis</strong> mengedit <strong>Cell B12</strong> pada Sheet 3 — <span class="text-muted">10 detik lalu</span>
        </li>
        <li class="list-group-item">
            <strong>Yoga</strong> mengunci <strong>Cell A4</strong> — <span class="text-muted">2 menit lalu</span>
        </li>
        <li class="list-group-item">
            <strong>Rendy</strong> mengubah nilai <strong>C9</strong> di Sheet 2 — <span class="text-muted">11 menit lalu</span>
        </li>
    </ul>
</div>


{{-- Exported Files --}}
<div class="card p-4 mt-4 shadow-sm border-0 mb-5">
    <h4 class="mb-3">File Export Terbaru</h4>

    <table class="table table-striped">
        <tr>
            <th>File</th>
            <th>Type</th>
            <th>Dibuat Oleh</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>

        <tr>
            <td>monthly_report.xlsx</td>
            <td><span class="badge bg-success">XLSX</span></td>
            <td>Agis</td>
            <td>2025-01-10</td>
            <td><button class="btn btn-outline-primary btn-sm">Download</button></td>
        </tr>

        <tr>
            <td>daily_log.pdf</td>
            <td><span class="badge bg-danger">PDF</span></td>
            <td>Yoga</td>
            <td>2025-01-09</td>
            <td><button class="btn btn-outline-primary btn-sm">Download</button></td>
        </tr>

    </table>
</div>

@endsection
