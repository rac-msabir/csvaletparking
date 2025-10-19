import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
    install: (app) => {
        // Base toast configuration
        const baseConfig = {
            position: 'top-right',
            autoClose: 5000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            theme: 'colored',  // Changed from 'colored' to 'light'
            className: 'text-white', // Using !important to ensure it overrides
            bodyClassName: 'text-white', // Ensure text color in body
            style: {
                color: '#ffffff !important',
                boxShadow: 'none', // Remove any shadow that might cause the extra background
                border: 'none' // Remove any border
            }
        };

        // Add toast to the global properties
        app.config.globalProperties.$toast = toast;
        
        // Also add it to provide/inject if needed
        app.provide('toast', toast);
        
        // Custom methods with bright colors
        const showSuccess = (message) => {
            toast.success(message, {
                ...baseConfig,
                className: '!bg-green-600 text-white', // Using !important
                style: {
                    backgroundColor: '#10B981 !important',
                    color: '#ffffff !important',
                    background: '#10B981 !important' // Ensure background is set
                }
            });
        };
        
        const showError = (message) => {
            toast.error(message, {
                ...baseConfig,
                className: '!bg-red-600 text-white', // Using !important
                style: {
                    backgroundColor: '#EF4444 !important',
                    color: '#ffffff !important',
                    background: '#EF4444 !important' // Ensure background is set
                }
            });
        };
        
        const showInfo = (message) => {
            toast.info(message, {
                ...baseConfig,
                className: '!bg-blue-600 text-white', // Using !important
                style: {
                    backgroundColor: '#3B82F6 !important',
                    color: '#ffffff !important',
                    background: '#3B82F6 !important' // Ensure background is set
                }
            });
        };
        
        // Add custom methods to the global properties
        app.config.globalProperties.$toast = {
            ...toast,
            success: showSuccess,
            error: showError,
            info: showInfo,
        };
        
        // Also provide these methods
        app.provide('toast', {
            ...toast,
            success: showSuccess,
            error: showError,
            info: showInfo,
        });
    },
};