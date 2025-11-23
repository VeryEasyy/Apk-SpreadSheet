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
                            data-sheet="{{ $sheet->id }}">
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

@push('scripts')
<script>
document.querySelectorAll('.cell').forEach(cell => {
    cell.addEventListener('blur', function () {
        let value = this.innerText;
        let cellName = this.dataset.cell;
        let sheetId = this.dataset.sheet;

        fetch("{{ route('reports.cell.update') }}", {
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
        });
    });
});
</script>
@endpush
