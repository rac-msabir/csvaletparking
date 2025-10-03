<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Super Admin Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Stats Cards -->
              <div class="bg-indigo-50 p-6 rounded-lg shadow">
                <div class="flex items-center">
                  <div class="p-3 rounded-full bg-indigo-100">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Total Tenants</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.tenants_count || 0 }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-green-50 p-6 rounded-lg shadow">
                <div class="flex items-center">
                  <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Active Tickets</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.active_tickets || 0 }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-purple-50 p-6 rounded-lg shadow">
                <div class="flex items-center">
                  <div class="p-3 rounded-full bg-purple-100">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Total Users</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.users_count || 0 }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Recent Activity -->
            <div class="mt-12">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
              <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                  <li v-for="(activity, index) in recentActivities" :key="index">
                    <div class="px-4 py-4 flex items-center sm:px-6">
                      <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                        <div class="truncate">
                          <div class="flex text-sm">
                            <p class="font-medium text-indigo-600 truncate">{{ activity.description }}</p>
                            <p class="ml-1 flex-shrink-0 font-normal text-gray-500">{{ activity.details }}</p>
                          </div>
                          <div class="mt-2 flex">
                            <div class="flex items-center text-sm text-gray-500">
                              <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                              </svg>
                              <p>{{ activity.time }}</p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="ml-5 flex-shrink-0">
                        <span :class="activity.statusClass" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                          {{ activity.status }}
                        </span>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
  components: {
    AppLayout,
    Head,
    Link,
  },
  
  props: {
    stats: {
      type: Object,
      default: () => ({
        tenants_count: 0,
        active_tickets: 0,
        users_count: 0,
      }),
    },
  },

  data() {
    return {
      recentActivities: [
        {
          description: 'New tenant registered',
          details: 'Acme Corp',
          time: '2 hours ago',
          status: 'Completed',
          statusClass: 'bg-green-100 text-green-800',
        },
        {
          description: 'System update',
          details: 'v2.0.1 deployed',
          time: '5 hours ago',
          status: 'In Progress',
          statusClass: 'bg-blue-100 text-blue-800',
        },
        {
          description: 'New user signup',
          details: 'john.doe@example.com',
          time: '1 day ago',
          status: 'Completed',
          statusClass: 'bg-green-100 text-green-800',
        },
      ],
    };
  },
};
</script>
