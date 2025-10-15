<template>
  <SuperAdminLayout :title="`Edit Tenant: ${form.name}`">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <h3 class="text-lg font-medium text-gray-900">Tenant Details</h3>
          <p class="mt-1 text-sm text-gray-600">
            Update the tenant organization's information.
          </p>
        </div>
        
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form @submit.prevent="submit" method="POST">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
              <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                  <div class="col-span-6 sm:col-span-4">
                    <input type="hidden" name="_token" :value="$page.props.csrf_token">
                    <label for="name" class="block text-sm font-medium text-gray-700">Organization Name</label>
                    <input
                      v-model="form.name"
                      type="text"
                      name="name"
                      id="name"
                      autocomplete="organization"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    >
                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                      {{ form.errors.name }}
                    </p>
                  </div>

                  <div class="col-span-6 sm:col-span-4">
                    <label for="domain" class="block text-sm font-medium text-gray-700">Domain</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                      <input
                        v-model="form.domain"
                        type="text"
                        name="domain"
                        id="domain"
                        class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300"
                        :disabled="!canEditDomain"
                      >
                      <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                        .{{ appDomain }}
                      </span>
                    </div>
                    <p v-if="form.errors.domain" class="mt-2 text-sm text-red-600">
                      {{ form.errors.domain }}
                    </p>
                    <p v-if="!canEditDomain" class="mt-2 text-sm text-gray-500">
                      Domain cannot be changed after creation.
                    </p>
                  </div>

                  <div class="col-span-6 sm:col-span-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                    <input
                      v-model="form.email"
                      type="email"
                      name="email"
                      id="email"
                      autocomplete="email"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    >
                    <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                      {{ form.errors.email }}
                    </p>
                  </div>

                  <div class="col-span-6 sm:col-span-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                    <input
                      v-model="form.phone"
                      type="tel"
                      name="phone"
                      id="phone"
                      autocomplete="tel"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    >
                    <p v-if="form.errors.phone" class="mt-2 text-sm text-red-600">
                      {{ form.errors.phone }}
                    </p>
                  </div>

                  <div class="col-span-6">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea
                      v-model="form.address"
                      id="address"
                      name="address"
                      rows="3"
                      class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    ></textarea>
                    <p v-if="form.errors.address" class="mt-2 text-sm text-red-600">
                      {{ form.errors.address }}
                    </p>
                  </div>

                  <div class="col-span-6">
                    <div class="flex items-start">
                      <div class="flex items-center h-5">
                        <input
                          v-model="form.is_active"
                          id="is_active"
                          name="is_active"
                          type="checkbox"
                          class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                        >
                      </div>
                      <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Active</label>
                        <p class="text-gray-500">When inactive, users won't be able to access this tenant.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button
                  type="button"
                  @click="$inertia.visit(route('super-admin.tenants.index'))"
                  class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  :disabled="form.processing"
                >
                  <span v-if="form.processing">Updating...</span>
                  <span v-else>Update Tenant</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </SuperAdminLayout>
</template>

<script>
import SuperAdminLayout from '@/Layouts/SuperAdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

export default {
  components: {
    SuperAdminLayout,
  },
  
  props: {
    tenant: Object,
    appDomain: {
      type: String,
      default: 'example.com', // This should be passed from the backend
    },
  },
  
  setup(props) {
    const form = useForm({
      name: props.tenant.name,
      domain: props.tenant.domain,
      email: props.tenant.email,
      phone: props.tenant.phone,
      address: props.tenant.address,
      is_active: props.tenant.is_active,
    });

    const submit = () => {
      form.put(route('super-admin.tenants.update', props.tenant.id), {
        onSuccess: () => {
          // Handle success (e.g., show notification)
        },
        preserveScroll: true,
      });
    };

    // Domain cannot be changed after creation
    const canEditDomain = false;

    return { form, submit, canEditDomain, Link };
  },
};
</script>
