/**
 * Spreadsheet Manager
 * Handles spreadsheet functionality including navigation, editing, and Excel import/export
 */

class SpreadsheetManager {
    constructor(config) {
        this.config = config;
        this.table = document.getElementById('spreadsheetTable');
        this.activeCellDisplay = document.querySelector('.active-cell strong');
        this.statusIndicator = document.querySelector('.status-indicator');
        this.selectionInfo = document.querySelector('.selection-info');
        this.selectedCountDisplay = document.querySelector('.selected-count');
        this.currentCell = null;
        
        this.init();
    }

    /**
     * Initialize spreadsheet
     */
    init() {
        this.setupCells();
        this.setupHeaders();
        this.setupButtons();
    }

    /**
     * Setup all cells with event listeners
     */
    setupCells() {
        document.querySelectorAll('.cell').forEach(cell => {
            this.attachCellListeners(cell);
        });
    }

    /**
     * Setup column and row headers
     */
    setupHeaders() {
        // Column headers
        document.querySelectorAll('.col-header').forEach(header => {
            header.addEventListener('click', (e) => this.selectColumn(e));
        });

        // Row headers
        document.querySelectorAll('.row-header').forEach(header => {
            if (header.dataset.row) {
                header.addEventListener('click', (e) => this.selectRow(e));
            }
        });
    }

    /**
     * Setup action buttons
     */
    setupButtons() {
        const addColumnBtn = document.getElementById('addColumn');
        const addRowBtn = document.getElementById('addRow');
        const exportExcelBtn = document.getElementById('exportExcel');
        const importExcelBtn = document.getElementById('importExcel');
        const fileInput = document.getElementById('excelFileInput');

        if (addColumnBtn) {
            addColumnBtn.addEventListener('click', () => this.addColumn());
        }

        if (addRowBtn) {
            addRowBtn.addEventListener('click', () => this.addRow());
        }

        if (exportExcelBtn) {
            exportExcelBtn.addEventListener('click', () => this.exportToExcel());
        }

        if (importExcelBtn) {
            importExcelBtn.addEventListener('click', () => fileInput.click());
        }

        if (fileInput) {
            fileInput.addEventListener('change', (e) => this.importFromExcel(e));
        }
    }

    /**
     * Attach event listeners to a cell
     */
    attachCellListeners(cell) {
        cell.addEventListener('focus', () => {
            this.currentCell = cell;
            this.activeCellDisplay.textContent = cell.dataset.cell;
            this.clearAllSelections();
        });

        cell.addEventListener('blur', () => {
            this.saveCell(cell);
        });

        cell.addEventListener('keydown', (e) => {
            this.handleCellKeydown(e, cell);
        });
    }

    /**
     * Handle keyboard navigation
     */
    handleCellKeydown(event, cell) {
        const key = event.key;

        // Arrow keys navigation
        if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(key)) {
            event.preventDefault();
            this.navigateCell(cell, key);
            return;
        }

        // Enter - move down
        if (key === 'Enter') {
            event.preventDefault();
            this.saveCell(cell);
            this.navigateCell(cell, 'ArrowDown');
        }

        // Tab - move right
        if (key === 'Tab') {
            event.preventDefault();
            this.saveCell(cell);
            this.navigateCell(cell, event.shiftKey ? 'ArrowLeft' : 'ArrowRight');
        }

