/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

// Configure axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Get CSRF token for auth headers
const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content || '';

// Configure Pusher
window.Pusher = Pusher;

// Initialize Pusher with error handling
const initPusher = () => {
    try {
        // Initialize Echo with Pusher
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
            wsHost: `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
            wsPort: 80,
            wssPort: 443,
            forceTLS: true,
            encrypted: true,
            disableStats: true,
            enabledTransports: ['wss', 'ws'],
            auth: {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            },
            authEndpoint: '/broadcasting/auth',
        });

        console.log('[Bootstrap] Pusher configured with key:', import.meta.env.VITE_PUSHER_APP_KEY);
        return true;
    } catch (error) {
        console.error('[Bootstrap] Error initializing Pusher:', error);
        window.Echo = null;
        return false;
    }
};

// Initialize Pusher when the app starts
initPusher();