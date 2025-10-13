<template>
  <AppLayout :title="'Dashboard'">
    <template #header>
      <div class="flex items-center space-x-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tenant Admin</h2>
      </div>
    </template>

    <div class="py-6 md:py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
          <div class="rounded-2xl bg-white shadow border border-gray-100">
            <div class="p-6 flex items-center justify-center">
              <div class="flex flex-col items-center justify-center">
                <p class="text-sm text-gray-500">Active Tickets</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ stats?.active_tickets ?? 0 }}</p>
              </div>
            </div>
          </div>
          <div class="rounded-2xl bg-white shadow border border-gray-100">
            <div class="p-6 flex items-center justify-center">
              <div class="flex flex-col items-center justify-center">
                <p class="text-sm text-gray-500">Employees</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ stats?.employees_count ?? 0 }}</p>
              </div>
            </div>
          </div>
          <div class="rounded-2xl bg-white shadow border border-gray-100">
            <div class="p-6 flex items-center justify-center">
              <div class="flex flex-col items-center justify-center">
                <p class="text-sm text-gray-500">Avg. Response Time</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ stats?.avg_response_time ?? '0m' }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 md:mt-8">
          <Link :href="route('tenant.tickets.create')" class="block w-full inline-flex items-center justify-center rounded-full bg-indigo-700 hover:bg-indigo-800 text-white h-12 px-6 md:px-10 font-medium">
            <span class="text-lg">+ Add Ticket</span>
          </Link>
        </div>

        <div class="mt-2">
          <div class="rounded-2xl bg-white shadow border border-gray-100">
            <div class="px-4 md:px-6 pt-5 flex items-center justify-between">
              <div class="flex space-x-6">
                <Link :href="route('tenant.tickets.index')" class="pb-3 border-b-2 border-indigo-700 text-indigo-700 font-semibold">All Tickets</Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  stats: Object,
});
</script>
