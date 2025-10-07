<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create New Ticket
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <form @submit.prevent="submit">
              <div class="space-y-6">
                <!-- Customer Information -->
                <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
                  <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                      <h3 class="text-lg font-medium leading-6 text-gray-900">Customer Information</h3>
                      <p class="mt-1 text-sm text-gray-500">Customer details for this ticket.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                          <label for="customer_name" class="block text-sm font-medium text-gray-700">Name *</label>
                          <input 
                            type="text" 
                            id="customer_name" 
                            v-model="form.customer_name" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.customer_name }"
                          />
                          <p v-if="form.errors.customer_name" class="mt-2 text-sm text-red-600">{{ form.errors.customer_name }}</p>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                          <label for="customer_phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                          <input 
                            type="tel" 
                            id="customer_phone" 
                            v-model="form.customer_phone" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.customer_phone }"
                          />
                          <p v-if="form.errors.customer_phone" class="mt-2 text-sm text-red-600">{{ form.errors.customer_phone }}</p>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                          <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                          <input 
                            type="email" 
                            id="customer_email" 
                            v-model="form.customer_email" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.customer_email }"
                          />
                          <p v-if="form.errors.customer_email" class="mt-2 text-sm text-red-600">{{ form.errors.customer_email }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Vehicle Information -->
                <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
                  <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                      <h3 class="text-lg font-medium leading-6 text-gray-900">Vehicle Information</h3>
                      <p class="mt-1 text-sm text-gray-500">Details about the customer's vehicle.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-2">
                          <label for="vehicle_make" class="block text-sm font-medium text-gray-700">Make *</label>
                          <input 
                            type="text" 
                            id="vehicle_make" 
                            v-model="form.vehicle_make" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.vehicle_make }"
                          />
                          <p v-if="form.errors.vehicle_make" class="mt-2 text-sm text-red-600">{{ form.errors.vehicle_make }}</p>
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                          <label for="vehicle_model" class="block text-sm font-medium text-gray-700">Model *</label>
                          <input 
                            type="text" 
                            id="vehicle_model" 
                            v-model="form.vehicle_model" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.vehicle_model }"
                          />
                          <p v-if="form.errors.vehicle_model" class="mt-2 text-sm text-red-600">{{ form.errors.vehicle_model }}</p>
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                          <label for="vehicle_color" class="block text-sm font-medium text-gray-700">Color *</label>
                          <input 
                            type="text" 
                            id="vehicle_color" 
                            v-model="form.vehicle_color" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.vehicle_color }"
                          />
                          <p v-if="form.errors.vehicle_color" class="mt-2 text-sm text-red-600">{{ form.errors.vehicle_color }}</p>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                          <label for="license_plate" class="block text-sm font-medium text-gray-700">License Plate</label>
                          <input 
                            type="text" 
                            id="license_plate" 
                            v-model="form.license_plate" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.license_plate }"
                          />
                          <p v-if="form.errors.license_plate" class="mt-2 text-sm text-red-600">{{ form.errors.license_plate }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Parking Information -->
                <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
                  <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                      <h3 class="text-lg font-medium leading-6 text-gray-900">Parking Information</h3>
                      <p class="mt-1 text-sm text-gray-500">Where the vehicle will be parked.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-2">
                          <label for="parking_zone" class="block text-sm font-medium text-gray-700">Zone</label>
                          <input 
                            type="text" 
                            id="parking_zone" 
                            v-model="form.parking_zone" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.parking_zone }"
                          />
                          <p v-if="form.errors.parking_zone" class="mt-2 text-sm text-red-600">{{ form.errors.parking_zone }}</p>
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                          <label for="parking_spot" class="block text-sm font-medium text-gray-700">Spot</label>
                          <input 
                            type="text" 
                            id="parking_spot" 
                            v-model="form.parking_spot" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.parking_spot }"
                          />
                          <p v-if="form.errors.parking_spot" class="mt-2 text-sm text-red-600">{{ form.errors.parking_spot }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
                  <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                      <h3 class="text-lg font-medium leading-6 text-gray-900">Additional Information</h3>
                      <p class="mt-1 text-sm text-gray-500">Any special instructions or notes.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                          <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assigned To</label>
                          <div class="mt-1">
                            <input 
                              type="text" 
                              :value="$page.props.auth.user.name" 
                              disabled 
                              class="block w-full border border-gray-300 bg-gray-100 rounded-md shadow-sm py-2 px-3 text-gray-700 sm:text-sm"
                            >
                          </div>
                          <p class="mt-1 text-sm text-gray-500">Tickets are automatically assigned to you.</p>
                        </div>


                        <div class="col-span-6">
                          <label for="special_instructions" class="block text-sm font-medium text-gray-700">Special Instructions</label>
                          <textarea 
                            id="special_instructions" 
                            v-model="form.special_instructions" 
                            rows="3" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300 text-red-900': form.errors.special_instructions }"
                          ></textarea>
                          <p v-if="form.errors.special_instructions" class="mt-2 text-sm text-red-600">{{ form.errors.special_instructions }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end">
                  <Link 
                    :href="route('employee.dashboard')" 
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    Cancel
                  </Link>
                  <button 
                    type="submit" 
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="form.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                  >
                    <span v-if="form.processing">
                      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Processing...
                    </span>
                    <span v-else>Create Ticket</span>
                  </button>
                </div>
                <!-- Location Status -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-lg">
                  <p class="text-sm text-gray-500 text-left">
                    <span v-if="!isLocationReady" class="text-yellow-600">{{ locationStatus }}</span>
                    <span v-else class="text-green-600">{{ locationStatus }}</span>
                  </p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- QR Code Modal -->
    <div v-if="showQRModal" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showQRModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
          <div class="text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
              Ticket Created Successfully!
            </h3>
            <div class="mt-2">
              <div class="flex justify-center mb-4">
                <img :src="storedQrCodeUrl" alt="QR Code" class="w-48 h-48" />
              </div>
              <p class="text-sm text-gray-500 mb-4">
                Please provide this QR code to the customer for vehicle retrieval.
              </p>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 grid grid-cols-2 gap-3">
            <button type="button" 
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
              @click="downloadQRCode">
              Download QR Code
            </button>
            <button type="button" 
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm"
              @click="printQRCode">
              Print QR Code
            </button>
            <Link 
              :href="route('employee.dashboard')" 
              class="col-span-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
              Close
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted, nextTick } from 'vue';
import getTicketPrintTemplate from '@/Components/PrintTemplates/TicketPrintTemplate';
import QRCode from 'qrcode';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  employees: Array,
});

