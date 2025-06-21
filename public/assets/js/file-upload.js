/**
 * File Upload Component Enhancements
 * Provides functionality for image preview, URL input/image upload coordination
 */
document.addEventListener('DOMContentLoaded', function() {
    // Process all file-upload components in the page
    const fileUploadComponents = document.querySelectorAll('.file-upload-container');
    
    fileUploadComponents.forEach(container => {
        const fileInput = container.querySelector('input[type="file"]');
        const previewContainer = container.querySelector('.preview-container');
        const previewDiv = container.querySelector('.image-preview');
        
        if (!fileInput) return;
        
        // Handle file input change
        fileInput.addEventListener('change', function() {
            // Find related URL input (typically a sibling or cousin element)
            const form = this.closest('form');
            const urlInput = form.querySelector('input[name="image_url"]');
            
            // If file selected, clear URL input
            if (this.files && this.files.length > 0) {
                if (urlInput) {
                    urlInput.value = '';
                }
            }
            
            // Standard preview handling is done by the component itself
        });
    });
    
    // Handle URL input fields
    const urlInputs = document.querySelectorAll('input[name="image_url"]');
    
    urlInputs.forEach(input => {
        input.addEventListener('input', debounce(function() {
            const url = this.value.trim();
            const form = this.closest('form');
            
            // Find related file input and preview container
            const fileUploadContainer = form.querySelector('.file-upload-container');
            if (!fileUploadContainer) return;
            
            const fileInput = fileUploadContainer.querySelector('input[type="file"]');
            const previewContainer = fileUploadContainer.querySelector('.preview-container');
            const previewDiv = fileUploadContainer.querySelector('.image-preview');
            
            if (!previewContainer || !previewDiv) return;
            
            // If URL entered, clear file input
            if (url && fileInput) {
                fileInput.value = '';
            }
            
            // Update preview with URL
            if (url) {
                previewContainer.classList.remove('d-none');
                previewDiv.innerHTML = '';
                
                const img = document.createElement('img');
                img.src = url;
                img.alt = 'URL Preview';
                img.classList.add('img-thumbnail', 'me-2', 'mb-2');
                img.style.maxHeight = '100px';
                
                // Handle image loading errors
                img.onerror = function() {
                    previewDiv.innerHTML = '<div class="alert alert-warning py-2">Invalid or inaccessible image URL</div>';
                };
                
                previewDiv.appendChild(img);
            } else {
                // Hide preview if URL cleared
                previewContainer.classList.add('d-none');
            }
        }, 500));
    });
});

// Utility function to debounce inputs
function debounce(func, wait = 300) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            func.apply(this, args);
        }, wait);
    };
}
