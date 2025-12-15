@php
    $lastSheet = $report->sheets->first();
    $lastCell = $lastSheet?->cells->sortByDesc('updated_at')->first();
@endphp

<tr>
    
    <td class="row-number">{{ $index }}</td>

    {{-- Report Title --}}
    <td>
        <div class="report-title">{{ $report->title }}</div>
    </td>

    {{-- Owner/Creator --}}
    <td>
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr($report->owner->name ?? 'U', 0, 1)) }}
            </div>
            <span class="user-name">{{ $report->owner->name ?? 'Tidak diketahui' }}</span>
        </div>
    </td>

    {{-- Created Date --}}
    <td>
        <div class="date-time-info">
            <span class="date-text">{{ $report->created_at->format('d M Y') }}</span>
            <span class="time-text">{{ $report->created_at->format('H:i') }}</span>
        </div>
    </td>

    {{-- Status --}}
    <td>
        @php
            // Ambil status dari session, jika tidak ada gunakan status default dari $report
            $sessionKey = "report_status_{$report->id}";
            $currentStatus = session($sessionKey, $report->status ?? 'draft');
        @endphp
        @include('dokumen.partials.status_badge', ['status' => $currentStatus])
    </td>

    {{-- Last Edited Date --}}
    <td>
        @if ($lastCell)
            <div class="date-time-info">
                <span class="date-text">{{ $lastCell->updated_at->format('d M Y') }}</span>
                <span class="time-text">{{ $lastCell->updated_at->format('H:i') }}</span>
            </div>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>

    {{-- Editor --}}
    <td>
        @if ($lastCell && $lastCell->updatedBy)
            <span class="editor-badge">
                <i class="bi bi-pencil"></i>
                {{ $lastCell->updatedBy->name }}
            </span>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>


    <td>
        <x-report-actions :report="$report" />
    </td>
</tr>
