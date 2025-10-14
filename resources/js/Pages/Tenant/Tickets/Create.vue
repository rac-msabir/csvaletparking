<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
          <h2 class="text-lg font-medium text-gray-900">Create New Ticket</h2>
         
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
          <form id="ticket-form" @submit.prevent="submit" enctype="multipart/form-data">
            <!-- Customer Information Section -->
            <div class="px-6 py-5 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
              <p class="mt-1 text-sm text-gray-500">Customer details for this ticket.</p>
            </div>
            
            <div class="px-6 py-5">
              <div class="grid grid-cols-6 gap-6">
                <!-- Name -->
                <div class="col-span-6 sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                  <input
                    type="text"
                    v-model="form.customer_name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.customer_name }"
                    placeholder="John Doe"
                    required
                  />
                  <p v-if="form.errors.customer_name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.customer_name }}
                  </p>
                </div>

                <!-- Phone -->
                <div class="col-span-6 sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                  <input
                    type="tel"
                    v-model="form.customer_phone"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.customer_phone }"
                    placeholder="+1 (555) 123-4567"
                    required
                  />
                  <p v-if="form.errors.customer_phone" class="mt-1 text-sm text-red-600">
                    {{ form.errors.customer_phone }}
                  </p>
                </div>

                <!-- Email -->
                <div class="col-span-6 sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input
                    type="email"
                    v-model="form.customer_email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.customer_email }"
                    placeholder="john@example.com"
                  />
                  <p v-if="form.errors.customer_email" class="mt-1 text-sm text-red-600">
                    {{ form.errors.customer_email }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Vehicle Information Section -->
            <div class="px-6 py-5 border-t border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Vehicle Information</h3>
              <p class="mt-1 text-sm text-gray-500">Details about the customer's vehicle.</p>
            </div>
            
            <div class="px-6 pb-5">
              <div class="grid grid-cols-6 gap-6">
                <!-- Make -->
                <div class="col-span-6 sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Make *</label>
                  <input
                    type="text"
                    v-model="form.vehicle_make"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.vehicle_make }"
                    placeholder="Toyota"
                    required
                  />
                  <p v-if="form.errors.vehicle_make" class="mt-1 text-sm text-red-600">
                    {{ form.errors.vehicle_make }}
                  </p>
                </div>

                <!-- Model -->
                <div class="col-span-6 sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                  <input
                    type="text"
                    v-model="form.vehicle_model"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.vehicle_model }"
                    placeholder="Camry"
                    required
                  />
                  <p v-if="form.errors.vehicle_model" class="mt-1 text-sm text-red-600">
                    {{ form.errors.vehicle_model }}
                  </p>
                </div>

                <!-- Color -->
                <div class="col-span-6 sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Color *</label>
                  <input
                    type="text"
                    v-model="form.vehicle_color"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.vehicle_color }"
                    placeholder="Red"
                    required
                  />
                  <p v-if="form.errors.vehicle_color" class="mt-1 text-sm text-red-600">
                    {{ form.errors.vehicle_color }}
                  </p>
                </div>

                <!-- License Plate -->
                <div class="col-span-6 sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">License Plate *</label>
                  <input
                    type="text"
                    v-model="form.license_plate"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.license_plate }"
                    placeholder="ABC123"
                    required
                  />
                  <p v-if="form.errors.license_plate" class="mt-1 text-sm text-red-600">
                    {{ form.errors.license_plate }}
                  </p>
                </div>

                <!-- Parking Spot -->
                <div class="col-span-6 sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Parking Spot</label>
                  <input
                    type="text"
                    v-model="form.parking_spot"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="A12"
                  />
                  <p v-if="form.errors.parking_spot" class="mt-1 text-sm text-red-600">
                    {{ form.errors.parking_spot }}
                  </p>
                </div>
              </div>
              <!-- Vehicle Images Section -->
              <div class="col-span-6 sm:col-span-2 mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Images</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                  <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                      <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                      <label for="vehicle_images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <span>Upload files</span>
                        <input id="vehicle_images" name="vehicle_images" type="file" class="sr-only" multiple @change="handleFileUpload" accept="image/*">
                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                    <div v-if="uploadedFiles.length > 0" class="mt-2">
                      <div v-for="(file, index) in uploadedFiles" :key="index" class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                        <span class="text-sm text-gray-700 truncate max-w-xs">{{ file.name }}</span>
                        <button type="button" @click="removeFile(index)" class="text-red-600 hover:text-red-800">
                          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Additional Information Section -->
            <div class="px-6 py-5 border-t border-gray-200 bg-gray-50">
              <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
              <p class="mt-1 text-sm text-gray-500">Any special instructions or notes.</p>
            </div>
            
            <div class="px-6 pb-5 bg-gray-50">
              <div class="grid grid-cols-6 gap-6">
                <!-- Notes -->
                <div class="col-span-6">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                  <textarea
                    v-model="form.notes"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="Any special instructions or notes about the vehicle..."
                  ></textarea>
                  <p v-if="form.errors.notes" class="mt-1 text-sm text-red-600">
                    {{ form.errors.notes }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-3 bg-gray-50 text-right border-t border-gray-200">
              <Link 
                :href="route('tenant.dashboard')" 
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Cancel
              </Link>
              <button 
                type="submit" 
                class="ml-3 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                <span v-if="form.processing">
                  Processing...
                </span>
                <span v-else>Save & Create Ticket</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- QR Code Modal -->
    <TransitionRoot as="template" :show="showQRModal">
      <Dialog as="div" class="fixed z-10 inset-0 overflow-y-auto" @close="closeQRModal">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
            <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
          </TransitionChild>

          <!-- This element is to trick the browser into centering the modal contents. -->
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
          
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
              <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                  <CheckIcon class="h-6 w-6 text-green-600" aria-hidden="true" />
                </div>
                <div class="mt-3 text-center sm:mt-5">
                  <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900">
                    Ticket Created Successfully!
                  </DialogTitle>
                  <div class="mt-4">
                    <p class="text-sm text-gray-500">
                      Ticket #{{ ticketNumber }}
                    </p>
                    <div class="mt-4 flex justify-center" ref="qrCodeElement">
                      <canvas ref="qrCanvas" class="border border-gray-200 p-2 rounded"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-5 sm:mt-6 grid grid-cols-2 gap-3">
                <button
                  type="button"
                  class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
                  @click="printQRCode"
                >
                  Print Ticket
                </button>
                <button
                  type="button"
                  class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
                  @click="downloadQRCode"
                >
                  Download QR
                </button>
                <button
                  type="button"
                  class="col-span-2 inline-flex justify-center w-full rounded-md border border-green-600 shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm"
                  @click="printViaThermal"
                  :disabled="isPrinting"
                >
                  <svg v-if="isPrinting" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                  </svg>
                  {{ isPrinting ? 'Printing...' : 'Print via Thermal Printer' }}
                </button>
                <Link 
                  :href="route('tenant.dashboard')"
                  class="col-span-2 mt-3 inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:text-sm text-center"
                >
                  Close & Go to Dashboard
                </Link>
              </div>
            </div>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>
  </AppLayout>
</template>


<!-- Update the script section (around line 270) -->
<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted, nextTick } from 'vue';
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import QRCode from 'qrcode';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  errors: Object,
});

