/**
 * Admin Bookings Dynamic Loading
 * This script handles the dynamic loading of bookings by status when tabs are clicked.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Tab elements
    const confirmedTab = document.getElementById('confirmed-tab');
    const pendingTab = document.getElementById('pending-tab');
    const cancelledTab = document.getElementById('cancelled-tab');
    
    // Content containers
    const confirmedContainer = document.getElementById('confirmed');
    const pendingContainer = document.getElementById('pending');
    const cancelledContainer = document.getElementById('cancelled');
    
    // Function to load bookings by status
    function loadBookingsByStatus(status, container) {
        // Show loading spinner
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `;
          // Make AJAX request to get filtered bookings
        fetch(`/admin/api/bookings?status=${status}`)
            .then(response => response.text())
            .then(html => {
                // Replace container content
                container.innerHTML = html;
                
                // Parse the HTML to apply event listeners
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Re-initialize client-side filtering
                initFiltering(container);
            })
            .catch(error => {
                container.innerHTML = `
                    <div class="alert alert-danger">
                        Error loading bookings: ${error.message}
                    </div>
                `;
            });
    }
    
    // Function to initialize filtering for dynamically loaded content
    function initFiltering(container) {
        const searchInput = container.querySelector('#search-booking');
        const dateFilter = container.querySelector('#date-filter');
        const resetButton = container.querySelector('#reset-filter');
        const bookingRows = container.querySelectorAll('.booking-row');
        
        if (!searchInput || !bookingRows.length) return;
        
        // Add event listeners for filtering
        searchInput.addEventListener('input', function() {
            filterRows(bookingRows, searchInput.value, dateFilter.value);
        });
        
        dateFilter.addEventListener('change', function() {
            filterRows(bookingRows, searchInput.value, dateFilter.value);
        });
        
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            dateFilter.value = '';
            bookingRows.forEach(row => row.style.display = '');
        });
    }
    
    // Function to filter rows
    function filterRows(rows, searchTerm, dateTerm) {
        const searchLower = searchTerm.toLowerCase();
        
        rows.forEach(row => {
            const bookingCode = row.getAttribute('data-booking-code').toLowerCase();
            const date = row.getAttribute('data-date');
            
            const matchesSearch = !searchLower || bookingCode.includes(searchLower);
            const matchesDate = !dateTerm || date === dateTerm;
            
            row.style.display = (matchesSearch && matchesDate) ? '' : 'none';
        });
    }
    
    // Add click event listeners to tabs
    if (confirmedTab) {
        confirmedTab.addEventListener('click', () => {
            if (!confirmedContainer.querySelector('.table-responsive')) {
                loadBookingsByStatus('confirmed', confirmedContainer);
            }
        });
    }
    
    if (pendingTab) {
        pendingTab.addEventListener('click', () => {
            if (!pendingContainer.querySelector('.table-responsive')) {
                loadBookingsByStatus('pending', pendingContainer);
            }
        });
    }
    
    if (cancelledTab) {
        cancelledTab.addEventListener('click', () => {
            if (!cancelledContainer.querySelector('.table-responsive')) {
                loadBookingsByStatus('cancelled', cancelledContainer);
            }
        });
    }
});
