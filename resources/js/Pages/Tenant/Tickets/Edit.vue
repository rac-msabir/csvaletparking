<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
          <h2 class="text-lg font-medium text-gray-900">Edit Ticket #{{ ticket.ticket_number }}</h2>
 
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
          <form id="ticket-form" @submit.prevent="submit">
            <!-- Customer Information Section -->
            <div class="px-6 py-5 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Customer Info</h3>
              
            </div>
            
            <div class="px-6 py-5">
              <div class="grid grid-cols-6 gap-6">
                <!-- Phone -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Customer Phone Number</label>
                  <input
                    type="tel"
                    id="customer_phone"
                    v-model="form.customer_phone"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.customer_phone }"
                    required
                  />
                  <p v-if="form.errors.customer_phone" class="mt-1 text-sm text-red-600">
                    {{ form.errors.customer_phone }}
                  </p>
                </div>

              </div>
            </div>

            <div class="px-6 pb-5">
              <div class="grid grid-cols-6 gap-6">
                <!-- Make -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="vehicle_make" class="block text-sm font-medium text-gray-700 mb-1">Car Brand</label>
                  <input
                    type="text"
                    id="vehicle_make"
                    v-model="form.vehicle_make"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.vehicle_make }"
                    required
                  />
                  <p v-if="form.errors.vehicle_make" class="mt-1 text-sm text-red-600">
                    {{ form.errors.vehicle_make }}
                  </p>
                </div>

                <!-- Model -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="vehicle_model" class="block text-sm font-medium text-gray-700 mb-1">Car Model</label>
                  <input
                    type="text"
                    id="vehicle_model"
                    v-model="form.vehicle_model"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.vehicle_model }"
                    required
                  />
                  <p v-if="form.errors.vehicle_model" class="mt-1 text-sm text-red-600">
                    {{ form.errors.vehicle_model }}
                  </p>
                </div>

      
                <!-- License Plate -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">Car Plate</label>
                  <input
                    type="text"
                    id="license_plate"
                    v-model="form.license_plate"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.license_plate }"
                    required
                  />
                  <p v-if="form.errors.license_plate" class="mt-1 text-sm text-red-600">
                    {{ form.errors.license_plate }}
                  </p>
                </div>


              </div>
            </div>


          <div class="px-6 pb-5 bg-gray-50">
            <div class="grid grid-cols-6 gap-6">
              <!-- special_instructions -->
              <div class="col-span-6">
                <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea
                  id="special_instructions"
                  v-model="form.special_instructions"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{ 'border-red-500': form.errors.special_instructions }"
                ></textarea>
                <p v-if="form.errors.special_instructions" class="mt-1 text-sm text-red-600">
                  {{ form.errors.special_instructions }}
                </p>
              </div>
            </div>
          </div>
            
            
            <!-- In Edit.vue, add this after the form fields but before the form actions -->
            <div class="px-6 py-5 border-t border-gray-200">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Attached Images</h3>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div v-for="(image, index) in ticket.images" :key="image.id" class="relative group">
                  <img 
                  :src="image.url" 
                  :alt="'Vehicle image ' + (index + 1)"
                  class="h-32 w-full object-cover rounded-md"
                  >
                  <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center rounded-md transition-opacity">
                    <a 
                    :href="image.url" 
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
                  Saving...
                </span>
                <span v-else>Save Changes</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  ticket: {
    type: Object,
    required: true,
  },
});

const form = useForm({
  customer_phone: props.ticket.customer_phone,
  vehicle_make: props.ticket.vehicle_make,
  vehicle_model: props.ticket.vehicle_model,
  vehicle_color: props.ticket.vehicle_color,
  license_plate: props.ticket.license_plate,
  special_instructions: props.ticket.special_instructions,
});

const submit = () => {

  console.log(form.data());
  form.put(route('tenant.tickets.update', props.ticket.id));
};
</script>
