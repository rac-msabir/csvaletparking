<template>
  <SuperAdminLayout title="Tenant Management">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-xl font-semibold text-gray-900">Tenants</h1>
          <p class="mt-2 text-sm text-gray-700">A list of all tenant organizations in the system.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button
            type="button"
            @click="$inertia.visit(route('super-admin.tenants.create'))"
            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
          >
            Add tenant
          </button>
        </div>
      </div>
      
      <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Domain</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                      <span class="sr-only">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  <tr v-for="tenant in tenants.data" :key="tenant.id">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                      {{ tenant.name }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      {{ tenant.domain }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      {{ tenant.email }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                      <span 
                        :class="[
                          'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                          tenant.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        ]"
                      >
                        {{ tenant.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                      <div class="flex items-center justify-end space-x-2">
                        <button
                          @click="toggleStatus(tenant)"
                          :class="[
                            'inline-flex items-center px-3 py-1.5 border rounded-md text-sm font-medium',
                            tenant.is_active 
                              ? 'border-red-300 text-red-700 bg-white hover:bg-red-50' 
                              : 'border-green-300 text-green-700 bg-white hover:bg-green-50'
                          ]"
                        >
                          {{ tenant.is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                        <a 
                          :href="route('super-admin.tenants.edit', tenant.id)" 
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          Edit
                        </a>
                        <button
                          @click="confirmDelete(tenant)"
                          class="text-red-600 hover:text-red-900"
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              
              <!-- Pagination -->
              <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                  <a 
                    v-if="tenants.prev_page_url"
                    :href="tenants.prev_page_url" 
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  >
                    Previous
                  </a>
                  <a 
                    v-if="tenants.next_page_url"
                    :href="tenants.next_page_url" 
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  >
                    Next
                  </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700">
                      Showing
                      <span class="font-medium">{{ tenants.from }}</span>
                      to
                      <span class="font-medium">{{ tenants.to }}</span>
                      of
                      <span class="font-medium">{{ tenants.total }}</span>
                      results
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <a 
                        v-for="(link, index) in tenants.links" 
                        :key="index"
                        :href="link.url"
                        :class="[
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                          link.active 
                            ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' 
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                          index === 0 ? 'rounded-l-md' : '',
                          index === tenants.links.length - 1 ? 'rounded-r-md' : ''
                        ]"
                        v-html="link.label"
                      ></a>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <ConfirmationModal :show="confirmingDeletion" @close="closeModal">
      <template #title>
        Delete Tenant
      </template>
      
      <template #content>
        Are you sure you want to delete this tenant? This action cannot be undone.
      </template>
      
      <template #footer>
        <button 
          type="button" 
          class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          @click="closeModal"
        >
          Cancel
        </button>
        <button 
          type="button" 
          class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
          @click="deleteTenant"
          :disabled="form.processing"
        >
          <span v-if="form.processing">Deleting...</span>
          <span v-else>Delete</span>
        </button>
      </template>
    </ConfirmationModal>
  </SuperAdminLayout>
</template>

<script>
import SuperAdminLayout from '@/Layouts/SuperAdminLayout.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { useForm } from '@inertiajs/vue3';

export default {
  components: {
    SuperAdminLayout,
    ConfirmationModal,
  },
  
  props: {
    tenants: Object,
  },
  
  data() {
    return {
      confirmingDeletion: false,
      tenantToDelete: null,
      form: useForm({
        _method: 'DELETE',
      }),
    };
  },
  
  methods: {
    confirmDelete(tenant) {
      this.tenantToDelete = tenant;
      this.confirmingDeletion = true;
    },
    
    closeModal() {
      this.confirmingDeletion = false;
      this.tenantToDelete = null;
    },
    
    deleteTenant() {
      if (!this.tenantToDelete) return;
      
      this.form.delete(route('super-admin.tenants.destroy', this.tenantToDelete.id), {
        preserveScroll: true,
        onSuccess: () => {
          this.closeModal();
          // Show success message
        },
      });
    },
    
    toggleStatus(tenant) {
      this.$inertia.post(route('super-admin.tenants.toggle-status', tenant.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
          // Show success message
        },
      });
    },
  },
};
</script>
