<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
          <h2 class="text-lg font-medium text-gray-900">Ticket #{{ ticket.ticket_number }}</h2>
          <div class="flex space-x-3">
            <Link 
              :href="route('employee.dashboard')" 
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Back to Dashboard
            </Link>
            <Link 
              :href="route('employee.tickets.edit', ticket.id)" 
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Edit Ticket
            </Link>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
          <!-- Customer Information Section -->
          <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
            <p class="mt-1 text-sm text-gray-500">Customer details for this ticket.</p>
          </div>
          
          <div class="px-6 py-5">
            <div class="grid grid-cols-6 gap-6">
            
              <!-- Phone -->
              <div class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Customer Phone Number</p>
                <p class="text-gray-900">{{ ticket.customer_phone }}</p>
              </div>
            </div>
          </div>
          
          <div class="px-6 py-5">
            <div class="grid grid-cols-6 gap-6">
              <!-- Make -->
              <div class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Car Brand</p>
                <p class="text-gray-900">{{ ticket.vehicle_make }}</p>
              </div>

              <!-- Model -->
              <div class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Car Model</p>
                <p class="text-gray-900">{{ ticket.vehicle_model }}</p>
              </div>

              <!-- License Plate -->
              <div class="col-span-6 sm:col-span-2">
                <p class="block text-sm font-medium text-gray-700 mb-1">Car Plate</p>
                <p class="text-gray-900 font-mono">{{ ticket.license_plate }}</p>
              </div>

            </div>
          </div>
          
          <div class="px-6 pb-5">
            <div class="grid grid-cols-6 gap-6">
              <!-- Special Instructions -->
              <div class="col-span-6 md:col-span-3">
                <p class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</p>
                <div class="mt-1 bg-white p-3 border border-gray-300 rounded-md">
                  <p class="text-gray-700">{{ ticket.special_instructions || 'No special instructions provided.' }}</p>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
      </div>
    
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
  ticket: {
    type: Object,
    required: true
  },
  activities: {
    type: Array,
    default: () => []
  }
});

const formatDateTime = (value) => {
  if (!value) return 'N/A';
  const date = new Date(value);
  return date.toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const capitalize = (value) => {
  if (!value) return '';
  value = value.toString();
  return value.charAt(0).toUpperCase() + value.slice(1);
};
</script>