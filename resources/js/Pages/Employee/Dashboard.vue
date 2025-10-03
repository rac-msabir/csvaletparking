<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Employee Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Stats Cards -->
              <div class="bg-blue-50 p-6 rounded-lg shadow">
                <div class="flex items-center">
                  <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Assigned Tickets</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.assigned_tickets || 0 }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-green-50 p-6 rounded-lg shadow">
                <div class="flex items-center">
                  <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Completed Today</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.completed_today || 0 }}</p>
                  </div>
                </div>
              </div>

              <div class="bg-purple-50 p-6 rounded-lg shadow">
                <div class="flex items-center">
                  <div class="p-3 rounded-full bg-purple-100">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Avg. Resolution Time</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.avg_resolution_time || '0m' }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tasks -->
            <div class="mt-12">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Your Tasks</h3>
              <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                  <li v-for="task in tasks" :key="task.id">
                    <div class="px-4 py-4 flex items-center sm:px-6">
                      <div class="min-w-0 flex-1 flex items-center">
                        <div class="flex-shrink-0">
                          <input type="checkbox" :checked="task.completed" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        </div>
                        <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                          <div>
                            <p class="text-sm font-medium text-indigo-600 truncate">{{ task.title }}</p>
                            <p class="mt-2 flex items-center text-sm text-gray-500">
                              <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                              </svg>
                              <span class="truncate">{{ task.customer }}</span>
                            </p>
                          </div>
                          <div class="hidden md:block">
                            <div>
                              <p class="text-sm text-gray-900">
                                Due {{ task.due_date }}
                              </p>
                              <p class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Created {{ task.created_at }}</span>
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div>
                        <span :class="task.priorityClass" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                          {{ task.priority }}
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
        assigned_tickets: 0,
        completed_today: 0,
        avg_resolution_time: '0m',
      }),
    },
  },

  data() {
    return {
      tasks: [
        {
          id: 1,
          title: 'Inspect parking area A',
          customer: 'Acme Corp',
          due_date: 'today',
          created_at: '2 hours ago',
          priority: 'High',
          priorityClass: 'bg-red-100 text-red-800',
          completed: false,
        },
        {
          id: 2,
          title: 'Process monthly report',
          customer: 'Internal',
          due_date: 'in 2 days',
          created_at: '1 day ago',
          priority: 'Medium',
          priorityClass: 'bg-yellow-100 text-yellow-800',
          completed: false,
        },
        {
          id: 3,
          title: 'Attend team meeting',
          customer: 'Internal',
          due_date: 'in 3 days',
          created_at: '2 days ago',
          priority: 'Low',
          priorityClass: 'bg-green-100 text-green-800',
          completed: true,
        },
      ],
    };
  },
};
</script>
