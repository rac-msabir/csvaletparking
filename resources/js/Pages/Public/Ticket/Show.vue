<template>
  <PublicLayout>
    <!-- dir="rtl" -->
    <div class="max-w-3xl mx-auto px-4 py-6 pb-28" >
      <!-- Ticket creation date (top-right) -->
      <div class="flex justify-end mb-3">
        <div class="text-sm text-gray-700">Ticket creation date: <span class="font-medium">{{ formatDateTime(ticket.check_in_at) }}</span></div>
      </div>

      <!-- Main Card -->
      <div class="relative bg-white border border-gray-200 rounded-2xl p-0 h-[100%]">
        <!-- status chip -->
         <div class="flex justify-between">
           <div class="m-4">
             <span class="inline-block px-3 py-1 text-xs rounded-md bg-rose-100 text-rose-700 border border-rose-200">{{ ticket.payment_status || 'unpaid' }}</span>
           </div>
           <!-- small green bar -->
           <div class="m-4 w-6 h-1 rounded-full bg-green-400"></div>
         </div>

        <!-- Header row -->
        <div class="px-5 pt-6 pb-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="text-gray-500 text-lg">›</span>
              <span class="text-gray-500 text-lg">#</span>
              <h2 class="text-lg font-semibold text-gray-800">Ticket Details</h2>
            </div>
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center ring-1 ring-gray-200">
              <span class="text-gray-400 text-lg">📷</span>
            </div>
          </div>
        </div>

        <!-- divider -->
        <hr class="mx-6"></hr>

        <!-- Details grid -->
        <div class="px-5 mt-4">
          <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-right">
            <div class="text-md font-bold text-gray-500">Customer phone number</div>
            <div class="text-md font-bold text-gray-500">Customer name</div>
            <div class="text-gray-700 text-sm">{{ ticket.customer_phone || '-' }}</div>
            <div class="text-gray-700 text-sm">{{ ticket.customer_name || '-' }}</div>

            <div class="text-md font-bold text-gray-500">Car model</div>
            <div class="text-md font-bold text-gray-500">car company</div>
            <div class="text-gray-700 text-sm">{{ ticket.vehicle_model || '-' }}</div>
            <div class="text-gray-700 text-sm">{{ ticket.vehicle_make || ticket.vehicle_company || '-' }}</div>

            <div class="text-md font-bold text-gray-500">The company</div>
            <div class="text-md font-bold text-gray-500">car plate</div>
            <div class="text-gray-700 text-sm">{{ ticket.company || '-' }}</div>
            <div class="text-gray-700 text-sm">{{ ticket.license_plate || '-' }}</div>
          </div>
        </div>

        <!-- divider -->
        <hr class="m-6"></hr>
        
        <!-- Map teaser bar -->
        <div class="px-5 pb-6">
          <div class="rounded-xl overflow-hidden">
            <div class="flex items-stretch justify-between bg-indigo-50">
              <button
                type="button"
                @click="openDirections"
                :disabled="!hasLocation"
                class="flex-1 text-left px-4 py-4 text-md font-bold text-indigo-900 hover:bg-indigo-100 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Show on map
              </button>
              <div class="w-px bg-indigo-100"></div>
              <div class="flex-1 px-4 py-3">
                <div class="flex items-center justify-between">
                  <div>
                    <div class="text-md font-bold text-gray-800">Branch location</div>
                    <div class="text-xs text-gray-500">{{ ticket.branch_name || '-' }}</div>
                  </div>
                  <div class="w-8 h-8 rounded-full bg-indigo-200/60 ring-1 ring-indigo-300 flex items-center justify-center">
                    <span class="text-indigo-800 text-xl font-bold">⬡</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Spacer card under main (empty placeholder like screenshot) -->
      <div class="mt-4">
        <div class="w-full h-12 rounded-xl bg-white border border-gray-200"></div>
      </div>

      <!-- Terms and security note -->
      <div class="mt-6 text-sm text-gray-600 flex items-center flex-wrap gap-x-3 gap-y-2">
        <a href="#" class="underline">Terms and Conditions</a>
        <a href="#" class="underline">Fully safe and secure</a>
        <span>Your Valley platform</span>
        <span class="ml-auto">
          <span class="inline-flex items-center justify-center align-middle text-indigo-900"></span>
        </span>
      </div>

      <!-- Fixed bottom action bar -->
      <div class="fixed inset-x-0 bottom-0 z-50 bg-white/90 backdrop-blur border-t">
        <div class="max-w-3xl mx-auto px-4 py-3">
          <button
            @click="requestVehicle"
            :disabled="isRequesting || ticket.status === 'in_progress'"
            class="w-full h-14 rounded-xl bg-[#0f2551] text-white text-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="isRequesting">Requesting...</span>
            <span v-else>Bring the car</span>
          </button>
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

const openDirections = () => {
  if (!hasLocation.value) return;
  const url = `https://www.google.com/maps/dir/?api=1&destination=${ticket.value.check_in_latitude},${ticket.value.check_in_longitude}`;
  if (typeof window !== 'undefined' && window && window.open) {
    window.open(url, '_blank');
  } else {
    console.warn('Window is not available to open directions');
  }
};
</script>
