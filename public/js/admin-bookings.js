// Admin booking page functionality

document.addEventListener('DOMContentLoaded', function() {
    // Handle tab switching
    const bookingTabs = document.querySelectorAll('#booking-tabs a');
    bookingTabs.forEach(tab => {
        tab.addEventListener('click', function(event) {
            event.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            
            // Update active state
            bookingTabs.forEach(t => {
                t.classList.remove('active');
                document.querySelector(t.getAttribute('href')).classList.remove('show', 'active');
            });
            
            this.classList.add('active');
            target.classList.add('show', 'active');
        });
    });
    
    // Client-side filtering functionality
    const searchInput = document.getElementById('search-booking');
    const statusFilter = document.getElementById('status-filter');
    const dateFilter = document.getElementById('date-filter');
    const resetButton = document.getElementById('reset-filter');
    const bookingRows = document.querySelectorAll('.booking-row');
    
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusTerm = statusFilter.value.toLowerCase();
        const dateTerm = dateFilter.value;
        
        bookingRows.forEach(row => {
            const bookingCode = row.getAttribute('data-booking-code').toLowerCase();
            const status = row.getAttribute('data-status').toLowerCase();
            const date = row.getAttribute('data-date');
            
            const matchesSearch = !searchTerm || bookingCode.includes(searchTerm);
            const matchesStatus = !statusTerm || status === statusTerm;
            const matchesDate = !dateTerm || date === dateTerm;
            
            if (matchesSearch && matchesStatus && matchesDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
    
    if (dateFilter) {
        dateFilter.addEventListener('change', applyFilters);
    }
    
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            dateFilter.value = '';
            
            bookingRows.forEach(row => {
                row.style.display = '';
            });
        });
    }
});