const form = useForm({
  customer_name: '',
  customer_phone: '',
  customer_email: '',
  vehicle_make: '',
  vehicle_model: '',
  vehicle_color: '',
  license_plate: '',
  parking_spot: '',
  notes: '',
  check_in_latitude: null,
  check_in_longitude: null,
  special_instructions: '',
  damage_notes: '',
  images: [],
});

// Refs for UI state
const uploadedFiles = ref([]);
const locationStatus = ref('Getting your location...');
const isLocationReady = ref(false);
const showQRModal = ref(false);
const qrCanvas = ref(null);
const qrCodeElement = ref(null);
const qrCodeUrl = ref('');
const ticketNumber = ref('');
const isPrinting = ref(false);

// Reset QR code data when modal is closed
const closeQRModal = () => {
  showQRModal.value = false;
  qrCodeUrl.value = '';
};

// Print via thermal printer
const printViaThermal = async () => {
    if (!ticketNumber.value) {
        toast.error('No ticket number available for printing');
        return;
    }
    
    isPrinting.value = true;
    
    try {
        const url = route('print.qr', ticketNumber.value);
        console.log('Sending print request to:', url);
        
        const response = await axios.get(url, {
            timeout: 10000, // 10 second timeout
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.data.status === 'success') {
            toast.success('Ticket sent to thermal printer successfully!');
        } else {
            throw new Error(response.data.message || 'Failed to print ticket');
        }
    } catch (error) {
        console.error('Print error details:', {
            message: error.message,
            code: error.code,
            status: error.response?.status,
            statusText: error.response?.statusText,
            responseData: error.response?.data
        });
        
        if (error.code === 'ECONNABORTED') {
            toast.error('Print request timed out. Please check the printer connection.');
        } else if (error.response?.status === 502) {
            toast.error('Unable to connect to the print service. Please try again later.');
        } else {
            toast.error(`Print failed: ${error.response?.data?.message || error.message}`);
        }
    } finally {
        isPrinting.value = false;
    }
};

// File handling
const handleFileUpload = (event) => {
  const files = Array.from(event.target.files);
  // Filter for image files and limit to 5 images max
  const imageFiles = files
    .filter(file => file.type.startsWith('image/'))
    .slice(0, 5 - uploadedFiles.value.length);
  
  // Add new files to the uploadedFiles array
  uploadedFiles.value = [...uploadedFiles.value, ...imageFiles];
  // Update the form data
  form.images = uploadedFiles.value;
};

const removeFile = (index) => {
  uploadedFiles.value.splice(index, 1);
  form.images = [...uploadedFiles.value];
};

// Location handling
const getLocation = async () => {
  if (!navigator.geolocation) {
    locationStatus.value = 'Geolocation is not supported by your browser';
    return;
  }

  try {
    locationStatus.value = 'Getting your location...';
    const position = await new Promise((resolve, reject) => {
      navigator.geolocation.getCurrentPosition(resolve, reject, {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
      });
    });

    form.check_in_latitude = position.coords.latitude;
    form.check_in_longitude = position.coords.longitude;
    
    isLocationReady.value = true;
    locationStatus.value = 'Location captured successfully';
  } catch (error) {
    console.error('Error getting location:', error);
    switch(error.code) {
      case error.PERMISSION_DENIED:
        locationStatus.value = 'Location access was denied. Please enable location services and refresh the page.';
        break;
      case error.POSITION_UNAVAILABLE:
        locationStatus.value = 'Location information is unavailable.';
        break;
      case error.TIMEOUT:
        locationStatus.value = 'The request to get user location timed out.';
        break;
      default:
        locationStatus.value = 'An unknown error occurred while getting location.';
    }
    isLocationReady.value = false;
  }
};

// Form submission
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
    if (uploadedFiles.value.length > 0) {
      // Create FormData for file uploads
      const formData = new FormData();
      
      // Append all form fields
      Object.keys(form).forEach(key => {
        if (key !== 'images' && form[key] !== null) {
          formData.append(key, form[key]);
        }
      });
      
      // Append each image file
      uploadedFiles.value.forEach((file, index) => {
        formData.append(`images[${index}]`, file);
      });

      // Submit with FormData
      await form.post(route('tenant.tickets.store'), {
        data: formData,
        forceFormData: true,
        preserveScroll: true,
        onSuccess: (response) => {
          if (response.props.ticket) {
            generateQRCode(response.props.ticket);
          }
        },
        onError: (errors) => {
          console.log('Form errors:', errors);
          form.errors = errors;
        },
      });
    } else {
      // Submit without files
      await form.post(route('tenant.tickets.store'), {
        preserveScroll: true,
        onSuccess: (response) => {
          if (response.props.ticket) {
            generateQRCode(response.props.ticket);
          }
        },
        onError: (errors) => {
          console.log('Form errors:', errors);
          form.errors = errors;
        },
      });
    }
  } catch (error) {
    console.error('Error submitting form:', error);
    form.errors = { submit: ['An error occurred while submitting the form.'] };
  }
};

