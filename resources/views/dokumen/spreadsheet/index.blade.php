@extends('layouts.app')

@section('title', 'Spreadsheet - ' . $report->title)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/components/spreadsheet.css') }}">

@endpush

@section('content')
{{-- Hidden Flash Messages for Global Toast
@if (session('success'))
    <div data-success-message="{{ session('success') }}" style="display: none;"></div>
@endif

@if (session('error'))
    <div data-error-message="{{ session('error') }}" style="display: none;"></div>
@endif --}}

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
                {{-- Save Dropdown --}}
                <div class="save-dropdown" id="saveDropdown">
                    <button class="btn-save-main" onclick="toggleSaveDropdown(event)">
                        <i class="bi bi-floppy"></i>
                        <span>Save</span>
                        <i class="bi bi-chevron-down" style="font-size: 12px;"></i>
                    </button>
                    <div class="save-dropdown-menu">
                        <button class="save-dropdown-item draft" onclick="saveWithStatus('draft')">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Save as Draft</span>
                        </button>
                        <button class="save-dropdown-item published" onclick="saveWithStatus('published')">
                            <i class="bi bi-check-circle"></i>
                            <span>Save as Published</span>
                        </button>
                        <button class="save-dropdown-item archived" onclick="saveWithStatus('archived')">
                            <i class="bi bi-archive"></i>
                            <span>Save as Archived</span>
                        </button>
                    </div>
                </div>
                
                <div style="width: 2px; height: 30px; background: #e0e0e0; margin: 0 8px;"></div>
                
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

    {{-- Formatting Toolbar --}}
    <div class="formatting-toolbar">
        <div class="toolbar-group">
            <label class="toolbar-label">Font:</label>
            <select class="toolbar-select" id="fontFamily">
                <option value="Poppins">Poppins</option>
                <option value="Inter">Inter</option>
                <option value="Arial">Arial</option>
                <option value="Times New Roman">Times New Roman</option>
                <option value="Courier New">Courier New</option>
                <option value="Georgia">Georgia</option>
                <option value="Verdana">Verdana</option>
            </select>
            <select class="toolbar-select" id="fontSize">
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="14" selected>14</option>
                <option value="16">16</option>
                <option value="18">18</option>
                <option value="20">20</option>
                <option value="24">24</option>
                <option value="28">28</option>
                <option value="32">32</option>
            </select>
        </div>

        <div class="toolbar-divider"></div>

        <div class="toolbar-group">
            <button class="toolbar-btn" id="boldBtn" title="Bold (Ctrl+B)" data-format="bold">
                <i class="bi bi-type-bold"></i>
            </button>
            <button class="toolbar-btn" id="italicBtn" title="Italic (Ctrl+I)" data-format="italic">
                <i class="bi bi-type-italic"></i>
            </button>
            <button class="toolbar-btn" id="underlineBtn" title="Underline (Ctrl+U)" data-format="underline">
                <i class="bi bi-type-underline"></i>
            </button>
            <button class="toolbar-btn" id="strikethroughBtn" title="Strikethrough" data-format="strikethrough">
                <i class="bi bi-type-strikethrough"></i>
            </button>
        </div>

        <div class="toolbar-divider"></div>

        <div class="toolbar-group">
            <button class="toolbar-btn" id="textColorBtn" title="Text Color">
                <i class="bi bi-fonts"></i>
                <input type="color" id="textColor" value="#000000" style="display: none;">
            </button>
            <button class="toolbar-btn" id="bgColorBtn" title="Background Color">
                <i class="bi bi-paint-bucket"></i>
                <input type="color" id="bgColor" value="#ffffff" style="display: none;">
            </button>
        </div>

        <div class="toolbar-divider"></div>

        <div class="toolbar-group">
            <button class="toolbar-btn" id="alignLeftBtn" title="Align Left" data-align="left">
                <i class="bi bi-text-left"></i>
            </button>
            <button class="toolbar-btn" id="alignCenterBtn" title="Align Center" data-align="center">
                <i class="bi bi-text-center"></i>
            </button>
            <button class="toolbar-btn" id="alignRightBtn" title="Align Right" data-align="right">
                <i class="bi bi-text-right"></i>
            </button>
            <button class="toolbar-btn" id="alignJustifyBtn" title="Justify" data-align="justify">
                <i class="bi bi-justify"></i>
            </button>
        </div>

        <div class="toolbar-divider"></div>

        <div class="toolbar-group">
            <button class="toolbar-btn" id="verticalTopBtn" title="Vertical Top" data-valign="top">
                <i class="bi bi-align-top"></i>
            </button>
            <button class="toolbar-btn" id="verticalMiddleBtn" title="Vertical Middle" data-valign="middle">
                <i class="bi bi-align-middle"></i>
            </button>
            <button class="toolbar-btn" id="verticalBottomBtn" title="Vertical Bottom" data-valign="bottom">
                <i class="bi bi-align-bottom"></i>
            </button>
        </div>

        <div class="toolbar-divider"></div>

        <div class="toolbar-group">
            <button class="toolbar-btn" id="clearFormatBtn" title="Clear Formatting">
                <i class="bi bi-eraser"></i>
            </button>
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

    {{-- Hidden Form for Status Update --}}
    <form id="statusForm" action="{{ route('dokumen.laporan.update-status', $report->id) }}" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="statusInput">
    </form>

    {{-- Context Menu --}}
    <div class="context-menu" id="contextMenu">
        <div class="context-menu-item" data-action="copy">
            <i class="bi bi-files"></i>
            <span>Copy</span>
            <span class="shortcut">Ctrl+C</span>
        </div>
        <div class="context-menu-item" data-action="cut">
            <i class="bi bi-scissors"></i>
            <span>Cut</span>
            <span class="shortcut">Ctrl+X</span>
        </div>
        <div class="context-menu-item" data-action="paste">
            <i class="bi bi-clipboard"></i>
            <span>Paste</span>
            <span class="shortcut">Ctrl+V</span>
        </div>
        <div class="context-menu-divider"></div>
        <div class="context-menu-item" data-action="delete">
            <i class="bi bi-trash"></i>
            <span>Delete</span>
            <span class="shortcut">Del</span>
        </div>
        <div class="context-menu-item" data-action="clear">
            <i class="bi bi-eraser"></i>
            <span>Clear Contents</span>
        </div>
        <div class="context-menu-divider"></div>
        <div class="context-menu-item" data-action="insert-row">
            <i class="bi bi-plus-square"></i>
            <span>Insert Row</span>
        </div>
        <div class="context-menu-item" data-action="insert-column">
            <i class="bi bi-plus-square"></i>
            <span>Insert Column</span>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- SheetJS Library for Excel Export/Import --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/components/spreadsheet.js') }}"></script>
<script>
    // Initialize spreadsheet with configuration
    const spreadsheetConfig = {
        updateRoute: "{{ route('laporan.cell.update') }}",
        csrfToken: "{{ csrf_token() }}",
        reportTitle: "{{ $report->title }}",
        sheetId: "{{ $sheet->id }}",
        reportId: "{{ $report->id }}"
    };

    // Toggle Save Dropdown
    function toggleSaveDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('saveDropdown');
        dropdown.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('saveDropdown');
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });

    // Save with status function
    function saveWithStatus(status) {
        // Close dropdown
        document.getElementById('saveDropdown').classList.remove('active');

        const statusLabels = {
            'draft': 'Draft',
            'published': 'Published',
            'archived': 'Archived'
        };

        Swal.fire({
            title: `Save as ${statusLabels[status]}?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'simple-confirm',
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel'
            },
            buttonsStyling: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                // Set status value
                document.getElementById('statusInput').value = status;
                
                // Submit form
                document.getElementById('statusForm').submit();
            }
        });
    }
</script>
@endpush