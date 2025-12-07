<div class="dropdown" style="position: static;">
    <button class="dropdown-toggle-modern" 
            type="button" 
            id="dropdownMenu{{ $report->id }}"
            aria-expanded="false"
            style="position: relative;">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    
    <ul class="dropdown-menu-modern" 
        aria-labelledby="dropdownMenu{{ $report->id }}"
        style="position: fixed; z-index: 9999;">
        {{-- View Detail --}}
        <li>
            <a class="dropdown-item-modern" href="#" 
               data-bs-toggle="modal" 
               data-bs-target="#viewModal{{ $report->id }}">
                <i class="bi bi-eye icon-view"></i>
                <span>Lihat Detail</span>
            </a>
        </li>

        {{-- View Spreadsheet --}}
        <li>
            <a class="dropdown-item-modern" 
               href="{{ route('dokumen.laporan.sheet.view', $report->id) }}">
                <i class="bi bi-table icon-spreadsheet"></i>
                <span>View Spreadsheet</span>
            </a>
        </li>

        @if($isAdmin)
            {{-- Edit Spreadsheet --}}
            <li>
                <a class="dropdown-item-modern" 
                   href="{{ route('dokumen.laporan.sheet', $report->id) }}">
                    <i class="bi bi-grid-3x3-gap icon-spreadsheet"></i>
                    <span>Edit Spreadsheet</span>
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            {{-- Edit Report --}}
            <li>
                <a class="dropdown-item-modern" href="#" 
                   data-bs-toggle="modal" 
                   data-bs-target="#editModal{{ $report->id }}">
                    <i class="bi bi-pencil icon-edit"></i>
                    <span>Edit Laporan</span>
                </a>
            </li>

            {{-- Delete Report --}}
            <li>
                <a class="dropdown-item-modern" href="#" 
                   onclick="event.preventDefault(); hapusLaporan({{ $report->id }})">
                    <i class="bi bi-trash icon-delete"></i>
                    <span>Hapus Laporan</span>
                </a>
            </li>
        @endif
    </ul>

    {{-- Hidden Delete Form --}}
    <form id="delete-form-{{ $report->id }}"
          action="{{ route('dokumen.laporan.delete', $report->id) }}"
          method="POST" 
          style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>