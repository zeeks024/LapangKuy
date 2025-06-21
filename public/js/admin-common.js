/**
 * Common JavaScript for all admin pages
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle sidebar toggle for responsive design
    const sidebarToggleBtn = document.getElementById('sidebar-toggle');
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('collapsed');
            document.querySelector('.admin-content').classList.toggle('expanded');
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const closeBtn = alert.querySelector('.close, .btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });

    // Common filter functionality
    const searchInputs = document.querySelectorAll('.filter-input, [id$="-search"]');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.dataset.tableTarget) {
                filterTable(this.value, this.dataset.tableTarget);
            }
        });
    });

    // Reset filter buttons
    const resetButtons = document.querySelectorAll('.reset-filter, [id$="-reset"]');
    resetButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            if (form) {
                e.preventDefault();
                form.reset();
                form.submit();
            }
        });
    });

    // Handle confirmation dialogs
    document.addEventListener('click', function(e) {
        const target = e.target;
        if (target.dataset.confirm) {
            if (!confirm(target.dataset.confirm)) {
                e.preventDefault();
            }
        }
    });
});

/**
 * Filter table by search term
 * 
 * @param {string} term - Search term
 * @param {string} tableId - Table element ID
 */
function filterTable(term, tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');
    const lowerTerm = term.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(lowerTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Show empty state message if no results
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
    const emptyState = table.nextElementSibling?.classList.contains('empty-state') 
        ? table.nextElementSibling 
        : null;

    if (emptyState) {
        if (visibleRows.length === 0) {
            emptyState.style.display = 'flex';
        } else {
            emptyState.style.display = 'none';
        }
    }
}