const form = useForm({
  customer_name: '',
  customer_phone: '',
  customer_email: '',
  vehicle_make: '',
  vehicle_model: '',
  vehicle_color: '',
  license_plate: '',
  parking_zone: '',
  parking_spot: '',
  special_instructions: '',
  check_in_latitude: null,
  check_in_longitude: null,
  check_out_latitude: null,
  check_out_longitude: null,
  errors: {},
});

const showQRModal = ref(false);
const locationStatus = ref('Getting your location...');

const isLocationReady = ref(false);

// Get user's current location when component mounts
onMounted(() => {
  getLocation();
});

async function getLocation() {
  if (!navigator.geolocation) {
    locationStatus.value = 'Geolocation is not supported by your browser';
    return;
  }

  try {
    locationStatus.value = 'Getting your location...';
    const position = await new Promise((resolve, reject) => {
      navigator.geolocation.getCurrentPosition(resolve, reject, {
        enableHighAccuracy: true,
        timeout: 10000, // Increased timeout to 10 seconds
        maximumAge: 0
      });
    });

    // Set check-in location
    form.check_in_latitude = position.coords.latitude;
    form.check_in_longitude = position.coords.longitude;
    
    // For new tickets, we'll set check-out location to null initially
    // as it will be updated when the vehicle is checked out
    form.check_out_latitude = null;
    form.check_out_longitude = null;
    
    isLocationReady.value = true;
    locationStatus.value = 'Location captured successfully';
  } catch (error) {
    console.error('Error getting location:', error);
    switch(error.code) {
      case error.PERMISSION_DENIED:
        locationStatus.value = 'Location access was denied. Please enable location services for this site.';
        break;
      case error.POSITION_UNAVAILABLE:
        locationStatus.value = 'Location information is unavailable.';
        break;
      case error.TIMEOUT:
        locationStatus.value = 'The request to get location timed out.';
        break;
      default:
        locationStatus.value = 'An unknown error occurred while getting your location.';
    }
  }
}
const qrCodeUrl = ref('');
const storedQrCodeUrl = ref('');
const qrCodeCanvas = ref(null);

