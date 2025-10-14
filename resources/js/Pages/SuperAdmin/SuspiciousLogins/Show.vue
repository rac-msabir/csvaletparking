<template>
  <AppLayout title="Suspicious Login Details">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Suspicious Login Details
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900">User Information</h3>
                <dl class="mt-2 space-y-4">
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ suspiciousLogin.user?.name }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ suspiciousLogin.user?.email }}</dd>
                  </div>
                </dl>
              </div>

              <div>
                <h3 class="text-lg font-medium text-gray-900">Location Information</h3>
                <dl class="mt-2 space-y-4">
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Login Location</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                      <a :href="`https://www.google.com/maps?q=${suspiciousLogin.login_latitude},${suspiciousLogin.login_longitude}`" 
                         target="_blank" 
                         class="text-blue-600 hover:text-blue-900">
                        {{ suspiciousLogin.login_latitude }}, {{ suspiciousLogin.login_longitude }}
                      </a>
                    </dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Allowed Location</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                      {{ suspiciousLogin.allowed_latitude }}, {{ suspiciousLogin.allowed_longitude }}
                    </dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Distance</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                      {{ suspiciousLogin.distance_km.toFixed(2) }} km
                    </dd>
                  </div>
                </dl>
              </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
              <dl class="mt-2 space-y-4">
                <div>
                  <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ suspiciousLogin.ip_address }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ suspiciousLogin.user_agent }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(suspiciousLogin.created_at) }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Status</dt>
                  <dd class="mt-1">
                    <span :class="suspiciousLogin.notified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'" 
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ suspiciousLogin.notified ? 'Notified' : 'Pending' }}
                    </span>
                  </dd>
                </div>
              </dl>
            </div>

            <div class="mt-8 flex justify-end">
              <Link :href="route('super-admin.suspicious-logins.index')" 
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to List
              </Link>
              <button v-if="!suspiciousLogin.notified" 
                      @click="markAsNotified" 
                      class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Mark as Notified
              </button>
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
    suspiciousLogin: Object,
  },

  methods: {
    formatDate(date) {
      return new Date(date).toLocaleString();
    },

    markAsNotified() {
      this.$inertia.post(route('super-admin.suspicious-logins.mark-notified', this.suspiciousLogin.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
          // Optional: Show success message
        },
      });
    },
  },
};
</script>