        // Escape - blur cell
        if (key === 'Escape') {
            cell.blur();
        }
    }

    /**
     * Navigate to adjacent cell
     */
    navigateCell(currentCell, direction) {
        const cellName = currentCell.dataset.cell;
        const row = parseInt(cellName.match(/\d+/)[0]);
        const col = cellName.replace(row, '');
        let nextCell = null;

        switch(direction) {
            case 'ArrowUp':
                if (row > 1) {
                    nextCell = document.querySelector(`[data-cell="${col}${row - 1}"]`);
                }
                break;
            case 'ArrowDown':
                nextCell = document.querySelector(`[data-cell="${col}${row + 1}"]`);
                break;
            case 'ArrowLeft':
                if (col.charCodeAt(0) > 65) {
                    const prevCol = String.fromCharCode(col.charCodeAt(0) - 1);
                    nextCell = document.querySelector(`[data-cell="${prevCol}${row}"]`);
                }
                break;
            case 'ArrowRight':
                const nextCol = String.fromCharCode(col.charCodeAt(0) + 1);
                nextCell = document.querySelector(`[data-cell="${nextCol}${row}"]`);
                break;
        }

        if (nextCell) {
            nextCell.focus();
        }
    }

    /**
     * Save cell value to server
     */
    async saveCell(cell) {
        const value = cell.innerText.trim();
        const cellName = cell.dataset.cell;
        const sheetId = cell.dataset.sheetId;

        this.updateStatus('saving', 'Saving...', '#ffc107');

        try {
            const response = await fetch(this.config.updateRoute, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    sheet_id: sheetId,
                    cell: cellName,
                    value: value
                })
            });

            const data = await response.json();
            
            if (response.ok) {
                this.updateStatus('success', 'Auto-saved', '#28a745');
            } else {
                throw new Error(data.message || 'Save failed');
            }
        } catch (error) {
            console.error('Save error:', error);
            this.updateStatus('error', 'Error saving', '#dc3545');
        }
    }

    /**
     * Update status indicator
     */
    updateStatus(type, message, color) {
        const icons = {
            saving: 'bi-arrow-repeat saving',
            success: 'bi-check-circle-fill',
            error: 'bi-exclamation-circle-fill'
        };

        this.statusIndicator.innerHTML = `<i class="bi ${icons[type]}"></i> ${message}`;
        this.statusIndicator.style.color = color;
    }

    /**
     * Select entire column
     */
    selectColumn(event) {
        event.preventDefault();
        const col = event.currentTarget.dataset.col;
        
        this.clearAllSelections();
        event.currentTarget.classList.add('selected');
        
        document.querySelectorAll(`[data-col="${col}"]`).forEach(cell => {
            if (cell.classList.contains('cell')) {
                cell.classList.add('selected');
            }
        });
        
        this.showSelectionInfo();
    }

    /**
     * Select entire row
     */
    selectRow(event) {
        event.preventDefault();
        const row = event.currentTarget.dataset.row;
        
        this.clearAllSelections();
        event.currentTarget.classList.add('selected');
        
        document.querySelectorAll(`[data-row="${row}"]`).forEach(cell => {
            if (cell.classList.contains('cell')) {
                cell.classList.add('selected');
            }
        });
        
        this.showSelectionInfo();
    }

    /**
     * Clear all selections
     */
    clearAllSelections() {
        document.querySelectorAll('.selected').forEach(el => {
            el.classList.remove('selected');
        });
        this.selectionInfo.style.display = 'none';
    }

    /**
     * Show selection info
     */
    showSelectionInfo() {
        const count = document.querySelectorAll('.cell.selected').length;
        if (count > 1) {
            this.selectedCountDisplay.textContent = count;
            this.selectionInfo.style.display = 'inline';
        }
    }

    /**
     * Add new column
     */
    addColumn() {
        const headerRow = this.table.querySelector('thead tr');
        const lastHeader = headerRow.querySelector('.col-header:last-child');
        const lastCol = lastHeader.dataset.col;
        const newCol = String.fromCharCode(lastCol.charCodeAt(0) + 1);

        // Add header
        const newHeader = document.createElement('th');
        newHeader.className = 'col-header';
        newHeader.dataset.col = newCol;
        newHeader.textContent = newCol;
        newHeader.addEventListener('click', (e) => this.selectColumn(e));
        headerRow.appendChild(newHeader);

        // Add cells to each row
        const bodyRows = this.table.querySelectorAll('tbody tr');
        bodyRows.forEach(row => {
            const rowHeader = row.querySelector('.row-header');
            const rowNum = rowHeader.dataset.row;
            const newCell = this.createCell(newCol, rowNum);
            row.appendChild(newCell);
        });

        this.showNotification(`Kolom ${newCol} berhasil ditambahkan!`);
    }

    /**
     * Add new row
     */
    addRow() {
        const tbody = this.table.querySelector('tbody');
        const lastRow = tbody.querySelector('tr:last-child');
        const lastRowNum = parseInt(lastRow.querySelector('.row-header').dataset.row);
        const newRowNum = lastRowNum + 1;

        const newRow = document.createElement('tr');
        
        // Add row header
        const rowHeader = document.createElement('th');
        rowHeader.className = 'row-header';
        rowHeader.dataset.row = newRowNum;
        rowHeader.textContent = newRowNum;
        rowHeader.addEventListener('click', (e) => this.selectRow(e));
        newRow.appendChild(rowHeader);

        // Add cells for each column
        const headers = this.table.querySelectorAll('.col-header');
        headers.forEach(header => {
            const col = header.dataset.col;
            const newCell = this.createCell(col, newRowNum);
            newRow.appendChild(newCell);
        });

        tbody.appendChild(newRow);
        this.showNotification(`Baris ${newRowNum} berhasil ditambahkan!`);
    }

    /**
     * Create new cell element
     */
    createCell(col, row) {
        const cell = document.createElement('td');
        cell.className = 'cell';
        cell.contentEditable = true;
        cell.spellcheck = false;
        cell.dataset.cell = col + row;
        cell.dataset.row = row;
        cell.dataset.col = col;
        cell.dataset.sheetId = this.config.sheetId;
        this.attachCellListeners(cell);
        return cell;
    }

    /**
     * Export to Excel
     */
    exportToExcel() {
        const wb = XLSX.utils.book_new();
        const wsData = [];

        // Get headers
        const headers = [''];
        this.table.querySelectorAll('.col-header').forEach(h => {
            headers.push(h.textContent);
        });
        wsData.push(headers);

        // Get data rows
        this.table.querySelectorAll('tbody tr').forEach(row => {
            const rowData = [];
            row.querySelectorAll('th, td').forEach(cell => {
                rowData.push(cell.textContent.trim());
            });
            wsData.push(rowData);
        });

        const ws = XLSX.utils.aoa_to_sheet(wsData);
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
        
        const fileName = `${this.config.reportTitle}_${this.getCurrentDate()}.xlsx`;
        XLSX.writeFile(wb, fileName);
        
        this.showNotification('File Excel berhasil diexport!');
    }

    /**
     * Import from Excel
     */
    importFromExcel(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {type: 'array'});
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet, {header: 1});

                this.populateTableFromData(jsonData);
                this.showNotification('File Excel berhasil diimport!');
            } catch (error) {
                console.error('Import error:', error);
                this.showNotification('Error importing file', 'error');
            }
        };
        reader.readAsArrayBuffer(file);
        event.target.value = '';
    }

    /**
     * Populate table from imported data
     */
    populateTableFromData(data) {
        if (data.length === 0) return;

        for (let i = 1; i < data.length; i++) {
            const row = data[i];
            for (let j = 1; j < row.length; j++) {
                const col = String.fromCharCode(64 + j);
                const cellId = col + i;
                const cell = document.querySelector(`[data-cell="${cellId}"]`);
                if (cell && row[j] !== undefined) {
                    cell.textContent = row[j];
                    this.saveCell(cell);
                }
            }
        }
    }

    /**
     * Show notification toast
     */
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = 'notification-toast';
        notification.textContent = message;
        
        if (type === 'error') {
            notification.style.background = '#dc3545';
        }
        
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('slide-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    /**
     * Get current date formatted
     */
    getCurrentDate() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
}

// Initialize spreadsheet when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (typeof spreadsheetConfig !== 'undefined') {
        new SpreadsheetManager(spreadsheetConfig);
    }
});

// Export for potential use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SpreadsheetManager;
}