const generateQRCode = async (ticket) => {
  try {
    // Use the same format as the backend
    const qrData = {
      ticket: {
        id: ticket.id,
        ticket_number: ticket.ticket_number,
        customer_name: ticket.customer_name,
        vehicle_make: ticket.vehicle_make,
        vehicle_model: ticket.vehicle_model,
        license_plate: ticket.license_plate,
        created_at: new Date().toISOString(),
        qr_code_path: ticket.qr_code_path || ''
      }
    };

    const url = await QRCode.toDataURL(JSON.stringify(qrData), {
      width: 300,
      margin: 2,
      color: {
        dark: '#000000',
        light: '#ffffff'
      }
    });
    qrCodeUrl.value = url;
    showQRModal.value = true;
  } catch (err) {
    console.error('Error generating QR code:', err);
  }
};

const downloadQRCode = async () => {
  try {
    // Use the stored QR code URL if available, otherwise use the generated one
    const qrCodeSource = storedQrCodeUrl.value || qrCodeUrl.value;
    
    if (!qrCodeSource) {
      console.error('No QR code available to download');
      return;
    }

    // Get the ticket number from the form or use a default
    const ticketNumber = form.ticket_number || qrCodeSource;
    // Clean the ticket number to ensure it's a valid filename
    const cleanTicketNumber = ticketNumber.toString().replace(/[^a-zA-Z0-9-_]/g, '-');
    const fileName = `ticket-${cleanTicketNumber}`;
    
    // If we have a stored URL (from the server)
    if (storedQrCodeUrl.value) {
      // Ensure we have an absolute URL
      const absoluteUrl = qrCodeSource.startsWith('http') 
        ? qrCodeSource 
        : `${window.location.origin}${qrCodeSource}`;
      
      try {
        // Fetch the SVG content directly
        const response = await fetch(absoluteUrl, { 
          headers: {
            'Accept': 'image/svg+xml',
            'Cache-Control': 'no-cache'
          },
          cache: 'no-cache'
        });
        
        if (!response.ok) throw new Error('Failed to fetch SVG');
        
        const svgText = await response.text();
        const blob = new Blob([svgText], { type: 'image/svg+xml' });
        const blobUrl = window.URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.href = blobUrl;
        link.download = fileName;
        link.rel = 'noopener noreferrer';
        
        document.body.appendChild(link);
        link.click();
        
        // Clean up
        setTimeout(() => {
          document.body.removeChild(link);
          window.URL.revokeObjectURL(blobUrl);
        }, 100);
        
      } catch (err) {
        console.error('Error downloading SVG:', err);
        // Fallback to opening in new tab if download fails
        window.open(absoluteUrl, '_blank');
      }
    } 
    // If we have a data URL (from the generated QR code)
    else if (qrCodeSource.startsWith('data:')) {
      const link = document.createElement('a');
      link.href = qrCodeSource;
      link.download = fileName;
      link.rel = 'noopener noreferrer';
      
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
    
  } catch (error) {
    console.error('Error downloading QR code:', error);
  }
};

const printQRCode = async () => {
  try {
    const qrCodeSource = storedQrCodeUrl.value || qrCodeUrl.value;
    const ticketNumber = form.ticket_number || 'N/A';
    const now = new Date();
    const formattedDate = now.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });

    // Create a canvas for the QR code
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const img = new Image();
    
    // Load the QR code image
    await new Promise((resolve, reject) => {
      img.crossOrigin = 'Anonymous';
      img.onload = resolve;
      img.onerror = reject;
      img.src = qrCodeSource;
    });

    // Set canvas size to match QR code
    canvas.width = img.width;
    canvas.height = img.height;
    ctx.drawImage(img, 0, 0);
    
    // Convert QR code to black and white for better thermal printing
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    for (let i = 0; i < data.length; i += 4) {
      const avg = (data[i] + data[i + 1] + data[i + 2]) / 3;
      const val = avg < 128 ? 0 : 255; // Simple threshold
      data[i] = data[i + 1] = data[i + 2] = val;
    }
    ctx.putImageData(imageData, 0, 0);
    
    // Create ESC/POS commands
    const ESC = '\x1B';
    const GS = '\x1D';
    const LF = '\x0A';
    
    // Initialize printer
    let cmds = '';
    
    // Reset printer
    cmds += ESC + '@';
    
    // Set alignment to center
    cmds += ESC + 'a' + '\x01';
    
    // Set text size (double height and width)
    cmds += GS + '!' + '\x11';
    
    // Add header
    cmds += 'VALET PARKING' + LF + LF;
    
    // Reset text size
    cmds += GS + '!' + '\x00';
    
    // Add ticket info
    cmds += `Ticket: ${ticketNumber}${LF}`;
    cmds += `Date: ${formattedDate}${LF}`;
    cmds += `Customer: ${form.customer_name || 'N/A'}${LF}`;
    cmds += `Phone: ${form.customer_phone || 'N/A'}${LF}`;
    cmds += `Vehicle: ${form.vehicle_make || ''} ${form.vehicle_model || ''}${LF}`;
    cmds += `Plate: ${form.license_plate || 'N/A'}${LF}${LF}`;
    
    // Add QR code
    const qrWidth = 180; // 180 dots (1 inch = 8 dots at 203 DPI)
    const qrData = canvas.toDataURL('image/png').split(',')[1];
    const qrBinary = atob(qrData);
    const qrBytes = new Uint8Array(qrBinary.length);
    
    for (let i = 0; i < qrBinary.length; i++) {
      qrBytes[i] = qrBinary.charCodeAt(i);
    }
    
    // Print QR code using ESC/POS image command
    cmds += GS + '(L' + '\x06' + '\x00' + '\x30' + '\x00' + '\x01' + '\x01' + '\x01';
    cmds += GS + 'v0' + '\x00' + String.fromCharCode(qrWidth, 0) + qrBinary + LF + LF;
    
    // Add footer
    cmds += '--------------------------------' + LF;
    cmds += 'THANK YOU FOR USING OUR SERVICE' + LF;
    cmds += 'PLEASE KEEP THIS TICKET SAFE' + LF + LF;
    
    // Add cut command (partial cut with 5mm feed)
    cmds += GS + 'V' + '\x41' + '\x05';
    
    // Open print dialog with raw ESC/POS commands
    const printWindow = window.open('', '_blank');
    const printContent = getTicketPrintTemplate(ticketNumber, cmds);
    printWindow.document.write(printContent);
    printWindow.document.close();
  } catch (error) {
    console.error('Error printing QR code:', error);
  }
  
};

