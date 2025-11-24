@extends('layouts.app')

@section('title','View Spreadsheet - ' . $report->title)

@section('content')

<style>
    .report-title {
        text-align: center;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 5px;
    }

    .report-subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 20px;
    }

    .spreadsheet-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .spreadsheet-table th,
    .spreadsheet-table td {
        border: 1px solid #333;
        padding: 6px;
        text-align: center;
    }

    .spreadsheet-table thead th {
        background: #f1f1f1;
        font-weight: bold;
    }

    .row-header {
        background: #f8f8f8;
        font-weight: bold;
        width: 50px;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .spreadsheet-table {
            font-size: 11px;
        }
    }
</style>

<div class="container">

    <div class="report-title">{{ $report->title }}</div>
    <div class="report-subtitle">Spreadsheet Report</div>

    <div class="table-responsive">
        <table class="spreadsheet-table">
            <thead>
                <tr>
                    <th>#</th>
                    @foreach($cols as $col)
                        <th>{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td class="row-header">{{ $row }}</td>

                        @foreach($cols as $col)
                            @php
                                $cellId = $col.$row;
                                $value = $cells[$cellId]->value ?? '';
                            @endphp

                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    @if(auth()->user()->role === 'admin')
        <div class="no-print mt-4 text-center">
            <a href="{{ route('dokumen.laporan.sheet.view', $report->id) }}"
                class="btn btn-success">
                Spreadsheet
            </a>

            <a href="{{ route('dokumen.laporan.sheet.pdf', $report->id) }}"
                class="btn btn-danger ms-2" target="_blank">
                PDF
            </a>
        </div>
    @endif

</div>

@endsection
