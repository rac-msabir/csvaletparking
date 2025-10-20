<template>
  <div class="bg-white min-h-screen p-4 print:p-0">
    <div class="no-print text-center my-4">
      <button 
        @click="printTicket" 
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2"
      >
        Print Ticket
      </button>
      <button 
        @click="closeWindow" 
        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
      >
        Close
      </button>
    </div>

    <div class="ticket max-w-[80mm] mx-auto font-sans p-4 print:p-2">
      <div class="header text-center mb-4 pb-2 border-b border-dashed border-gray-700">
        <h1 class="text-lg font-bold">CS VALET PARKING</h1>
        <p class="text-sm">{{ appName }}</p>
      </div>

      <div class="ticket-info mb-4">
        <p><strong>Ticket #:</strong> {{ ticket.ticket_number }}</p>
        <p><strong>Date:</strong> {{ formatDate(ticket.created_at) }}</p>
        <p><strong>Status:</strong> {{ capitalizeFirst(ticket.status) }}</p>
      </div>

      <div class="customer-info mb-4">
        <h2 class="font-bold border-b border-gray-200 mb-2">Customer Information</h2>
        <p><strong>Phone:</strong> {{ ticket.customer_phone }}</p>
      </div>

      <div class="vehicle-info mb-4">
        <h2 class="font-bold border-b border-gray-200 mb-2">Vehicle Information</h2>
        <p><strong>Make:</strong> {{ ticket.vehicle_make }}</p>
        <p><strong>Model:</strong> {{ ticket.vehicle_model }}</p>
        <p><strong>License Plate:</strong> {{ ticket.license_plate }}</p>
      </div>

      <div v-if="ticket.special_instructions" class="special-instructions mb-4">
        <h2 class="font-bold border-b border-gray-200 mb-2">Notes</h2>
        <p class="whitespace-pre-line">{{ ticket.special_instructions }}</p>
      </div>

      <div v-if="ticket.qr_code_url" class="qr-code mt-6 text-center">
        <img :src="ticket.qr_code_url" alt="QR Code" class="mx-auto w-48 h-48">
        <p class="text-sm mt-2">Scan to verify ticket</p>
      </div>

      <div class="footer mt-6 pt-2 text-center text-xs text-gray-500 border-t border-dashed border-gray-300">
        <p>Thank you for using our valet service!</p>
        <p>{{ appUrl }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  ticket: {
    type: Object,
    required: true
  },
  qrCodeUrl: {
    type: String,
    default: ''
  },
  appName: {
    type: String,
    default: document.head.querySelector('meta[name="app-name"]')?.content || 'Valet Parking'
  },
  appUrl: {
    type: String,
    default: window.location.origin
  }
});

const formatDate = (dateString) => {
  const options = { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit', 
    minute: '2-digit',
    hour12: true 
  };
  return new Date(dateString).toLocaleString('en-US', options);
};

const capitalizeFirst = (str) => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};

const printTicket = () => {
  window.print();
};

const closeWindow = () => {
  window.close();
};

// Auto-print when the page loads
onMounted(() => {
  setTimeout(() => {
    window.print();
    // Close the window after printing (some browsers block this)
    // window.onafterprint = () => window.close();
  }, 500);
});
</script>

<style scoped>
@media print {
  @page {
    size: 80mm 297mm; /* 80mm width, auto height */
    margin: 0;
    padding: 10px;
  }
  body {
    width: 100%;
    margin: 0;
    padding: 10px;
    font-size: 12px;
    line-height: 1.2;
  }
  .no-print {
    display: none !important;
  }
  .ticket {
    width: 100%;
    border: 1px dashed #ccc;
    padding: 10px;
    box-sizing: border-box;
  }
}
</style>