const submit = async () => {
  // If location is not ready, try to get it first
  if (!isLocationReady.value) {
    locationStatus.value = 'Getting your location...';
    try {
      await getLocation();
      if (!isLocationReady.value) {
        locationStatus.value = 'Location is required. Please enable location services and try again.';
        return;
      }
    } catch (error) {
      locationStatus.value = 'Error getting location. Please try again.';
      return;
    }
  }

  try {
    await form.post(route('employee.tickets.store'), {
      preserveScroll: true,
      onSuccess: (response) => {
        // Check if we have a ticket in the response
        if (response.props.ticket) {
          const ticket = response.props.ticket;
          // If we have a stored QR code path, use it
          if (ticket.qr_code_path) {
            storedQrCodeUrl.value = '/storage/' + ticket.qr_code_path;
            showQRModal.value = true;
            return; // Stay on the page to show the QR code
          } else {
            // Fallback to generating QR code on the fly with full ticket data
            generateQRCode(ticket);
            return; // Stay on the page to show the generated QR code
          }
        } 
        
        // Handle non-Inertia responses
        if (response.data) {
          try {
            const responseData = typeof response.data === 'string' 
              ? JSON.parse(response.data) 
              : response.data;
              
            if (responseData.ticket) {
              if (responseData.ticket.qr_code_path) {
                storedQrCodeUrl.value = '/storage/' + responseData.ticket.qr_code_path;
                showQRModal.value = true;
              } else {
                generateQRCode({
                  id: responseData.ticket.id,
                  ticket_number: responseData.ticket.ticket_number,
                  customer_name: responseData.ticket.customer_name,
                  vehicle_make: responseData.ticket.vehicle_make,
                  vehicle_model: responseData.ticket.vehicle_model,
                  license_plate: responseData.ticket.license_plate,
                  created_at: new Date().toISOString(),
                  qr_code_path: responseData.ticket.qr_code_path || ''
                });
              }
              return; // Stay on the page to show the QR code
            }
          } catch (e) {
            console.error('Error parsing response:', e);
            // Show error to user
            form.errors = { submit: ['Error processing the ticket.'] };
            return;
          }
        }
        
        // If we get here, something went wrong
        form.errors = { submit: ['Unexpected response from server.'] };
      },
      onError: (errors) => {
        console.log('Form errors:', errors);
        form.errors = errors;
      },
    });
  } catch (error) {
    console.error('Error submitting form:', error);
    form.errors = { submit: ['An error occurred while submitting the form.'] };
  }
};
</script>
