<template>
  <AppLayout :title="'Dashboard'">
    <!-- Toast Notifications Container -->
    <!-- The ToastContainer is automatically injected by vue3-toastify -->
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Employee Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Stats Cards -->
              <div class="bg-blue-50 p-6 rounded-lg shadow">
                <div class="flex items-center">
                  <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Active Tickets</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.active_tickets || 0 }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-green-50 p-6 rounded-lg shadow">
                <div class="flex items-center">
                  <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Employees</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.employees_count || 0 }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-purple-50 p-6 rounded-lg shadow">
                <div class="flex items-center">
                  <div class="p-3 rounded-full bg-purple-100">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Avg. Response Time</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.avg_response_time || '0m' }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Recent Tickets -->
            <div class="mt-12">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Recent Tickets</h3>
                <Link :href="route('employee.tickets.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                  New Ticket
                </Link>
              </div>
              
              <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                  <li v-for="ticket in recentTickets" :key="ticket.id">
                    <Link :href="route('employee.tickets.show', ticket.id)" class="block hover:bg-gray-50">
                      <div class="px-4 py-4 flex items-center sm:px-6">
                        <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                          <div class="truncate">
                            <div class="flex text-sm">
                              <p class="font-medium text-indigo-600 truncate">{{ ticket.title }}</p>
                              <p class="ml-1 flex-shrink-0 font-normal text-gray-500">#{{ ticket.reference }}</p>
                            </div>
                            <div class="mt-2 flex">
                              <div class="flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                {{ ticket.customer_name }}
                              </div>
                            </div>
                          </div>
                          <div class="mt-4 flex-shrink-0 sm:mt-0 sm:ml-5">
                            <div class="flex items-center text-sm text-gray-500">
                              <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                              </svg>
                              <p>{{ ticket.created_at }}</p>
                            </div>
                          </div>
                        </div>
                        <div class="ml-5 flex-shrink-0">
                          <span :class="ticket.statusClass" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                            {{ ticket.status }}
                          </span>
                        </div>
                      </div>
                    </Link>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Custom styles for notifications */
.notification {
    padding: 1rem;
    margin-bottom: 0.5rem;
    border-radius: 0.5rem;
    background-color: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-left: 4px solid #3b82f6; /* blue-500 */
}
.notification-title {
    font-weight: 600;
    color: #1f2937; /* gray-800 */
}
.notification-message {
    font-size: 0.875rem;
    color: #4b5563; /* gray-600 */
}
</style>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const props = defineProps({
    stats: Object,
    recentTickets: Array,
    auth: Object,
});

const notifications = ref([]);
// Show notification function
const showNotification = (notification) => {
    toast.info(
        `<div class="flex flex-col">
            <span class="font-semibold">${notification.title}</span>
            <span class="text-sm">${notification.message}</span>
            ${notification.url ? `<a href="${notification.url}" class="text-blue-500 hover:underline mt-1 text-xs">View Details →</a>` : ''}
        </div>`,
        {
            position: 'top-right',
            autoClose: 8000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            toastClassName: '!bg-white !text-gray-800 !shadow-lg',
            bodyClassName: 'p-0',
        }
    );
};

// Set up Echo listeners
const echo = window.Echo;
let channel = null;

onMounted(() => {
    if (echo && props.auth?.user?.id) {
        channel = echo.private(`user.${props.auth.user.id}`);
        channel.listen('.vehicle.requested', (data) => {
            showNotification({
                title: 'Vehicle Requested',
                message: data.message,
                url: data.url,
                ticketId: data.ticket_id
            });
        });
    }
});

onUnmounted(() => {
    if (channel) {
        channel.stopListening('.vehicle.requested');
        if (echo) {
            echo.leave(`user.${props.auth.user?.id}`);
        }
    }
});


defineExpose({
    showNotification
});
</script>
