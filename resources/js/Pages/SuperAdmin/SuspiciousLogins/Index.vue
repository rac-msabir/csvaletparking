<template>
  <AppLayout title="Suspicious Login Attempts">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Suspicious Login Attempts
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="login in suspiciousLogins.data" :key="login.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ login.user?.name }}</div>
                      <div class="text-sm text-gray-500">{{ login.user?.email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <a :href="`https://www.google.com/maps?q=${login.login_latitude},${login.login_longitude}`" 
                         target="_blank" 
                         class="text-blue-600 hover:text-blue-900">
                        View on Map
                      </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ login.distance_km.toFixed(2) }} km
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ login.ip_address }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(login.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="login.notified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'" 
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ login.notified ? 'Notified' : 'Pending' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <Link :href="route('super-admin.suspicious-logins.show', login.id)" 
                            class="text-indigo-600 hover:text-indigo-900 mr-4">
                        View
                      </Link>
                      <button v-if="!login.notified" 
                              @click="markAsNotified(login.id)" 
                              class="text-green-600 hover:text-green-900">
                        Mark as Notified
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    AppLayout,
    Link,
  },

  props: {
    suspiciousLogins: Object,
  },

  methods: {
    formatDate(date) {
      return new Date(date).toLocaleString();
    },

    markAsNotified(id) {
      this.$inertia.post(route('super-admin.suspicious-logins.mark-notified', id), {}, {
        preserveScroll: true,
        onSuccess: () => {
          // Optional: Show success message
        },
      });
    },
  },
};
</script>