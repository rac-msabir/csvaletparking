/**
 * Utility functions for handling QR code printing
 */

/**
 * Print a QR code for the given ticket ID
 * @param {string|number} ticketId - The ID of the ticket to print
 * @param {Object} options - Additional options
 * @param {Function} options.onSuccess - Callback when printing is successful
 * @param {Function} options.onError - Callback when printing fails
 * @param {boolean} options.showAlerts - Whether to show browser alerts (default: true)
 */
export const printQrCode = async (ticketId, {
    onSuccess = null,
    onError = null,
    showAlerts = true
} = {}) => {
    try {
        const response = await fetch(`/print-qr/${ticketId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to print ticket');
        }

        if (onSuccess && typeof onSuccess === 'function') {
            onSuccess(data);
        } else if (showAlerts) {
            alert('Ticket sent to printer successfully!');
        }

        return data;
    } catch (error) {
        console.error('Printing error:', error);
        
        if (onError && typeof onError === 'function') {
            onError(error);
        } else if (showAlerts) {
            alert(`Printing failed: ${error.message}`);
        }
        
        throw error;
    }
};

/**
 * Initialize print button with the given selector
 * @param {string} selector - CSS selector for the print button
 * @param {string|Function} ticketId - Ticket ID or a function that returns the ticket ID
 * @param {Object} options - Additional options
 */
export const initPrintButton = (selector, ticketId, options = {}) => {
    const buttons = document.querySelectorAll(selector);
    
    if (!buttons.length) return;
    
    const handleClick = async (event) => {
        event.preventDefault();
        
        const id = typeof ticketId === 'function' ? ticketId() : ticketId;
        if (!id) {
            console.error('No ticket ID provided for printing');
            return;
        }
        
        const button = event.currentTarget;
        const originalText = button.innerHTML;
        
        try {
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Printing...';
            
            await printQrCode(id, {
                ...options,
                showAlerts: false
            });
            
            if (options.onSuccess) {
                options.onSuccess();
            } else {
                // Show success toast or notification
                const toast = document.createElement('div');
                toast.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                toast.role = 'alert';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                    <strong>Success!</strong> Ticket #${id} sent to printer.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(toast);
                
                // Auto-remove after 3 seconds
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        } catch (error) {
            console.error('Print error:', error);
            
            if (options.onError) {
                options.onError(error);
            } else {
                // Show error toast
                const toast = document.createElement('div');
                toast.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3';
                toast.role = 'alert';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                    <strong>Error!</strong> Failed to print ticket: ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(toast);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    toast.remove();
                }, 5000);
            }
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    };
    
    buttons.forEach(button => {
        button.removeEventListener('click', handleClick);
        button.addEventListener('click', handleClick);
    });
};
