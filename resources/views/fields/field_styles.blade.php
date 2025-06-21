<style>
/* Time Slot Cards */
.time-slot-card {
    padding: 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    background-color: #f8f9fa;
    height: 100%;
}

.time-slot-card.available {
    border-left: 4px solid #28a745;
}

.time-slot-card.available:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    cursor: pointer;
}

.time-slot-card.booked {
    border-left: 4px solid #dc3545;
    opacity: 0.8;
    background-color: #f5f5f5;
}

.time-slot-card .time {
    font-weight: 600;
    font-size: 1.1rem;
}

.time-slot-card .price {
    color: #0d6efd;
    margin-top: 0.25rem;
    font-weight: 500;
}

.time-slot-card .btn-book {
    display: inline-block;
    margin-top: 0.75rem;
    padding: 0.25rem 0.75rem;
    background-color: #28a745;
    color: white;
    border-radius: 0.25rem;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.time-slot-card .btn-book:hover {
    background-color: #218838;
    transform: scale(1.05);
}

.time-slot-card .status-badge {
    margin-top: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 0.25rem;
    font-size: 0.85rem;
    text-align: center;
}

.time-slot-card .status-badge.booked {
    background-color: #f8d7da;
    color: #721c24;
}

.schedule-legend .status-indicator {
    width: 16px;
    height: 16px;
    border-radius: 50%;
}

.schedule-legend .status-indicator.available {
    background-color: #28a745;
}

.schedule-legend .status-indicator.booked {
    background-color: #dc3545;
}

/* Facility Items Hover Effect */
.facility-item {
    transition: all 0.3s ease;
}

.facility-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Tabs Navigation
    const mobileTabSelect = document.getElementById('fieldTabsMobile');
    if (mobileTabSelect) {
        mobileTabSelect.addEventListener('change', function() {
            const tabId = this.value;
            const tabElement = document.querySelector(`button[data-bs-target="#${tabId}"]`);
            if (tabElement) {
                bootstrap.Tab.getOrCreateInstance(tabElement).show();
            }
        });
    }
});
</script>
