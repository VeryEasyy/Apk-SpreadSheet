/**
 * Global Toast Notification System
 * Minimalist toast notifications - Bottom Left Corner
 * File: public/js/components/toast-notification.js
 */


function initToast() {
    if (!document.getElementById('globalToast')) {
        const toast = document.createElement('div');
        toast.id = 'globalToast';
        toast.className = 'global-toast-notification';
        toast.innerHTML = `
            <span id="globalToastMessage"></span>
            <button class="toast-close" onclick="hideGlobalToast()">
                <i class="bi bi-x"></i>
            </button>
        `;
        document.body.appendChild(toast);
    }
}

// Show Toast Notification
function showToast(message, duration = null) {
    initToast();
    
    const toast = document.getElementById('globalToast');
    const messageEl = document.getElementById('globalToastMessage');
    
    messageEl.textContent = message;
    toast.classList.add('show');

    // Only auto-hide if duration is specified
    if (duration !== null && duration > 0) {
        setTimeout(() => {
            hideGlobalToast();
        }, duration);
    }
    // Otherwise, toast stays until user clicks X button
}

// Hide Toast Notification
function hideGlobalToast() {
    const toast = document.getElementById('globalToast');
    if (toast) {
        toast.classList.remove('show');
    }
}

// Add CSS for global toast if not exists
function addToastStyles() {
    if (!document.getElementById('globalToastStyles')) {
        const style = document.createElement('style');
        style.id = 'globalToastStyles';
        style.textContent = `
            .global-toast-notification {
                position: fixed;
                bottom: 24px;
                left: 24px;
                transform: translateX(-400px);
                background: #323232;
                color: white;
                padding: 16px 20px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 20px;
                font-family: 'Poppins', sans-serif;
                font-size: 14px;
                font-weight: 400;
                box-shadow: 0 4px 16px rgba(0,0,0,0.3);
                z-index: 9999;
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                min-width: 300px;
                max-width: 400px;
            }

            .global-toast-notification.show {
                opacity: 1;
                transform: translateX(0);
            }

            .global-toast-notification .toast-close {
                background: none;
                border: none;
                color: white;
                font-size: 22px;
                cursor: pointer;
                padding: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0.7;
                transition: opacity 0.2s ease;
                flex-shrink: 0;
                line-height: 1;
            }

            .global-toast-notification .toast-close:hover {
                opacity: 1;
            }

            .global-toast-notification .toast-close i {
                font-size: 22px;
                font-weight: 600;
            }

            #globalToastMessage {
                flex: 1;
            }

            @media (max-width: 768px) {
                .global-toast-notification {
                    left: 16px;
                    right: 16px;
                    min-width: auto;
                    transform: translateX(-500px);
                }
                
                .global-toast-notification.show {
                    transform: translateX(0);
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    addToastStyles();
    
    // Check for session messages
    const successMessage = document.querySelector('[data-success-message]');
    if (successMessage) {
        const message = successMessage.dataset.successMessage;
        showToast(message);
    }

    const errorMessage = document.querySelector('[data-error-message]');
    if (errorMessage) {
        const message = errorMessage.dataset.errorMessage;
        showToast(message);
    }
});

// Export functions to global scope
window.showToast = showToast;
window.hideGlobalToast = hideGlobalToast;