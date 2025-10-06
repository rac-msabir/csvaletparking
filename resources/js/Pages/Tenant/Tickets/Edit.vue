<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Edit Ticket #{{ ticket.ticket_number }}
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="submit">
              <!-- Customer Information -->
              <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="customer_name" v-model="form.customer_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div v-if="form.errors.customer_name" class="text-red-500 text-xs mt-1">{{ form.errors.customer_name }}</div>
                  </div>
                  <div>
                    <label for="customer_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" id="customer_phone" v-model="form.customer_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div v-if="form.errors.customer_phone" class="text-red-500 text-xs mt-1">{{ form.errors.customer_phone }}</div>
                  </div>
                  <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700">Email (Optional)</label>
                    <input type="email" id="customer_email" v-model="form.customer_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div v-if="form.errors.customer_email" class="text-red-500 text-xs mt-1">{{ form.errors.customer_email }}</div>
                  </div>
                </div>
              </div>

              <!-- Vehicle Information -->
              <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Vehicle Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                  <div>
                    <label for="vehicle_make" class="block text-sm font-medium text-gray-700">Make</label>
                    <input type="text" id="vehicle_make" v-model="form.vehicle_make" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div v-if="form.errors.vehicle_make" class="text-red-500 text-xs mt-1">{{ form.errors.vehicle_make }}</div>
                  </div>
                  <div>
                    <label for="vehicle_model" class="block text-sm font-medium text-gray-700">Model</label>
                    <input type="text" id="vehicle_model" v-model="form.vehicle_model" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div v-if="form.errors.vehicle_model" class="text-red-500 text-xs mt-1">{{ form.errors.vehicle_model }}</div>
                  </div>
                  <div>
                    <label for="vehicle_color" class="block text-sm font-medium text-gray-700">Color</label>
                    <input type="text" id="vehicle_color" v-model="form.vehicle_color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div v-if="form.errors.vehicle_color" class="text-red-500 text-xs mt-1">{{ form.errors.vehicle_color }}</div>
                  </div>
                  <div>
                    <label for="license_plate" class="block text-sm font-medium text-gray-700">License Plate</label>
                    <input type="text" id="license_plate" v-model="form.license_plate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div v-if="form.errors.license_plate" class="text-red-500 text-xs mt-1">{{ form.errors.license_plate }}</div>
                  </div>
                </div>
              </div>

              <!-- Parking Information -->
              <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Parking Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label for="parking_zone" class="block text-sm font-medium text-gray-700">Zone</label>
                    <input type="text" id="parking_zone" v-model="form.parking_zone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div v-if="form.errors.parking_zone" class="text-red-500 text-xs mt-1">{{ form.errors.parking_zone }}</div>
                  </div>
                  <div>
                    <label for="parking_spot" class="block text-sm font-medium text-gray-700">Spot</label>
                    <input type="text" id="parking_spot" v-model="form.parking_spot" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <div v-if="form.errors.parking_spot" class="text-red-500 text-xs mt-1">{{ form.errors.parking_spot }}</div>
                  </div>
                  <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                      <option value="pending">Pending</option>
                      <option value="in_progress">In Progress</option>
                      <option value="ready">Ready</option>
                      <option value="delivered">Delivered</option>
                      <option value="cancelled">Cancelled</option>
                    </select>
                    <div v-if="form.errors.status" class="text-red-500 text-xs mt-1">{{ form.errors.status }}</div>
                  </div>
                </div>
              </div>

              <!-- Special Instructions -->
              <div class="mb-8">
                <label for="special_instructions" class="block text-sm font-medium text-gray-700">Special Instructions</label>
                <textarea id="special_instructions" v-model="form.special_instructions" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                <div v-if="form.errors.special_instructions" class="text-red-500 text-xs mt-1">{{ form.errors.special_instructions }}</div>
              </div>

              <!-- Damage Notes -->
              <div class="mb-8">
                <label for="damage_notes" class="block text-sm font-medium text-gray-700">Damage Notes</label>
                <textarea id="damage_notes" v-model="form.damage_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                <div v-if="form.errors.damage_notes" class="text-red-500 text-xs mt-1">{{ form.errors.damage_notes }}</div>
              </div>

              <!-- Assigned To -->
              <div class="mb-8">
                <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assigned To</label>
                <div class="mt-1">
                  <input 
                    type="text" 
                    :value="$page.props.auth.user.name" 
                    disabled 
                    class="bg-gray-100 border border-gray-300 text-gray-700 rounded-md shadow-sm w-full md:w-1/3 p-2"
                  >
                  <input type="hidden" v-model="form.assigned_to" :value="$page.props.auth.user.id" />
                </div>
                <p class="mt-1 text-sm text-gray-500">Tickets are automatically assigned to you.</p>
              </div>

              <!-- Form Actions -->
              <div class="flex items-center justify-end space-x-4">
                <Link :href="route('tenant.tickets.show', ticket.id)" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                  Cancel
                </Link>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition" :disabled="form.processing">
                  <span v-if="form.processing">Saving...</span>
                  <span v-else>Save Changes</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

export default defineComponent({
  components: {
    AppLayout,
    Link,
  },

  props: {
    ticket: {
      type: Object,
      required: true,
    },
    employees: {
      type: Array,
      default: () => [],
    },
  },

  setup(props) {
    const form = useForm({
      customer_name: props.ticket.customer_name,
      customer_phone: props.ticket.customer_phone,
      customer_email: props.ticket.customer_email || '',
      vehicle_make: props.ticket.vehicle_make,
      vehicle_model: props.ticket.vehicle_model,
      vehicle_color: props.ticket.vehicle_color,
      license_plate: props.ticket.license_plate,
      parking_zone: props.ticket.parking_zone || '',
      parking_spot: props.ticket.parking_spot || '',
      status: props.ticket.status,
      special_instructions: props.ticket.special_instructions || '',
      damage_notes: props.ticket.damage_notes || '',
      assigned_to: props.ticket.assigned_to || null,
    });

    const submit = () => {
      form.put(route('tenant.tickets.update', props.ticket.id));
    };

    return { form, submit };
  },
});
</script>
