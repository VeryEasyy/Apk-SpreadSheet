@extends('layouts.app')

@section('title', 'Spreadsheet - ' . $report->title)

@section('content')
<h4 class="fw-bold mb-3">{{ $report->title }} - Spreadsheet</h4>

<div class="spreadsheet-wrapper">
    <table class="table table-bordered spreadsheet-table">
        <thead>
            <tr>
                <th class="bg-light text-center">#</th>
                @foreach ($cols as $col)
                    <th class="text-center bg-light">{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <th class="bg-light text-center">{{ $row }}</th>
                    @foreach ($cols as $col)
                        @php
                            $cellId = $col.$row;
                            $value = $cells[$cellId]->value ?? '';
                        @endphp
                        <td contenteditable="true"
                            class="cell"
                            data-cell="{{ $cellId }}"
                            data-sheet-id="{{ $sheet->id }}">
                            {{ $value }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('styles')
<style>
.spreadsheet-wrapper {
    overflow-x: auto;
    background: #fff;
    padding: 15px;
}

.spreadsheet-table {
    border-collapse: collapse;
    min-width: 900px;
}

.spreadsheet-table td,
.spreadsheet-table th {
    min-width: 100px;
    height: 35px;
}

.spreadsheet-table td.cell:focus {
    outline: 2px solid #0d6efd;
    background: #eef5ff;
}
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.cell').forEach(cell => {

        cell.addEventListener('blur', function () {
            saveCell(this);
        });

        cell.addEventListener('keydown', function(e) {

            if (e.key === 'Enter') {
                e.preventDefault();
                saveCell(this);

                let cellName = this.dataset.cell; 
                let row = parseInt(cellName.match(/\d+/)[0]);
                let col = cellName.replace(row, '');

                let nextRow = row + 1;
                let nextCell = document.querySelector(`[data-cell="${col}${nextRow}"]`);

                if (nextCell) {
                    nextCell.focus();
                }
            }

            if (e.key === 'Tab') {
                e.preventDefault();
                saveCell(this);

                let cellName = this.dataset.cell;
                let row = parseInt(cellName.match(/\d+/)[0]);
                let col = cellName.replace(row, '');

                let nextCol = String.fromCharCode(col.charCodeAt(0) + 1);
                let nextCell = document.querySelector(`[data-cell="${nextCol}${row}"]`);

                if (nextCell) {
                    nextCell.focus();
                }
            }

        });

    });

});

function saveCell(cell) {

    let value    = cell.innerText.trim();
    let cellName = cell.dataset.cell;
    let sheetId  = cell.dataset.sheetId;

    console.log("SENDING:", sheetId, cellName, value);

    fetch("{{ route('laporan.cell.update') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN' : '{{ csrf_token() }}',
            'Content-Type' : 'application/json'
        },
        body: JSON.stringify({
            sheet_id: sheetId,
            cell: cellName,
            value: value
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log('SERVER:', data);
    })
    .catch(err => console.error('SERVER ERROR:', err));
}
</script>




