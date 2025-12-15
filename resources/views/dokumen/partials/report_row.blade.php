@php
    $lastLog = $item->logs->sortByDesc('created_at')->first();
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
        @if($lastLog)
            {{ \Carbon\Carbon::parse($lastLog->created_at)->format('d M Y H:i') }}
        @else
            <span class="text-muted">-</span>
        @endif
    </td>

    {{-- Editor --}}
    <td>
        @if($lastLog)
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr($lastLog->editor->name ?? 'U', 0, 1)) }}
                </div>
                <span class="user-name">{{ $lastLog->editor->name ?? 'Tidak diketahui' }}</span>
            </div>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td>
         @if($lastLog)
            <span class="text-muted">
                {{ $lastLog->editor->name }}
                mengubah sel <strong>{{ $lastLog->cell }}</strong><br>
                dari "<em>{{ $lastLog->old_value ?? '-' }}</em>"
                menjadi "<em>{{ $lastLog->new_value ?? '-' }}</em>"
            </span>
        @else
            <span class="text-muted">Belum ada perubahan</span>
        @endif
    </td>


    <td>
        <x-report-actions :report="$report" />
    </td>
</tr>
