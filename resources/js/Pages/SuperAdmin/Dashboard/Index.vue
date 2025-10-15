<template>
  <SuperAdminLayout title="Dashboard">
    <div class="bg-white shadow">
      <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900">Tenant Organizations</h3>
          <p class="mt-1 text-sm text-gray-500">A list of all tenant organizations in the system.</p>
        </div>
        <Link 
          :href="route('super-admin.tenants.create')"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Add New Tenant
        </Link>
      </div>
      
      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
          <li v-for="tenant in tenants.data" :key="tenant.id">
            <div class="px-4 py-4 sm:px-6">
              <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-indigo-600 truncate">
                    {{ tenant.name }}
                  </p>
                  <div class="mt-2 flex">
                    <p class="flex items-center text-sm text-gray-500">
                      <span class="truncate">{{ tenant.domain }}</span>
                    </p>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <span 
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="tenant.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ tenant.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <span class="text-sm text-gray-500">
                    {{ formatDate(tenant.created_at) }}
                  </span>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <Link 
                    :href="route('super-admin.tenants.show', tenant.id)"
                    class="font-medium text-indigo-600 hover:text-indigo-900 mr-4"
                  >
                    View
                  </Link>
                  <Link 
                    :href="route('super-admin.tenants.edit', { tenant: tenant.id })"
                    class="font-medium text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </Link>
                </div>
              </div>
            </div>
          </li>
        </ul>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button 
              @click="previousPage"
              :disabled="!tenants.prev_page_url"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              :class="{ 'opacity-50 cursor-not-allowed': !tenants.prev_page_url }"
            >
              Previous
            </button>
            <button 
              @click="nextPage"
              :disabled="!tenants.next_page_url"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              :class="{ 'opacity-50 cursor-not-allowed': !tenants.next_page_url }"
            >
              Next
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ tenants.from }}</span> to 
                <span class="font-medium">{{ tenants.to }}</span> of 
                <span class="font-medium">{{ tenants.total }}</span> results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="previousPage"
                  :disabled="!tenants.prev_page_url"
                  :class="{ 'opacity-50 cursor-not-allowed': !tenants.prev_page_url }"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                >
                  <span class="sr-only">Previous</span>
                  <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                </button>
                <button
                  v-for="(link, index) in tenants.links"
                  :key="index"
                  @click="goToPage(link.url)"
                  :disabled="!link.url || link.active"
                  :class="[
                    link.active 
                      ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' 
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                  ]"
                  v-html="link.label"
                ></button>
                <button
                  @click="nextPage"
                  :disabled="!tenants.next_page_url"
                  :class="{ 'opacity-50 cursor-not-allowed': !tenants.next_page_url }"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                >
                  <span class="sr-only">Next</span>
                  <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </SuperAdminLayout>
</template>

<script>
import { defineComponent } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SuperAdminLayout from '@/Layouts/SuperAdminLayout.vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';

export default defineComponent({
  components: {
    Head,
    Link,
    SuperAdminLayout,
    ChevronLeftIcon,
    ChevronRightIcon,
  },
  
  props: {
    tenants: Object,
  },
  setup() {
    const formatDate = (dateString) => {
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    };

    const viewTenant = (id) => {
      window.location.href = route('super-admin.tenants.show', id);
    };

    const editTenant = (id) => {
      window.location.href = `/super-admin/tenants/${id}/edit`;
    };

    const goToPage = (url) => {
      if (!url) return;
      const page = url.split('page=')[1];
      router.visit(`${window.location.pathname}?page=${page}`);
    };

    const previousPage = () => {
      if (this.tenants.prev_page_url) {
        router.visit(this.tenants.prev_page_url);
        window.location.href = this.tenants.prev_page_url;
      }
    };

    const nextPage = () => {
      if (this.tenants.next_page_url) {
        window.location.href = this.tenants.next_page_url;
      }
    };

    return {
      formatDate,
      viewTenant,
      editTenant,
      goToPage,
      previousPage,
      nextPage,
    };
  },
});
</script>

