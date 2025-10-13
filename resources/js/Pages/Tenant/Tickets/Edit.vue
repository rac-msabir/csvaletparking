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
              <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
              <p class="mt-1 text-sm text-gray-500">Customer details for this ticket.</p>
            </div>
            
            <div class="px-6 py-5">
              <div class="grid grid-cols-6 gap-6">
                <!-- Name -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                  <input
                    type="text"
                    id="customer_name"
                    v-model="form.customer_name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.customer_name }"
                    required
                  />
                  <p v-if="form.errors.customer_name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.customer_name }}
                  </p>
                </div>

                <!-- Phone -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
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

                <!-- Email -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input
                    type="email"
                    id="customer_email"
                    v-model="form.customer_email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.customer_email }"
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
                  <label for="vehicle_make" class="block text-sm font-medium text-gray-700 mb-1">Make *</label>
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
                  <label for="vehicle_model" class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
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

                <!-- Color -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="vehicle_color" class="block text-sm font-medium text-gray-700 mb-1">Color *</label>
                  <input
                    type="text"
                    id="vehicle_color"
                    v-model="form.vehicle_color"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.vehicle_color }"
                    required
                  />
                  <p v-if="form.errors.vehicle_color" class="mt-1 text-sm text-red-600">
                    {{ form.errors.vehicle_color }}
                  </p>
                </div>

                <!-- License Plate -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">License Plate *</label>
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

                <!-- Parking Spot -->
                <div class="col-span-6 sm:col-span-2">
                  <label for="parking_spot" class="block text-sm font-medium text-gray-700 mb-1">Parking Spot</label>
                  <input
                    type="text"
                    id="parking_spot"
                    v-model="form.parking_spot"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': form.errors.parking_spot }"
                  />
                  <p v-if="form.errors.parking_spot" class="mt-1 text-sm text-red-600">
                    {{ form.errors.parking_spot }}
                  </p>
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
                <!-- special_instructions -->
                <div class="col-span-6">
                  <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
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
  customer_name: props.ticket.customer_name,
  customer_phone: props.ticket.customer_phone,
  customer_email: props.ticket.customer_email,
  vehicle_make: props.ticket.vehicle_make,
  vehicle_model: props.ticket.vehicle_model,
  vehicle_color: props.ticket.vehicle_color,
  license_plate: props.ticket.license_plate,
  parking_spot: props.ticket.parking_spot,
  special_instructions: props.ticket.special_instructions,
  damage_notes: props.ticket.damage_notes,

});

const submit = () => {

  console.log(form.data());
  form.put(route('tenant.tickets.update', props.ticket.id));
};
</script>
