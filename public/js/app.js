// Base app JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('App initialized without Vite');
    
    // Setup CSRF token for AJAX requests
    if (typeof $ !== 'undefined' && $) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
    }
});

// Add any additional JavaScript from resources/js/app.js as needed
