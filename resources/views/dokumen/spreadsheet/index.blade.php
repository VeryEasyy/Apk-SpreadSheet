@extends('layouts.app')

@section('title', 'Spreadsheet - ' . $report->title)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/components/spreadsheet.css') }}">
@endpush

@section('content')
<div class="spreadsheet-container">
    {{-- Header Section --}}
    <div class="spreadsheet-header">
        <div class="header-content">
            <div class="title-section">
                <button class="btn-back" onclick="window.history.back()" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <i class="bi bi-table"></i>
                <h4 class="title">{{ $report->title }}</h4>
            </div>
            <div class="action-buttons">
                <button class="btn-action" id="importExcel" title="Import Excel">
                    <i class="bi bi-upload"></i>
                </button>
                <button class="btn-action" id="exportExcel" title="Export Excel">
                    <i class="bi bi-download"></i>
                </button>
                <button class="btn-action" onclick="window.print()" title="Print">
                    <i class="bi bi-printer"></i>
                </button>
                <button class="btn-action" id="addColumn" title="Tambah Kolom">
                    <i class="bi bi-plus-lg"></i> Col
                </button>
                <button class="btn-action" id="addRow" title="Tambah Baris">
                    <i class="bi bi-plus-lg"></i> Row
                </button>
            </div>
        </div>
    </div>

    {{-- Spreadsheet Wrapper --}}
    <div class="spreadsheet-wrapper">
        <div class="table-container">
            <table class="spreadsheet-table" id="spreadsheetTable">
                <thead>
                    <tr>
                        <th class="row-header corner-cell">#</th>
                        @foreach ($cols as $col)
                            <th class="col-header" data-col="{{ $col }}">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <th class="row-header" data-row="{{ $row }}">{{ $row }}</th>
                            @foreach ($cols as $col)
                                @php
                                    $cellId = $col.$row;
                                    $value = $cells[$cellId]->value ?? '';
                                @endphp
                                <td contenteditable="true"
                                    class="cell"
                                    data-cell="{{ $cellId }}"
                                    data-row="{{ $row }}"
                                    data-col="{{ $col }}"
                                    data-sheet-id="{{ $sheet->id }}"
                                    spellcheck="false">{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer Info --}}
    <div class="spreadsheet-footer">
        <div class="cell-info">
            <span class="active-cell">Cell: <strong>-</strong></span>
            <span class="selection-info" style="margin-left: 20px; display: none;">
                <i class="bi bi-check-square"></i> <strong class="selected-count">0</strong> cells selected
            </span>
        </div>
        <div class="status-info">
            <span class="status-indicator">
                <i class="bi bi-check-circle-fill text-success"></i> Auto-saved
            </span>
        </div>
    </div>

    {{-- Hidden File Input for Import --}}
    <input type="file" id="excelFileInput" accept=".xlsx,.xls" style="display: none;">
</div>
@endsection

@push('scripts')
{{-- SheetJS Library for Excel Export/Import --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="{{ asset('js/components/spreadsheet.js') }}"></script>
<script>
    // Initialize spreadsheet with configuration
    const spreadsheetConfig = {
        updateRoute: "{{ route('laporan.cell.update') }}",
        csrfToken: "{{ csrf_token() }}",
        reportTitle: "{{ $report->title }}",
        sheetId: "{{ $sheet->id }}"
    };
</script>
@endpush