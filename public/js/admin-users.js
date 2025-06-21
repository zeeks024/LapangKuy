/**
 * JavaScript for admin users page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle user tab functionality
    const userTabs = document.querySelectorAll('#user-tabs .nav-link');
    
    userTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            userTabs.forEach(t => {
                t.classList.remove('active');
                document.querySelector(t.getAttribute('href')).classList.remove('show', 'active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            document.querySelector(this.getAttribute('href')).classList.add('show', 'active');
            
            const tabId = this.getAttribute('href').substring(1);
            
            if (tabId !== 'all') {
                const tabContent = document.querySelector(`#${tabId}`);
                let role = '';
                
                if (tabId === 'user-role') role = 'user';
                else if (tabId === 'owner-role') role = 'field_owner';
                else if (tabId === 'admin-role') role = 'admin';
                
                // Only load content if it hasn't been loaded already
                if (tabContent.querySelector('.spinner-border')) {
                    fetch(`/api/users?role=${role}&ajax=1`)
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
    const filterForm = document.getElementById('user-filter-form');
    
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // Allow normal form submission - server-side filtering
        });
    }
    
    // Client-side filter functionality (for immediate feedback)
    const searchUser = document.getElementById('search-user');
    const roleFilter = document.getElementById('role-filter');
    const statusFilter = document.getElementById('status-filter');
    
    function filterTable() {
        const searchTerm = searchUser?.value.toLowerCase() || '';
        const roleValue = roleFilter?.value.toLowerCase() || '';
        const statusValue = statusFilter?.value.toLowerCase() || '';
        
        const tableSelector = document.querySelector('.tab-pane.active')?.id === 'all' ? '#all' : '.tab-pane.active';
        const rows = document.querySelectorAll(`${tableSelector} table tbody tr`);
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            if (!row.querySelector('td')) return;
            
            const userDataCol = row.querySelector('td:nth-child(1)');
            const roleCol = row.querySelector('td:nth-child(2)');
            const statusCol = row.querySelector('td:nth-child(3)');
            
            if (!userDataCol || !roleCol || !statusCol) return;
            
            const userData = userDataCol.textContent.toLowerCase();
            const userRole = roleCol.textContent.toLowerCase();
            const userStatus = statusCol.textContent.toLowerCase();
            
            const matchesSearch = searchTerm === '' || userData.includes(searchTerm);
            const matchesRole = roleValue === '' || userRole.includes(roleValue);
            const matchesStatus = statusValue === '' || 
                (statusValue === 'active' && userStatus.includes('aktif')) ||
                (statusValue === 'inactive' && userStatus.includes('tidak aktif'));
            
            if (matchesSearch && matchesRole && matchesStatus) {
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
    
    if (searchUser) searchUser.addEventListener('input', filterTable);
    if (roleFilter) roleFilter.addEventListener('change', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
    
    // Reset filters
    const resetButton = document.getElementById('reset-filter');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (searchUser) searchUser.value = '';
            if (roleFilter) roleFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            
            filterTable();
        });
    }
    
    // Handle dropdown toggles
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const dropdown = this.nextElementSibling;
            const isOpen = dropdown.classList.contains('show');
            
            // Close all dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
            
            // Toggle current dropdown
            if (!isOpen) {
                dropdown.classList.add('show');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
        
        // Confirm actions
        if (e.target.closest('[data-confirm]')) {
            const button = e.target.closest('[data-confirm]');
            if (!confirm(button.dataset.confirm)) {
                e.preventDefault();
            }
        }
    });
});
