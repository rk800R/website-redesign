/**
 * Contact Form Handler
 * Manages form submission status messages and interactions
 */

(function() {
    'use strict';

    // Handle status messages from URL parameter
    function handleStatusMessages() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const alertDiv = document.getElementById('statusAlert');
        
        if (!status || !alertDiv) return;

        const messages = {
            success: {
                class: 'alert alert-success',
                text: '<strong>Success!</strong> Your message has been sent successfully. We\'ll get back to you within 2 business days.'
            },
            error: {
                class: 'alert alert-error',
                text: '<strong>Error!</strong> Something went wrong. Please try again or contact us directly at info@pcb.com.pk'
            },
            db_error: {
                class: 'alert alert-error',
                text: '<strong>Error!</strong> Something went wrong. Please try again or contact us directly at info@pcb.com.pk'
            },
            validation_error: {
                class: 'alert alert-error',
                text: '<strong>Validation Error!</strong> Please fill in all required fields correctly.'
            }
        };

        const messageConfig = messages[status];
        
        if (messageConfig) {
            alertDiv.className = messageConfig.class;
            alertDiv.innerHTML = messageConfig.text;
            alertDiv.style.display = 'block';
            
            // Scroll to alert
            alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertDiv.style.display = 'none';
                // Clean URL without reloading
                const cleanUrl = window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }, 5000);
        }
    }

    // Optional: Client-side validation before submit
    function validateForm(event) {
        const form = event.target;
        const fullName = form.full_name.value.trim();
        const email = form.email.value.trim();
        const message = form.message.value.trim();
        
        if (!fullName || !email || !message) {
            alert('Please fill in all required fields');
            event.preventDefault();
            return false;
        }
        
        return true;
    }

    // Initialize when DOM is ready
    function init() {
        handleStatusMessages();
        
        // Attach form validation (optional)
        const contactForm = document.querySelector('.contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', validateForm);
        }
    }

    // Run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
