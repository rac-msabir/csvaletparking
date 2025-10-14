<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <span class="text-xl font-bold text-gray-800">Valet Admin</span>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <inertia-link 
              :href="route('super-admin.dashboard')" 
              :class="[$page.url.startsWith('/super-admin/dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium']">
              Dashboard
            </inertia-link>
            <inertia-link 
              :href="route('super-admin.suspicious-logins.index')" 
              :class="[$page.url.startsWith('/super-admin/suspicious-logins') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium']">
              Suspicious Logins
              <span v-if="$page.props.unreadSuspiciousLoginsCount" 
                    class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white transform translate-y-1/2 -translate-x-1/2 bg-red-600 rounded-full">
                {{ $page.props.unreadSuspiciousLoginsCount }}
              </span>
            </inertia-link>
          </div>
          </div>
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <div class="ml-3 relative">
              <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-4">
                  {{ $page.props.auth.user.name }}
                </span>
                <button @click="showDropdown = !showDropdown" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <img class="h-8 w-8 rounded-full" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" />
                </button>
              </div>
              <div v-show="showDropdown" @click.away="showDropdown = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                <form @submit.prevent="logout">
                  <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Sign out
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <slot></slot>
      </div>
    </main>
  </div>
</template>

<script>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
  name: 'SuperAdminLayout',
  setup() {
    const showDropdown = ref(false);

    const logout = () => {
      router.post(route('super-admin.logout'));
    };

    return {
      showDropdown,
      logout,
    };
  },
};
</script>
