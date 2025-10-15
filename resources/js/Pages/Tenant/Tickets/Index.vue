<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Ticket Management
        </h2>
        <Link 
          :href="route('tenant.tickets.create')" 
          class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
          New Ticket
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="ticket in tickets.data" :key="ticket.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ ticket.ticket_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ ticket.customer_name }}</div>
                      <div class="text-sm text-gray-500">{{ ticket.customer_phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ ticket.vehicle_make }} {{ ticket.vehicle_model }}</div>
                      <div class="text-sm text-gray-500">{{ ticket.license_plate || 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span 
                        :class="{
                          'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                          'bg-yellow-100 text-yellow-800': ticket.status === 'pending',
                          'bg-blue-100 text-blue-800': ticket.status === 'in_progress',
                          'bg-green-100 text-green-800': ticket.status === 'ready',
                          'bg-purple-100 text-purple-800': ticket.status === 'delivered',
                          'bg-red-100 text-red-800': ticket.status === 'cancelled',
                        }"
                      >
                        {{ ticket.status.replace('_', ' ') }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ ticket.assigned_to ? ticket.assigned_to.name : 'Unassigned' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDateTime(ticket.check_in_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <Link :href="route('tenant.tickets.edit', ticket.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                      <button 
                        @click="confirmDelete(ticket)" 
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                  <tr v-if="tickets.data.length === 0">
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                      No tickets found.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ConfirmationModal :show="confirmingDeletion" @close="closeModal">
      <template #title>
        Delete Ticket
      </template>
      <template #content>
        Are you sure you want to delete this ticket? This action cannot be undone.
      </template>
      <template #footer>
        <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
        <DangerButton class="ml-2" :class="{ 'opacity-25': processing }" :disabled="processing" @click="deleteTicket">Delete</DangerButton>
      </template>
    </ConfirmationModal>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
  tickets: Object,
});

const confirmingDeletion = ref(false);
const processing = ref(false);
const ticketToDelete = ref(null);

const confirmDelete = (ticket) => {
  ticketToDelete.value = ticket;
  confirmingDeletion.value = true;
};

const closeModal = () => {
  confirmingDeletion.value = false;
  ticketToDelete.value = null;
};

const deleteTicket = () => {
  if (!ticketToDelete.value) return;
  processing.value = true;
  useForm().delete(route('tenant.tickets.destroy', ticketToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onFinish: () => (processing.value = false),
  });
};

const formatDateTime = (dateTime) => {
  if (!dateTime) return 'N/A';
  const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
  return new Date(dateTime).toLocaleString('en-US', options);
};
</script>
