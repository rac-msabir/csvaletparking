<template>
  <div class="space-y-4">
    <Link 
      :href="route('employee.login')" 
      class="block w-full px-4 py-2 text-center text-sm font-medium text-white bg-primary rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Employee Login
    </Link>
    
    <p class="text-center text-sm text-gray-500">
      Are you an employee? 
      <Link :href="route('employee.login')" class="font-medium text-indigo-600 hover:text-indigo-500">
        Log in here
      </Link>
    </p>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
