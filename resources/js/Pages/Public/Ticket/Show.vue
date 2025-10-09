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

      <!-- Map Section -->
      <div class="mt-8 mb-6">
        <h3 class="font-semibold text-gray-700 mb-3">Vehicle Location</h3>
        <div class="rounded-lg overflow-hidden border border-gray-200 h-64 relative">
          <div id="map" class="w-full h-full"></div>
          <div v-if="!hasLocation" class="absolute inset-0 flex items-center justify-center bg-gray-50">
            <p class="text-gray-500">Location data not available</p>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 mt-6">
        <button 
          @click="requestVehicle" 
          :disabled="isRequesting || ticket.status === 'in_progress'"
          class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          <span v-if="isRequesting">Requesting...</span>
          <span v-else>Bring My Car</span>
        </button>
        
        <a 
          :href="'https://www.google.com/maps/dir/?api=1&destination=' + ticket.check_in_latitude + ',' + ticket.check_in_longitude"
          target="_blank"
          class="px-6 py-3 text-center border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
        >
          Get Directions
        </a>
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
import { computed, ref, onMounted, onUnmounted } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import axios from 'axios';

const props = defineProps({
  ticket: {
    type: Object,
    required: true,
  },
});

const ticket = ref(props.ticket);

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

const hasLocation = computed(() => {
  return ticket.value?.check_in_latitude && ticket.value?.check_in_longitude;
});

const isRequesting = ref(false);

const initMap = () => {
  if (!hasLocation.value) return;
  
  // Initialize the map
  const map = L.map('map').setView(
    [ticket.value.check_in_latitude, ticket.value.check_in_longitude], 
    18 // Zoom level
  );
  
  // Add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);
  
  // Add a marker at the vehicle's location
  L.marker([ticket.value.check_in_latitude, ticket.value.check_in_longitude])
    .addTo(map)
    .bindPopup('Your vehicle is here')
    .openPopup();
};

const requestVehicle = async () => {
  if (isRequesting.value || ticket.value.status === 'in_progress') return;
  
  isRequesting.value = true;
  
  try {
    // Call your API endpoint to request the vehicle
    await axios.post(route('api.tickets.request-vehicle', { ticket: ticket.value.id }));
    
    // Show success message
    alert('Your vehicle is being prepared. Please wait at the pickup location.');
    
    // Update the ticket status
    ticket.value.status = 'in_progress';
  } catch (error) {
    console.error('Error requesting vehicle:', error);
    alert('Failed to request your vehicle. Please try again or contact staff for assistance.');
  } finally {
    isRequesting.value = false;
  }
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

// Initialize Echo for real-time updates (use the global Echo instance)
let echo = null;

// Listen for ticket status updates
const setupEchoListeners = () => {
  if (!ticket.value?.id) return null;
  if (!echo) return null;
  
  // Subscribe to the Laravel private channel for this ticket
  const channel = echo.private(`ticket.${ticket.value.id}`);
  
  // Listen for the TicketStatusUpdated event
  channel.listen('TicketStatusUpdated', (e) => {
    console.log('Ticket status updated:', e);
    // Update the ticket status when we receive an update
    if (e.ticket?.id === ticket.value.id) {
      ticket.value.status = e.ticket.status;
      
      // If the ticket is ready, show a notification
      if (e.ticket.status === 'ready') {
        alert('Your vehicle is ready for pickup!');
      }
    }
  });
  
  return channel;
};

// Clean up Echo listeners
let channel = null;

onUnmounted(() => {
  if (channel) {
    channel.stopListening('TicketStatusUpdated');
    if (echo) {
      echo.leave(`ticket.${ticket.value?.id}`);
    }
  }
});

// Initialize the map when the component is mounted
onMounted(() => {
  const initEcho = (attempt = 0) => {
    echo = window.Echo;
    if (!echo) {
      if (attempt < 10) {
        setTimeout(() => initEcho(attempt + 1), 200);
      } else {
        console.warn('Echo is not initialized after retries; skipping realtime setup.');
      }
      return;
    }
    channel = setupEchoListeners();
  };

  initEcho();
  
  if (hasLocation.value) {
    // Load Leaflet CSS and JS dynamically
    const leafletCSS = document.createElement('link');
    leafletCSS.rel = 'stylesheet';
    leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(leafletCSS);
    
    const leafletJS = document.createElement('script');
    leafletJS.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    leafletJS.onload = () => {
      // Initialize the map after Leaflet is loaded
      window.L = L; // Make L available globally
      initMap();
    };
    document.head.appendChild(leafletJS);
  }
});
</script>
