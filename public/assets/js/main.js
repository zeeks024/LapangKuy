// Booking page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Time slot selection
    const timeSlots = document.querySelectorAll('.time-slot');
    const startTimeInput = document.getElementById('start_time');
    const durationButtons = document.querySelectorAll('.duration-btn');
    const durationInput = document.getElementById('duration');
    
    // Safe price extraction with null checks
    const priceElement = document.querySelector('.price-row:first-child');
    const fieldPrice = priceElement ? priceElement.textContent.match(/\d+/g)?.join('') || '0' : '0';
    
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    
    let selectedTimeSlot = null;
    let selectedDuration = 1;

    // Initialize price calculation with null checks
    if(subtotalElement && totalElement && fieldPrice) {
        subtotalElement.textContent = 'Rp' + formatNumber(fieldPrice);
        totalElement.textContent = 'Rp' + formatNumber(fieldPrice);
    }
    
    // Handle time slot selection
    timeSlots.forEach(slot => {
        if(!slot.classList.contains('booked')) {
            slot.addEventListener('click', function() {
                // Reset previously selected time slot
                if(selectedTimeSlot) {
                    selectedTimeSlot.classList.remove('selected');
                }
                
                // Select new time slot
                this.classList.add('selected');
                selectedTimeSlot = this;
                startTimeInput.value = this.dataset.time;
                
                // Check if subsequent slots are available based on duration
                validateDurationAvailability();
                
                // Update booking summary
                updateBookingSummary();
            });
        }
    });
    
    // Handle duration selection
    durationButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Reset previously selected duration
            durationButtons.forEach(btn => btn.classList.remove('active'));
            
            // Select new duration
            this.classList.add('active');
            selectedDuration = parseInt(this.dataset.duration);
            durationInput.value = selectedDuration;
            
            // Validate if the selected duration is available
            validateDurationAvailability();
            
            // Update booking summary
            updateBookingSummary();
        });
    });
    
    // Validate if selected duration is available based on selected time
    function validateDurationAvailability() {
        if(!selectedTimeSlot) return;
        
        const startTime = selectedTimeSlot.dataset.time;
        const startHour = parseInt(startTime.split(':')[0]);
        
        // Reset all duration buttons
        durationButtons.forEach(btn => {
            btn.classList.remove('disabled');
        });
        
        // Check each duration option
        durationButtons.forEach(btn => {
            const duration = parseInt(btn.dataset.duration);
            let isAvailable = true;
            
            // Check if slots are available for the selected duration
            for(let i = 1; i < duration; i++) {
                const nextHour = startHour + i;
                const nextHourStr = (nextHour < 10 ? '0' : '') + nextHour + ':00';
                const nextSlot = document.querySelector(`.time-slot[data-time="${nextHourStr}"]`);
                
                if(!nextSlot || nextSlot.classList.contains('booked')) {
                    isAvailable = false;
                    break;
                }
            }
            
            // Disable duration option if not available
            if(!isAvailable) {
                btn.classList.add('disabled');
                if(parseInt(durationInput.value) === duration) {
                    // If current selection is not available, reset to 1 hour
                    durationInput.value = 1;
                    durationButtons.forEach(b => b.classList.remove('active'));
                    document.querySelector('.duration-btn[data-duration="1"]').classList.add('active');
                    selectedDuration = 1;
                }
            }
        });
    }
    
    // Update booking summary with selected options
    function updateBookingSummary() {
        if(!selectedTimeSlot || !subtotalElement || !totalElement) return;
        
        const price = parseInt(fieldPrice);
        const subtotal = price * selectedDuration;
        
        // Update subtotal and total
        subtotalElement.textContent = 'Rp' + formatNumber(subtotal);
        totalElement.textContent = 'Rp' + formatNumber(subtotal);
        
        // Update booking summary details
        const dateInput = document.getElementById('date');
        const bookingDate = document.getElementById('booking-date');
        const bookingTime = document.getElementById('booking-time');
        const bookingDuration = document.getElementById('booking-duration');
        
        if(dateInput && bookingDate) {
            const date = new Date(dateInput.value);
            const formattedDate = date.toLocaleDateString('id-ID', {
                weekday: 'long', 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric'
            });
            bookingDate.textContent = formattedDate;
        }
        
        if(bookingTime) {
            const startTime = selectedTimeSlot.dataset.time;
            const startHour = parseInt(startTime.split(':')[0]);
            const endHour = startHour + selectedDuration;
            const endTime = (endHour < 10 ? '0' : '') + endHour + ':00';
            bookingTime.textContent = startTime + ' - ' + endTime;
        }
        
        if(bookingDuration) {
            bookingDuration.textContent = selectedDuration + ' jam';
        }
        
        // Show booking summary
        const bookingSummary = document.querySelector('.booking-summary');
        if(bookingSummary) {
            bookingSummary.classList.remove('d-none');
        }
    }
    
    // Format number with thousand separators
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
    
    // Initialize booking form with URL parameters if present
    const urlParams = new URLSearchParams(window.location.search);
    const dateParam = urlParams.get('date');
    
    if(dateParam) {
        const dateInput = document.getElementById('date');
        if(dateInput) {
            dateInput.value = dateParam;
        }
    }
});

// Field detail page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Gallery thumbnails
    const thumbnails = document.querySelectorAll('.thumbnails img');
    const mainImage = document.querySelector('.main-image img');
    
    if(thumbnails.length && mainImage) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                mainImage.src = this.src;
            });
        });
    }
    
    // Tabs functionality (DISABLED, use Bootstrap default)
    // const tabs = document.querySelectorAll('.nav-tabs .nav-link');
    // const tabContents = document.querySelectorAll('.tab-content .tab-pane');
    
    // if(tabs.length && tabContents.length) {
    //     tabs.forEach(tab => {
    //         tab.addEventListener('click', function(e) {
    //             e.preventDefault();
                
    //             // Remove active class from all tabs
    //             tabs.forEach(t => t.classList.remove('active'));
                
    //             // Hide all tab contents
    //             tabContents.forEach(content => content.classList.remove('show', 'active'));
                
    //             // Add active class to clicked tab
    //             this.classList.add('active');
                
    //             // Show corresponding tab content
    //             const tabId = this.getAttribute('href');
    //             document.querySelector(tabId).classList.add('show', 'active');
    //         });
    //     });
    // }
});

// Home page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Banner carousel autoplay
    const banners = document.querySelectorAll('.hero .carousel-item');
    let currentBannerIndex = 0;
    
    if(banners.length > 1) {
        setInterval(function() {
            // Hide current banner
            banners[currentBannerIndex].classList.remove('active');
            
            // Update index
            currentBannerIndex = (currentBannerIndex + 1) % banners.length;
            
            // Show next banner
            banners[currentBannerIndex].classList.add('active');
        }, 5000);
    }
});
