// Session Flash Message Handler for Render Production
document.addEventListener('DOMContentLoaded', function() {
    // Handle flash messages from headers (for AJAX requests)
    function handleFlashMessages() {
        const xhr = new XMLHttpRequest();
        const originalOpen = xhr.open;
        const originalSend = xhr.send;
        
        xhr.open = function() {
            originalOpen.apply(this, arguments);
        };
        
        xhr.send = function() {
            this.addEventListener('load', function() {
                const flashHeader = this.getResponseHeader('X-Flash-Messages');
                if (flashHeader) {
                    try {
                        const messages = JSON.parse(flashHeader);
                        showFlashMessages(messages);
                    } catch (e) {
                        console.log('Flash message parse error:', e);
                    }
                }
            });
            originalSend.apply(this, arguments);
        };
    }
    
    // Display flash messages
    function showFlashMessages(messages) {
        Object.keys(messages).forEach(type => {
            showNotification(messages[type], type);
        });
    }
    
    // Show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        const bgColor = {
            'success': 'bg-green-50 border border-green-200 text-green-800',
            'error': 'bg-red-50 border border-red-200 text-red-800',
            'info': 'bg-blue-50 border border-blue-200 text-blue-800'
        };
        
        notification.className += ` ${bgColor[type] || bgColor.success}`;
        
        const icon = {
            'success': '✅',
            'error': '❌', 
            'info': 'ℹ️'
        };
        
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="text-xl mr-3">${icon[type] || icon.success}</span>
                <div>
                    <h4 class="font-semibold">${type.charAt(0).toUpperCase() + type.slice(1)}!</h4>
                    <p class="text-sm">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                    ✕
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    // Initialize handler
    handleFlashMessages();
    
    // Manual trigger for testing
    window.testFlashMessage = function(message, type = 'success') {
        showNotification(message, type);
    };
    
    // Check for server-side flash messages on page load
    const successMessage = document.querySelector('[data-success-message]');
    const errorMessage = document.querySelector('[data-error-message]');
    
    if (successMessage) {
        showNotification(successMessage.dataset.successMessage, 'success');
    }
    
    if (errorMessage) {
        showNotification(errorMessage.dataset.errorMessage, 'error');
    }
});