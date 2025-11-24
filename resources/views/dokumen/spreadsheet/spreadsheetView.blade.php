@extends('layouts.app')

@section('title','View Spreadsheet - ' . $report->title)

@section('content')
<h4 class="fw-bold mb-3">{{ $report->title }} - View Sheet</h4>

<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            @foreach($cols as $col)
                <th class="text-center bg-light">{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <tr>
            <th class="bg-light text-center">{{ $row }}</th>
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
    <a href="{{ route('dokumen.laporan.sheet', $report->id) }}"
        class="btn btn-success mt-3">
        Edit Spreadsheet
    </a>
@endif

@endsection
