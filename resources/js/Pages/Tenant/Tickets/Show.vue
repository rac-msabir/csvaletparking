<template>
    <AppLayout>
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ticket #{{ ticket.ticket_number }}
          </h2>
          <div class="flex space-x-2">
            <Link 
              :href="route('tenant.tickets.edit', ticket.id)" 
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
              Edit
            </Link>
            <Link 
              :href="route('tenant.tickets.index')" 
              class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
              Back to Tickets
            </Link>
          </div>
        </div>
      </template>
  
      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <!-- Status Badge -->
              <div class="mb-6">
                <span 
                  :class="{
                    'bg-yellow-100 text-yellow-800': ticket.status === 'pending',
                    'bg-blue-100 text-blue-800': ticket.status === 'in_progress',
                    'bg-green-100 text-green-800': ticket.status === 'ready',
                    'bg-purple-100 text-purple-800': ticket.status === 'delivered',
                    'bg-red-100 text-red-800': ticket.status === 'cancelled'
                  }" 
                  class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ ticket.status.replace('_', ' ') | capitalize }}
                </span>
              </div>
  
              <!-- Customer Information -->
              <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium">{{ ticket.customer_name }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="font-medium">{{ ticket.customer_phone }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ ticket.customer_email || 'N/A' }}</p>
                  </div>
                </div>
              </div>
  
              <!-- Vehicle Information -->
              <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Vehicle Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                  <div>
                    <p class="text-sm text-gray-500">Make</p>
                    <p class="font-medium">{{ ticket.vehicle_make }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Model</p>
                    <p class="font-medium">{{ ticket.vehicle_model }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Color</p>
                    <p class="font-medium">{{ ticket.vehicle_color }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">License Plate</p>
                    <p class="font-mono">{{ ticket.license_plate }}</p>
                  </div>
                </div>
              </div>
  
              <!-- Parking Information -->
              <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Parking Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <p class="text-sm text-gray-500">Parking Spot</p>
                    <p class="font-medium">{{ ticket.parking_spot || 'N/A' }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Zone</p>
                    <p class="font-medium">{{ ticket.parking_zone || 'N/A' }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Check-in Time</p>
                    <p class="font-medium">{{ formatDateTime(ticket.check_in_at) }}</p>
                  </div>
                </div>
              </div>
  
              <!-- Special Instructions & Notes -->
              <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h3 class="text-lg font-medium text-gray-900 mb-2">Special Instructions</h3>
                  <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ ticket.special_instructions || 'No special instructions provided.' }}</p>
                </div>
                <div>
                  <h3 class="text-lg font-medium text-gray-900 mb-2">Damage Notes</h3>
                  <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ ticket.damage_notes || 'No damage reported.' }}</p>
                </div>
              </div>
  
              <!-- Payment Information -->
              <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <p class="text-sm text-gray-500">Amount</p>
                    <p class="text-xl font-bold">${{ parseFloat(ticket.amount || 0).toFixed(2) }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Payment Status</p>
                    <span 
                      :class="{
                        'bg-green-100 text-green-800': ticket.payment_status === 'paid',
                        'bg-yellow-100 text-yellow-800': ticket.payment_status !== 'paid'
                      }" 
                      class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    >
                      {{ ticket.payment_status || 'pending' | capitalize }}
                    </span>
                  </div>
                  <div v-if="ticket.payment_status === 'paid'">
                    <p class="text-sm text-gray-500">Paid At</p>
                    <p class="font-medium">{{ formatDateTime(ticket.paid_at) }}</p>
                  </div>
                </div>
              </div>
  
              <!-- Activity Timeline -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Timeline</h3>
                <div class="flow-root">
                  <ul class="-mb-8">
                    <li v-for="(activity, index) in activities" :key="activity.id">
                      <div class="relative pb-8">
                        <span v-if="index !== activities.length - 1" 
                          class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" 
                          aria-hidden="true"></span>
                        <div class="relative flex space-x-3">
                          <div>
                            <span 
                              :class="{
                                'bg-green-500': activity.event === 'created',
                                'bg-blue-500': activity.event === 'status_changed',
                                'bg-yellow-500': activity.event === 'updated',
                                'bg-gray-500': !activity.event
                              }" 
                              class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white"
                            >
                              <svg v-if="activity.event === 'created'" class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                              </svg>
                              <svg v-else-if="activity.event === 'status_changed'" class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                              </svg>
                              <svg v-else class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                              </svg>
                            </span>
                          </div>
                          <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                              <p class="text-sm text-gray-500">
                                {{ activity.description }}
                                <span v-if="activity.properties && activity.properties.status" 
                                  class="ml-1 font-medium text-gray-900">
                                  {{ activity.properties.status.replace('_', ' ') | capitalize }}
                                </span>
                              </p>
                            </div>
                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                              {{ formatDateTime(activity.created_at) }}
                            </div>
                          </div>
                        </div>
                      </div>
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
  
  <script>
  import { defineComponent } from 'vue';
  import { Link } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  export default defineComponent({
    components: {
      Link,
      AppLayout,
    },
  
    props: {
      ticket: {
        type: Object,
        required: true,
      },
    },
  
    computed: {
      activities() {
        // Make sure we have activities and sort them by created_at in descending order
        if (!this.ticket.activities) return [];
        return [...this.ticket.activities].sort((a, b) => 
          new Date(b.created_at) - new Date(a.created_at)
        );
      }
    },
  
    methods: {
      formatDateTime(value) {
        if (!value) return 'N/A';
        return new Date(value).toLocaleString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      },
    },
  
    filters: {
      capitalize: function (value) {
        if (!value) return '';
        value = value.toString();
        return value.charAt(0).toUpperCase() + value.slice(1);
      }
    }
  });
  </script>