// QR Code functions
const generateQRCode = async (ticket) => {
  try {
    ticketNumber.value = ticket.ticket_number;
    const qrData = JSON.stringify({
      ticket_id: ticket.id,
      ticket_number: ticket.ticket_number,
      customer_name: ticket.customer_name,
      license_plate: ticket.license_plate,
    });

    // Generate QR code as data URL
    qrCodeUrl.value = await QRCode.toDataURL(qrData, {
      errorCorrectionLevel: 'H',
      width: 200,
      margin: 1
    });
    
    showQRModal.value = true;
    
    // Wait for the next tick to ensure the canvas is rendered
    await nextTick();
    
    // Generate QR code for the canvas
    if (qrCanvas.value) {
      await QRCode.toCanvas(qrCanvas.value, qrData, {
        errorCorrectionLevel: 'H',
        width: 200,
        margin: 1
      });
    }
  } catch (error) {
    console.error('Error generating QR code:', error);
  }
};

const printQRCode = () => {
  const printWindow = window.open('', '_blank');
  const printContent = `
    <!DOCTYPE html>
    <html>
    <head>
      <title>Print Ticket #${ticketNumber.value}</title>
      <style>
        @media print {
          @page { margin: 0; }
          body { margin: 1.6cm; }
        }
        .ticket { 
          max-width: 300px; 
          margin: 0 auto; 
          text-align: center; 
          padding: 20px; 
          border: 1px solid #000; 
          border-radius: 5px;
        }
        .ticket h2 { margin: 0 0 10px 0; }
        .ticket p { margin: 5px 0; }
      </style>
    </head>
    <body>
      <div class="ticket">
        <h2>Valet Parking Ticket</h2>
        <p>Ticket #${ticketNumber.value}</p>
        <img src="${qrCodeUrl.value}" alt="QR Code" style="width: 200px; height: 200px;">
        <p>${new Date().toLocaleString()}</p>
        <p>Please keep this ticket safe</p>
      </div>
      <script>
        window.onload = function() {
          window.print();
          window.onafterprint = function() {
            window.close();
          }
        };
      <\/script>
    </body>
    </html>
  `;
  
  printWindow.document.open();
  printWindow.document.write(printContent);
  printWindow.document.close();
};

const downloadQRCode = () => {
  const link = document.createElement('a');
  link.download = `ticket-${ticketNumber.value}.png`;
  link.href = qrCodeUrl.value;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

// Get location when component mounts
onMounted(() => {
  getLocation();
});
</script>
