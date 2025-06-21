/**
 * JavaScript for admin fields page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle field tab functionality
    const fieldTabs = document.querySelectorAll('#field-tabs .nav-link');
    
    fieldTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            fieldTabs.forEach(t => {
                t.classList.remove('active');
                document.querySelector(t.getAttribute('href')).classList.remove('show', 'active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            document.querySelector(this.getAttribute('href')).classList.add('show', 'active');
            
            const tabId = this.getAttribute('href').substring(1);
            
            if (tabId === 'active' || tabId === 'inactive') {
                const tabContent = document.querySelector(`#${tabId}`);
                const isAvailable = tabId === 'active' ? 1 : 0;
                
                // Only load content if it hasn't been loaded already
                if (tabContent.querySelector('.spinner-border')) {
                    fetch(`/api/fields?status=${tabId}&ajax=1`)
                        .then(response => response.text())
                        .then(html => {
                            tabContent.innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error loading tab content:', error);
                            tabContent.innerHTML = '<div class="alert alert-danger">Error loading content</div>';
                        });
                }
            }
        });
    });
    
    // Form submission
    const filterForm = document.getElementById('field-filter-form');
    
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // Allow normal form submission - server-side filtering
        });
    }
    
    // Client-side filter functionality (for immediate feedback)
    const searchField = document.getElementById('search-field');
    const categoryFilter = document.getElementById('category-filter');
    const statusFilter = document.getElementById('status-filter');
    
    function filterTable() {
        const searchTerm = searchField?.value.toLowerCase() || '';
        const categoryValue = categoryFilter?.value.toLowerCase() || '';
        const statusValue = statusFilter?.value.toLowerCase() || '';
        
        const tableSelector = document.querySelector('.tab-pane.active')?.id === 'all' ? '#all' : '.tab-pane.active';
        const rows = document.querySelectorAll(`${tableSelector} table tbody tr`);
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            if (!row.querySelector('td')) return;
            
            const nameColumn = row.querySelector('td:nth-child(3)');
            const categoryColumn = row.querySelector('td:nth-child(4)');
            const statusColumn = row.querySelector('td:nth-child(7)');
            
            if (!nameColumn || !categoryColumn || !statusColumn) return;
            
            const name = nameColumn.textContent.toLowerCase();
            const category = categoryColumn.textContent.toLowerCase();
            const status = statusColumn.textContent.toLowerCase();
            
            const matchesSearch = searchTerm === '' || name.includes(searchTerm);
            const matchesCategory = categoryValue === '' || category.includes(categoryValue);
            const matchesStatus = statusValue === '' || 
                (statusValue === 'active' && status.includes('aktif')) ||
                (statusValue === 'inactive' && status.includes('tidak aktif'));
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show or hide empty state message
        const emptyState = document.querySelector(`${tableSelector} .empty-state`)?.closest('tr');
        if (emptyState) {
            if (visibleCount === 0) {
                emptyState.style.display = '';
            } else {
                emptyState.style.display = 'none';
            }
        }
    }
    
    if (searchField) searchField.addEventListener('input', filterTable);
    if (categoryFilter) categoryFilter.addEventListener('change', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
    
    // Reset filters
    const resetButton = document.getElementById('reset-filter');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (searchField) searchField.value = '';
            if (categoryFilter) categoryFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            
            filterTable();
        });
    }
    
    // Confirm deletion
    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-confirm]')) {
            const button = e.target.closest('[data-confirm]');
            if (!confirm(button.dataset.confirm)) {
                e.preventDefault();
            }
        }
    });
});
