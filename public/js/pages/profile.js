document.addEventListener('DOMContentLoaded', function() {
    initializeFormValidation();
    initializePhoneInput();
    initializeAnimations();
});

function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const fullName = form.querySelector('[name="full_name"]');
            
            if (fullName && !fullName.value.trim()) {
                e.preventDefault();
                showError('Nama lengkap harus diisi!');
                fullName.focus();
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Menyimpan...';
                
                // Re-enable after timeout (in case of error)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                }, 5000);
            }
        });
    });
}

/**
 * Initialize phone input formatting
 */
function initializePhoneInput() {
    const phoneInputs = document.querySelectorAll('input[name="phone"]');
    
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            // Remove non-numeric characters
            let value = e.target.value.replace(/[^0-9]/g, '');
            
            // Limit to 13 digits (Indonesian phone number)
            if (value.length > 13) {
                value = value.slice(0, 13);
            }
            
            e.target.value = value;
        });
        
        input.addEventListener('blur', function(e) {
            const value = e.target.value;
            
            // Validate Indonesian phone number
            if (value && !value.startsWith('08') && !value.startsWith('62')) {
                showError('Format nomor telepon tidak valid. Gunakan format 08xx atau 62xx');
                e.target.focus();
            }
        });
    });
}

/**
 * Initialize animations
 */
function initializeAnimations() {
    // Animate detail items on page load
    const detailItems = document.querySelectorAll('.detail-item');
    detailItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add ripple effect to buttons
    addRippleEffect();
}

/**
 * Add ripple effect to buttons
 */
function addRippleEffect() {
    const buttons = document.querySelectorAll('.btn-edit-profile, .btn-create-profile, .btn-primary-modern');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;
            
            button.style.position = 'relative';
            button.style.overflow = 'hidden';
            button.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Add ripple animation
    if (!document.getElementById('ripple-animation')) {
        const style = document.createElement('style');
        style.id = 'ripple-animation';
        style.textContent = `
            @keyframes ripple {
                from {
                    transform: scale(0);
                    opacity: 1;
                }
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

/**
 * Show error notification
 */
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Perhatian!',
        text: message,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'OK'
    });
}

/**
 * Confirm before leaving with unsaved changes
 */
window.addEventListener('beforeunload', function(e) {
    const forms = document.querySelectorAll('form');
    let hasChanges = false;
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (input.value !== input.defaultValue) {
                hasChanges = true;
            }
        });
    });
    
    if (hasChanges) {
        e.preventDefault();
        e.returnValue = '';
    }
});