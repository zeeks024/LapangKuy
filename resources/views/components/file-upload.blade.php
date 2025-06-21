<!-- filepath: resources/views/components/file-upload.blade.php -->
@props([
    'name' => 'image',
    'label' => 'Upload Image',
    'accept' => 'image/*',
    'multiple' => false,
    'required' => false,
    'help' => null,
    'currentImage' => null,
    'errorName' => null
])

<div class="mb-3 file-upload-container">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    
    @if($currentImage)
    <div class="current-image mb-2">
        <img src="{{ $currentImage }}" alt="Current image" class="img-thumbnail" style="max-height: 150px;">
        <div class="mt-1 small text-muted">Current image</div>
    </div>
    @endif
    
    <div class="input-group custom-file-input">
        <input 
            type="file" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-control @error($errorName ?? $name) is-invalid @enderror" 
            accept="{{ $accept }}" 
            {{ $multiple ? 'multiple' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >
        <label class="input-group-text" for="{{ $name }}">
            <i class="fas fa-upload"></i>
        </label>
    </div>
    
    @if($help)
    <small class="form-text text-muted">{{ $help }}</small>
    @endif
    
    @error($errorName ?? $name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    
    <div class="preview-container mt-2 d-none">
        <p class="small mb-1">Preview:</p>
        <div class="image-preview"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('{{ $name }}');
    const previewContainer = input.closest('.file-upload-container').querySelector('.preview-container');
    const previewDiv = previewContainer.querySelector('.image-preview');
    
    input.addEventListener('change', function() {
        // Clear previous previews
        previewDiv.innerHTML = '';
        
        // If no files selected, hide preview container
        if (this.files.length === 0) {
            previewContainer.classList.add('d-none');
            return;
        }
        
        // Show preview container
        previewContainer.classList.remove('d-none');
        
        // Process each file
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail', 'mr-2', 'mb-2');
                img.style.maxHeight = '100px';
                previewDiv.appendChild(img);
            }
            
            reader.readAsDataURL(file);
        });
    });
});
</script>

<style>
.file-upload-container .custom-file-input {
    position: relative;
}

.file-upload-container .image-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.file-upload-container .current-image {
    border: 1px solid #dee2e6;
    display: inline-block;
    padding: 5px;
    border-radius: 4px;
}
</style>
