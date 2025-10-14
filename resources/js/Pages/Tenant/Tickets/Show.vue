<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100">
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
          <h2 class="text-lg font-medium text-gray-900">Ticket #{{ ticket.ticket_number }}</h2>
          <div class="flex space-x-3">
            <Link :href="route('tenant.dashboard')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Back to Dashboard</Link>
            <Link :href="route('tenant.tickets.edit', ticket.id)" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Edit Ticket</Link>
          </div>
        </div>
      </div>

      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
          <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
            <p class="mt-1 text-sm text-gray-500">Customer details for this ticket.</p>
          </div>
          <div class="px-6 py-5">
            <div class="grid grid-cols-6 gap-6">
              <div class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Name</p>
                <p class="text-gray-900">{{ ticket.customer_name }}</p>
              </div>
              <div class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Phone</p>
                <p class="text-gray-900">{{ ticket.customer_phone }}</p>
              </div>
              <div class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Email</p>
                <p class="text-gray-900">{{ ticket.customer_email || 'N/A' }}</p>
              </div>
            </div>
          </div>

          <div class="px-6 py-5 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Vehicle Information</h3>
            <p class="mt-1 text-sm text-gray-500">Details about the customer's vehicle.</p>
          </div>
          <div class="px-6 py-5">
            <div class="grid grid-cols-6 gap-6">
              <div class="col-span-6 sm:col-span-2">
                <p class="block text_sm font-medium text-gray-700 mb-1">Make</p>
                <p class="text-gray-900">{{ ticket.vehicle_make }}</p>
              </div>
              <div class="col-span-6 sm:col-span-2">
                <p class="block text_sm font-medium text-gray-700 mb-1">Model</p>
                <p class="text-gray-900">{{ ticket.vehicle_model }}</p>
              </div>
              <div class="col-span-6 sm:col-span-2">
                <p class="block text_sm font-medium text-gray-700 mb-1">Color</p>
                <p class="text-gray-900">{{ ticket.vehicle_color }}</p>
              </div>
              <div class="col-span-6 sm:col-span-2">
                <p class="block text_sm font-medium text-gray-700 mb-1">License Plate</p>
                <p class="text-gray-900 font-mono">{{ ticket.license_plate }}</p>
              </div>
              <div class="col-span-6 sm:col-span-2">
                <p class="block text_sm font-medium text-gray-700 mb-1">Parking Spot</p>
                <p class="text-gray-900">{{ ticket.parking_spot || 'N/A' }}</p>
              </div>
              <div class="col-span-6 sm:col-span-2">
                <p class="block text_sm font-medium text-gray-700 mb-1">Check-in Time</p>
                <p class="text-gray-900">{{ formatDateTime(ticket.check_in_at) }}</p>
              </div>
            </div>
          </div>

           <div class="my-8 mx-6">
              <h4 class="text-md font-medium text-gray-900 mb-4">Vehicle Images</h4>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div v-for="(image, index) in ticket.images" :key="image.id" class="relative group">
                  <img 
                    :src="'/storage/' + image.path" 
                    :alt="'Vehicle image ' + (index + 1)"
                    class="h-40 w-full object-cover rounded-md shadow-sm"
                  >
                  <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center rounded-md transition-opacity">
                    <a 
                      :href="'/storage/' + image.path" 
                      target="_blank" 
                      class="text-white p-2 hover:text-gray-200"
                      title="View Full Size"
                    >
                      <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>


          <div class="px-6 py-5 border-t border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
            <p class="mt-1 text-sm text-gray-500">Special instructions and notes for this ticket.</p>
          </div>
          <div class="px-6 pb-5">
            <div class="w-full ">
              
                <p class="block w-full text-sm font-medium text-gray-700 mb-1">Special Instructions</p>
                <div class="mt-1 bg-white p-3 border border-gray-300 rounded-md">
                  <p class="text-gray-700">{{ ticket.special_instructions || 'No special instructions provided.' }}</p>
                </div>
              <!-- <div class="col-span-6 md:col-span-3">
                <p class="block text-sm font-medium text-gray-700 mb-1">Damage Notes</p>
                <div class="mt-1 bg-white p-3 border border-gray-300 rounded-md">
                  <p class="text-gray-700">{{ ticket.damage_notes || 'No damage reported.' }}</p>
                </div>
              </div> -->
            </div>
          </div>

          <!-- <div class="px-6 py-5 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Payment Information</h3>
          </div>
          <div class="px-6 pb-5">
            <div class="grid grid-cols-6 gap-6">
              <div class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Amount</p>
                <p class="text-2xl font-bold text-gray-900">${{ parseFloat(ticket.amount || 0).toFixed(2) }}</p>
              </div>
              <div class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Payment Status</p>
                <span 
                  :class="{ 'bg-green-100 text-green-800': ticket.payment_status === 'paid', 'bg-yellow-100 text-yellow-800': ticket.payment_status !== 'paid' }" 
                  class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ ticket.payment_status || 'pending' | capitalize }}
                </span>
              </div>
              <div v-if="ticket.payment_status === 'paid'" class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Paid At</p>
                <p class="text-gray-900">{{ formatDateTime(ticket.paid_at) }}</p>
              </div>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
  ticket: { type: Object, required: true },
  activities: { type: Array, default: () => [] },
});

const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  return date.toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const capitalize = (value) => {
  if (!value) return '';
  value = value.toString();
  return value.charAt(0).toUpperCase() + value.slice(1);
};
</script>
