import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import ToastPlugin from './Plugins/toast';
import 'vue3-toastify/dist/index.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Add global styles for toast container
const style = document.createElement('style');
style.textContent = `
  .Toastify__toast-container {
    z-index: 9999;
  }
  .Toastify__toast {
    border-radius: 8px;
    padding: 12px 16px;
    font-family: inherit;
  }
  .Toastify__toast--success {
    background-color: #10B981;
  }
  .Toastify__toast--error {
    background-color: #EF4444;
  }
  .Toastify__toast--info {
    background-color: #3B82F6;
  }
`;
document.head.appendChild(style);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ 
            render: () => h(App, props)
        })
        .use(plugin)
        .use(ZiggyVue)
        .use(ToastPlugin);
            
        return vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});