<template>
  <PublicLayout>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md">
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Valet Parking Ticket</h1>
        <p class="text-gray-600">Your vehicle is in safe hands</p>
      </div>

      <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-lg font-semibold">Ticket #{{ ticket.ticket_number }}</h2>
            <div class="text-sm text-gray-600">
              Status: <span :class="statusBadgeClass">{{ formatStatus(ticket.status) }}</span>
            </div>
          </div>
          <div class="text-right">
            <div class="text-sm text-gray-600">
              Checked In: {{ formatDateTime(ticket.check_in_at) }}
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="font-semibold text-gray-700 mb-2">Customer Information</h3>
          <p class="text-gray-800">{{ ticket.customer_name }}</p>
          <p class="text-gray-600">{{ ticket.customer_phone }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="font-semibold text-gray-700 mb-2">Vehicle Information</h3>
          <p class="text-gray-800">{{ ticket.vehicle_make }} {{ ticket.vehicle_model }}</p>
          <p class="text-gray-600">Color: {{ ticket.vehicle_color }}</p>
          <p class="text-gray-600" v-if="ticket.license_plate">Plate: {{ ticket.license_plate }}</p>
          <p class="text-gray-600" v-if="ticket.parking_spot">Spot: {{ ticket.parking_spot }}</p>
        </div>
      </div>

      <div class="text-center text-sm text-gray-500 mt-8">
        <p>Keep this link safe to check your vehicle status anytime</p>
        <div class="mt-2 p-2 bg-gray-100 rounded text-xs break-all">
          {{ ticket.public_url }}
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
  ticket: {
    type: Object,
    required: true,
  },
});

const statusBadgeClass = computed(() => {
  const baseClasses = 'px-2 py-1 rounded-full text-xs font-medium';
  
  switch (props.ticket.status) {
    case 'pending':
      return `${baseClasses} bg-yellow-100 text-yellow-800`;
    case 'in_progress':
      return `${baseClasses} bg-blue-100 text-blue-800`;
    case 'ready':
      return `${baseClasses} bg-green-100 text-green-800`;
    case 'delivered':
      return `${baseClasses} bg-gray-100 text-gray-800`;
    case 'cancelled':
      return `${baseClasses} bg-red-100 text-red-800`;
    default:
      return `${baseClasses} bg-gray-100 text-gray-800`;
  }
});

const formatStatus = (status) => {
  return status
    .split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
};

const formatDateTime = (dateTime) => {
  if (!dateTime) return 'N/A';
  
  const options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  };
  
  return new Date(dateTime).toLocaleString(undefined, options);
};
</script>
