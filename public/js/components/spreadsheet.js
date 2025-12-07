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
        this.clipboard = {
            type: null, // 'copy' or 'cut'
            cells: []
        };
        
        this.init();
    }

    /**
     * Initialize spreadsheet
     */
    init() {
        this.setupCells();
        this.setupHeaders();
        this.setupButtons();
        this.setupContextMenu();
        this.setupKeyboardShortcuts();
        this.setupFormattingToolbar();
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
            
            // Don't clear selections if Shift is held
            if (!event || !event.shiftKey) {
                this.clearAllSelections();
            }
        });

        cell.addEventListener('click', (e) => {
            // Shift+Click for range selection
            if (e.shiftKey && this.currentCell && this.currentCell !== cell) {
                e.preventDefault();
                this.selectCellRange(this.currentCell, cell);
            }
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
        
        // Get inline styles
        const styles = cell.getAttribute('style') || '';

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
                    value: value,
                    styles: styles
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

    /**
     * Select range of cells (Shift+Click)
     */
    selectCellRange(startCell, endCell) {
        const startRow = parseInt(startCell.dataset.row);
        const startCol = startCell.dataset.col.charCodeAt(0);
        const endRow = parseInt(endCell.dataset.row);
        const endCol = endCell.dataset.col.charCodeAt(0);

        const minRow = Math.min(startRow, endRow);
        const maxRow = Math.max(startRow, endRow);
        const minCol = Math.min(startCol, endCol);
        const maxCol = Math.max(startCol, endCol);

        this.clearAllSelections();

        for (let row = minRow; row <= maxRow; row++) {
            for (let col = minCol; col <= maxCol; col++) {
                const cellId = String.fromCharCode(col) + row;
                const cell = document.querySelector(`[data-cell="${cellId}"]`);
                if (cell) {
                    cell.classList.add('selected');
                }
            }
        }

        this.showSelectionInfo();
    }

    /**
     * Setup context menu
     */
    setupContextMenu() {
        const contextMenu = document.getElementById('contextMenu');
        
        // Show context menu on right click
        document.addEventListener('contextmenu', (e) => {
            const cell = e.target.closest('.cell');
            if (cell) {
                e.preventDefault();
                this.showContextMenu(e, cell);
            } else {
                this.hideContextMenu();
            }
        });

        // Hide context menu on click outside
        document.addEventListener('click', () => {
            this.hideContextMenu();
        });

        // Context menu item actions
        contextMenu.addEventListener('click', (e) => {
            const item = e.target.closest('.context-menu-item');
            if (item && !item.classList.contains('disabled')) {
                const action = item.dataset.action;
                this.handleContextMenuAction(action);
                this.hideContextMenu();
            }
        });

        // Prevent context menu from closing when clicking inside
        contextMenu.addEventListener('contextmenu', (e) => {
            e.preventDefault();
        });
    }

    /**
     * Show context menu
     */
    showContextMenu(event, cell) {
        const contextMenu = document.getElementById('contextMenu');
        
        // If cell is not selected, select it
        if (!cell.classList.contains('selected')) {
            this.clearAllSelections();
            cell.classList.add('selected');
            this.currentCell = cell;
        }

        // Position context menu
        const x = event.pageX;
        const y = event.pageY;
        
        contextMenu.style.left = `${x}px`;
        contextMenu.style.top = `${y}px`;
        contextMenu.classList.add('show');

        // Enable/disable paste based on clipboard
        const pasteItem = contextMenu.querySelector('[data-action="paste"]');
        if (this.clipboard.cells.length > 0) {
            pasteItem.classList.remove('disabled');
        } else {
            pasteItem.classList.add('disabled');
        }

        // Adjust position if menu goes off screen
        setTimeout(() => {
            const menuRect = contextMenu.getBoundingClientRect();
            const windowWidth = window.innerWidth;
            const windowHeight = window.innerHeight;

            if (menuRect.right > windowWidth) {
                contextMenu.style.left = `${windowWidth - menuRect.width - 10}px`;
            }
            if (menuRect.bottom > windowHeight) {
                contextMenu.style.top = `${windowHeight - menuRect.height - 10}px`;
            }
        }, 0);
    }

    /**
     * Hide context menu
     */
    hideContextMenu() {
        const contextMenu = document.getElementById('contextMenu');
        contextMenu.classList.remove('show');
    }

    /**
     * Handle context menu actions
     */
    handleContextMenuAction(action) {
        switch(action) {
            case 'copy':
                this.copySelectedCells();
                break;
            case 'cut':
                this.cutSelectedCells();
                break;
            case 'paste':
                this.pasteClipboard();
                break;
            case 'delete':
                this.deleteSelectedCells();
                break;
            case 'clear':
                this.clearSelectedCells();
                break;
            case 'insert-row':
                this.addRow();
                break;
            case 'insert-column':
                this.addColumn();
                break;
        }
    }

    /**
     * Copy selected cells
     */
    copySelectedCells() {
        const selectedCells = document.querySelectorAll('.cell.selected');
        if (selectedCells.length === 0) return;

        // Clear previous clipboard styling
        document.querySelectorAll('.cell.copied, .cell.cut').forEach(cell => {
            cell.classList.remove('copied', 'cut');
        });

        this.clipboard.type = 'copy';
        this.clipboard.cells = Array.from(selectedCells).map(cell => ({
            element: cell,
            value: cell.textContent,
            cellId: cell.dataset.cell
        }));

        // Add visual indication
        selectedCells.forEach(cell => {
            cell.classList.add('copied');
        });

        this.showNotification(`${selectedCells.length} cell(s) copied`);
    }

    /**
     * Cut selected cells
     */
    cutSelectedCells() {
        const selectedCells = document.querySelectorAll('.cell.selected');
        if (selectedCells.length === 0) return;

        // Clear previous clipboard styling
        document.querySelectorAll('.cell.copied, .cell.cut').forEach(cell => {
            cell.classList.remove('copied', 'cut');
        });

        this.clipboard.type = 'cut';
        this.clipboard.cells = Array.from(selectedCells).map(cell => ({
            element: cell,
            value: cell.textContent,
            cellId: cell.dataset.cell
        }));

        // Add visual indication
        selectedCells.forEach(cell => {
            cell.classList.add('cut');
        });

        this.showNotification(`${selectedCells.length} cell(s) cut`);
    }

    /**
     * Paste clipboard contents
     */
    pasteClipboard() {
        if (this.clipboard.cells.length === 0) return;

        const targetCell = this.currentCell || document.querySelector('.cell.selected');
        if (!targetCell) return;

        // Get starting position
        const startRow = parseInt(targetCell.dataset.row);
        const startCol = targetCell.dataset.col.charCodeAt(0);

        // Calculate source dimensions
        const sourceRows = new Set(this.clipboard.cells.map(c => c.element.dataset.row));
        const sourceCols = new Set(this.clipboard.cells.map(c => c.element.dataset.col));
        const minSourceRow = Math.min(...Array.from(sourceRows).map(r => parseInt(r)));
        const minSourceCol = Math.min(...Array.from(sourceCols).map(c => c.charCodeAt(0)));

        // Paste each cell
        this.clipboard.cells.forEach(clipboardCell => {
            const sourceRow = parseInt(clipboardCell.element.dataset.row);
            const sourceCol = clipboardCell.element.dataset.col.charCodeAt(0);
            
            // Calculate offset
            const rowOffset = sourceRow - minSourceRow;
            const colOffset = sourceCol - minSourceCol;
            
            // Calculate target position
            const targetRow = startRow + rowOffset;
            const targetCol = String.fromCharCode(startCol + colOffset);
            const targetCellId = targetCol + targetRow;
            
            // Find target cell
            const targetElement = document.querySelector(`[data-cell="${targetCellId}"]`);
            if (targetElement && targetElement.classList.contains('cell')) {
                targetElement.textContent = clipboardCell.value;
                this.saveCell(targetElement);
            }
        });

        // If cut, clear source cells
        if (this.clipboard.type === 'cut') {
            this.clipboard.cells.forEach(clipboardCell => {
                clipboardCell.element.textContent = '';
                clipboardCell.element.classList.remove('cut');
                this.saveCell(clipboardCell.element);
            });
        }

        // Clear clipboard styling
        document.querySelectorAll('.cell.copied, .cell.cut').forEach(cell => {
            cell.classList.remove('copied', 'cut');
        });

        this.showNotification(`${this.clipboard.cells.length} cell(s) pasted`);
    }

    /**
     * Delete selected cells
     */
    deleteSelectedCells() {
        const selectedCells = document.querySelectorAll('.cell.selected');
        if (selectedCells.length === 0) return;

        selectedCells.forEach(cell => {
            cell.textContent = '';
            this.saveCell(cell);
        });

        this.showNotification(`${selectedCells.length} cell(s) deleted`);
    }

    /**
     * Clear selected cells
     */
    clearSelectedCells() {
        this.deleteSelectedCells();
    }

    /**
     * Setup keyboard shortcuts
     */
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ignore if typing in a cell
            if (document.activeElement.classList.contains('cell') && 
                document.activeElement.contentEditable === 'true') {
                
                // Allow formatting shortcuts while editing
                if (e.ctrlKey || e.metaKey) {
                    if (e.key === 'b') {
                        e.preventDefault();
                        this.toggleFormat('bold');
                    } else if (e.key === 'i') {
                        e.preventDefault();
                        this.toggleFormat('italic');
                    } else if (e.key === 'u') {
                        e.preventDefault();
                        this.toggleFormat('underline');
                    }
                }
                return;
            }

            // Ctrl/Cmd + C - Copy
            if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
                e.preventDefault();
                this.copySelectedCells();
            }

            // Ctrl/Cmd + X - Cut
            if ((e.ctrlKey || e.metaKey) && e.key === 'x') {
                e.preventDefault();
                this.cutSelectedCells();
            }

            // Ctrl/Cmd + V - Paste
            if ((e.ctrlKey || e.metaKey) && e.key === 'v') {
                e.preventDefault();
                this.pasteClipboard();
            }

            // Delete - Delete cells
            if (e.key === 'Delete') {
                e.preventDefault();
                this.deleteSelectedCells();
            }

            // Formatting shortcuts
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                this.toggleFormat('bold');
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 'i') {
                e.preventDefault();
                this.toggleFormat('italic');
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
                e.preventDefault();
                this.toggleFormat('underline');
            }
        });
    }

    /**
     * Setup formatting toolbar
     */
    setupFormattingToolbar() {
        // Font family
        const fontFamily = document.getElementById('fontFamily');
        if (fontFamily) {
            fontFamily.addEventListener('change', (e) => {
                this.applyFontFamily(e.target.value);
            });
        }

        // Font size
        const fontSize = document.getElementById('fontSize');
        if (fontSize) {
            fontSize.addEventListener('change', (e) => {
                this.applyFontSize(e.target.value);
            });
        }

        // Text formatting buttons (bold, italic, underline, strikethrough)
        document.querySelectorAll('.toolbar-btn[data-format]').forEach(btn => {
            btn.addEventListener('click', () => {
                const format = btn.dataset.format;
                this.toggleFormat(format);
            });
        });

        // Text color
        const textColorBtn = document.getElementById('textColorBtn');
        const textColorInput = document.getElementById('textColor');
        if (textColorBtn && textColorInput) {
            textColorBtn.addEventListener('click', () => {
                textColorInput.click();
            });
            textColorInput.addEventListener('change', (e) => {
                this.applyTextColor(e.target.value);
                textColorBtn.style.setProperty('--text-color', e.target.value);
            });
        }

        // Background color
        const bgColorBtn = document.getElementById('bgColorBtn');
        const bgColorInput = document.getElementById('bgColor');
        if (bgColorBtn && bgColorInput) {
            bgColorBtn.addEventListener('click', () => {
                bgColorInput.click();
            });
            bgColorInput.addEventListener('change', (e) => {
                this.applyBackgroundColor(e.target.value);
                bgColorBtn.style.setProperty('--bg-color', e.target.value);
            });
        }

        // Text alignment buttons
        document.querySelectorAll('.toolbar-btn[data-align]').forEach(btn => {
            btn.addEventListener('click', () => {
                const align = btn.dataset.align;
                this.applyTextAlign(align);
            });
        });

        // Vertical alignment buttons
        document.querySelectorAll('.toolbar-btn[data-valign]').forEach(btn => {
            btn.addEventListener('click', () => {
                const valign = btn.dataset.valign;
                this.applyVerticalAlign(valign);
            });
        });

        // Clear formatting
        const clearFormatBtn = document.getElementById('clearFormatBtn');
        if (clearFormatBtn) {
            clearFormatBtn.addEventListener('click', () => {
                this.clearFormatting();
            });
        }

        // Update toolbar state when cell is selected
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('cell')) {
                this.updateToolbarState(e.target);
            }
        });
    }

    /**
     * Apply font family to selected cells
     */
    applyFontFamily(fontFamily) {
        const selectedCells = this.getSelectedCells();
        selectedCells.forEach(cell => {
            cell.style.fontFamily = fontFamily;
            this.saveCell(cell);
        });
        this.showNotification(`Font changed to ${fontFamily}`);
    }

    /**
     * Apply font size to selected cells
     */
    applyFontSize(fontSize) {
        const selectedCells = this.getSelectedCells();
        selectedCells.forEach(cell => {
            cell.style.fontSize = fontSize + 'px';
            this.saveCell(cell);
        });
        this.showNotification(`Font size changed to ${fontSize}px`);
    }

    /**
     * Toggle text format (bold, italic, underline, strikethrough)
     */
    toggleFormat(format) {
        const selectedCells = this.getSelectedCells();
        if (selectedCells.length === 0) return;

        const formatMap = {
            'bold': 'fontWeight',
            'italic': 'fontStyle',
            'underline': 'textDecoration',
            'strikethrough': 'textDecoration'
        };

        const valueMap = {
            'bold': { on: 'bold', off: 'normal' },
            'italic': { on: 'italic', off: 'normal' },
            'underline': { on: 'underline', off: 'none' },
            'strikethrough': { on: 'line-through', off: 'none' }
        };

        const styleProperty = formatMap[format];
        const values = valueMap[format];

        // Check if first cell has the format applied
        const firstCell = selectedCells[0];
        const currentValue = firstCell.style[styleProperty];
        const isActive = currentValue === values.on || 
                        (format === 'underline' && currentValue.includes('underline')) ||
                        (format === 'strikethrough' && currentValue.includes('line-through'));

        selectedCells.forEach(cell => {
            if (format === 'underline' || format === 'strikethrough') {
                // Handle text-decoration specially (can have multiple values)
                const decorations = (cell.style.textDecoration || '').split(' ').filter(d => d);
                
                if (isActive) {
                    // Remove decoration
                    cell.style.textDecoration = decorations
                        .filter(d => d !== values.on)
                        .join(' ') || 'none';
                } else {
                    // Add decoration
                    if (!decorations.includes(values.on)) {
                        decorations.push(values.on);
                    }
                    cell.style.textDecoration = decorations.filter(d => d !== 'none').join(' ');
                }
            } else {
                cell.style[styleProperty] = isActive ? values.off : values.on;
            }
            this.saveCell(cell);
        });

        // Update button state
        const btn = document.querySelector(`[data-format="${format}"]`);
        if (btn) {
            btn.classList.toggle('active', !isActive);
        }

        this.showNotification(`${format} ${isActive ? 'removed' : 'applied'}`);
    }

    /**
     * Apply text color to selected cells
     */
    applyTextColor(color) {
        const selectedCells = this.getSelectedCells();
        selectedCells.forEach(cell => {
            cell.style.color = color;
            this.saveCell(cell);
        });
        this.showNotification('Text color applied');
    }

    /**
     * Apply background color to selected cells
     */
    applyBackgroundColor(color) {
        const selectedCells = this.getSelectedCells();
        selectedCells.forEach(cell => {
            cell.style.backgroundColor = color;
            this.saveCell(cell);
        });
        this.showNotification('Background color applied');
    }

    /**
     * Apply text alignment to selected cells
     */
    applyTextAlign(align) {
        const selectedCells = this.getSelectedCells();
        selectedCells.forEach(cell => {
            cell.style.textAlign = align;
            this.saveCell(cell);
        });

        // Update button states
        document.querySelectorAll('.toolbar-btn[data-align]').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-align="${align}"]`)?.classList.add('active');

        this.showNotification(`Text aligned ${align}`);
    }

    /**
     * Apply vertical alignment to selected cells
     */
    applyVerticalAlign(valign) {
        const selectedCells = this.getSelectedCells();
        selectedCells.forEach(cell => {
            cell.style.verticalAlign = valign;
            this.saveCell(cell);
        });

        // Update button states
        document.querySelectorAll('.toolbar-btn[data-valign]').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-valign="${valign}"]`)?.classList.add('active');

        this.showNotification(`Vertical alignment set to ${valign}`);
    }

    /**
     * Clear all formatting from selected cells
     */
    clearFormatting() {
        const selectedCells = this.getSelectedCells();
        selectedCells.forEach(cell => {
            // Remove all inline styles
            cell.removeAttribute('style');
            this.saveCell(cell);
        });

        // Reset toolbar buttons
        document.querySelectorAll('.toolbar-btn.active').forEach(btn => {
            btn.classList.remove('active');
        });

        this.showNotification('Formatting cleared');
    }

    /**
     * Get all selected cells
     */
    getSelectedCells() {
        let cells = Array.from(document.querySelectorAll('.cell.selected'));
        
        // If no cells selected, use current cell
        if (cells.length === 0 && this.currentCell) {
            cells = [this.currentCell];
        }

        return cells;
    }

    /**
     * Update toolbar state based on selected cell
     */
    updateToolbarState(cell) {
        if (!cell) return;

        const styles = window.getComputedStyle(cell);

        // Update font family
        const fontFamily = document.getElementById('fontFamily');
        if (fontFamily) {
            fontFamily.value = cell.style.fontFamily || 'Inter';
        }

        // Update font size
        const fontSize = document.getElementById('fontSize');
        if (fontSize && cell.style.fontSize) {
            fontSize.value = parseInt(cell.style.fontSize);
        }

        // Update format buttons
        const isBold = styles.fontWeight === 'bold' || styles.fontWeight >= 600;
        const isItalic = styles.fontStyle === 'italic';
        const isUnderline = styles.textDecoration.includes('underline');
        const isStrikethrough = styles.textDecoration.includes('line-through');

        document.getElementById('boldBtn')?.classList.toggle('active', isBold);
        document.getElementById('italicBtn')?.classList.toggle('active', isItalic);
        document.getElementById('underlineBtn')?.classList.toggle('active', isUnderline);
        document.getElementById('strikethroughBtn')?.classList.toggle('active', isStrikethrough);

        // Update alignment buttons
        const textAlign = cell.style.textAlign || 'left';
        document.querySelectorAll('.toolbar-btn[data-align]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.align === textAlign);
        });

        const verticalAlign = cell.style.verticalAlign || 'top';
        document.querySelectorAll('.toolbar-btn[data-valign]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.valign === verticalAlign);
        });
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