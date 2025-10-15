<template>
  <SuperAdminLayout title="Profile">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
          <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
        </div>
        
        <div class="mt-5 md:mt-0 md:col-span-2">
          <form @submit.prevent="submit">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
              <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                  <div class="col-span-6 sm:col-span-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                      v-model="form.name"
                      type="text"
                      name="name"
                      id="name"
                      autocomplete="name"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    >
                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                      {{ form.errors.name }}
                    </p>
                  </div>

                  <div class="col-span-6 sm:col-span-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
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
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input
                      v-model="form.phone"
                      type="text"
                      name="phone"
                      id="phone"
                      autocomplete="tel"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    >
                    <p v-if="form.errors.phone" class="mt-2 text-sm text-red-600">
                      {{ form.errors.phone }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button
                  type="submit"
                  class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  :disabled="form.processing"
                >
                  <span v-if="form.processing">Saving...</span>
                  <span v-else>Save</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="hidden sm:block" aria-hidden="true">
        <div class="py-5">
          <div class="border-t border-gray-200"></div>
        </div>
      </div>

      <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <h3 class="text-lg font-medium text-gray-900">Update Password</h3>
            <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
          </div>
          
          <div class="mt-5 md:mt-0 md:col-span-2">
            <form @submit.prevent="updatePassword">
              <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                      <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                      <input
                        v-model="passwordForm.current_password"
                        type="password"
                        name="current_password"
                        id="current_password"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                      >
                      <p v-if="passwordForm.errors.current_password" class="mt-2 text-sm text-red-600">
                        {{ passwordForm.errors.current_password }}
                      </p>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                      <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                      <input
                        v-model="passwordForm.password"
                        type="password"
                        name="password"
                        id="password"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                      >
                      <p v-if="passwordForm.errors.password" class="mt-2 text-sm text-red-600">
                        {{ passwordForm.errors.password }}
                      </p>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                      <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                      <input
                        v-model="passwordForm.password_confirmation"
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                      >
                    </div>
                  </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                  <button
                    type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="passwordForm.processing"
                  >
                    <span v-if="passwordForm.processing">Updating...</span>
                    <span v-else>Update Password</span>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </SuperAdminLayout>
</template>

<script>
import SuperAdminLayout from '@/Layouts/SuperAdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

export default {
  components: {
    SuperAdminLayout,
  },
  
  props: {
    user: Object,
  },

  setup(props) {
    const form = useForm({
      name: props.user.name,
      email: props.user.email,
      phone: props.user.phone,
    });

    const passwordForm = useForm({
      current_password: '',
      password: '',
      password_confirmation: '',
    });

    const submit = () => {
      form.put(route('super-admin.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
          // Handle success (e.g., show notification)
        },
      });
    };

    const updatePassword = () => {
      passwordForm.put(route('super-admin.profile.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
          passwordForm.reset('current_password', 'password', 'password_confirmation');
          // Handle success (e.g., show notification)
        },
      });
    };

    return { form, passwordForm, submit, updatePassword };
  },
};
</script>
