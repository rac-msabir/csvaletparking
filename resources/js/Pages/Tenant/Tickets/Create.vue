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
                            <input type="hidden" v-model="form.assigned_to" :value="$page.props.auth.user.id" />
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
                    :href="route('tenant.tickets.index')" 
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
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
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
  assigned_to: null,
});

const submit = () => {
  form.post(route('tenant.tickets.store'), {
    preserveScroll: true,
    onSuccess: () => {
      // Form was successful
    },
  });
};
</script>
