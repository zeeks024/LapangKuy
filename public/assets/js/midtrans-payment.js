/**
 * Midtrans Payment JS
 * Handles payment initialization with Midtrans
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get payment token from data attribute
    const payButton = document.getElementById('pay-button');
    
    if (payButton) {
        const snapToken = payButton.getAttribute('data-token');
        const clientKey = payButton.getAttribute('data-client-key');
        const isTestMode = payButton.getAttribute('data-test-mode') === 'true';
        
        console.log('Payment initialization:', { 
            snapToken: snapToken ? snapToken.substring(0, 10) + '...' : 'not found',
            clientKey: clientKey ? clientKey.substring(0, 10) + '...' : 'not found',
            isTestMode: isTestMode
        });
        
        payButton.addEventListener('click', function() {
            // Display loading state
            const buttonText = payButton.innerHTML;
            payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Loading...';
            payButton.disabled = true;
            
            if (isTestMode) {
                console.log('TEST MODE ACTIVE - simulating payment');
                simulateTestPayment();
                return;
            }
            
            if (!snapToken || snapToken === 'undefined' || !clientKey || clientKey === 'undefined') {
                console.error('Missing snap token or client key');
                showError('Terjadi kesalahan dalam memproses pembayaran. Silakan coba lagi.');
                resetButton(buttonText);
                return;
            }
            
            try {
                // Load Midtrans Snap
                snap.pay(snapToken, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        redirectToSuccess();
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        redirectToSuccess();
                    },
                    onError: function(result) {
                        console.error('Payment error:', result);
                        showError('Terjadi kesalahan dalam memproses pembayaran. Silakan coba lagi.');
                        resetButton(buttonText);
                    },
                    onClose: function() {
                        console.log('Payment widget closed');
                        resetButton(buttonText);
                    }
                });
            } catch (e) {
                console.error('Error in snap.pay:', e);
                showError('Terjadi kesalahan dalam memproses pembayaran. Silakan coba lagi.');
                resetButton(buttonText);
            }
        });
    }
    
    function showError(message) {
        const errorElement = document.getElementById('payment-error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        } else {
            alert(message);
        }
    }
    
    function resetButton(originalText) {
        if (payButton) {
            payButton.innerHTML = originalText;
            payButton.disabled = false;
        }
    }
    
    function redirectToSuccess() {
        const bookingId = document.getElementById('booking-id').value;
        window.location.href = `/bookings/${bookingId}/payment/success`;
    }
    
    function simulateTestPayment() {
        console.log('Simulating test payment...');
        
        setTimeout(function() {
            redirectToSuccess();
        }, 2000);
    }
});
