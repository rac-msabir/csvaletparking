/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import { configureEcho } from "@laravel/echo-vue";

const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content || '';
const user = window.authUser || {};

const scheme = (import.meta.env.VITE_REVERB_SCHEME || 'http').toLowerCase();
const host = import.meta.env.VITE_REVERB_HOST || (window.location.hostname || '127.0.0.1');
const port = Number(import.meta.env.VITE_REVERB_PORT || (scheme === 'https' ? 443 : 8080));
const appKey = import.meta.env.VITE_REVERB_APP_KEY || '';

try {
    console.debug('[Bootstrap] Configuring Echo', { scheme, host, port, hasKey: !!appKey });
    const echo = configureEcho({
        broadcaster: 'reverb',
        key: appKey,
        wsHost: host,
        wsPort: port,
        wssPort: port,
        forceTLS: scheme === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        },
    });

    window.Echo = echo;
    console.debug('[Bootstrap] Echo initialized');
} catch (e) {
    console.error('[Bootstrap] Echo initialization failed', e);
    window.Echo = null;
}