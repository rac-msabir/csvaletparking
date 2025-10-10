<template>
  <AppLayout :title="'Dashboard'">
    <template #header>
      <div class="flex items-center space-x-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">UR VALET</h2>
        <!-- Site selector -->
        <div class="relative" @keydown.escape="siteOpen=false">
          <button type="button" @click="siteOpen=!siteOpen" class="inline-flex items-center h-9 px-3 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
            <span class="truncate max-w-[10rem]">{{ selectedSite }}</span>
            <svg class="ml-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div v-if="siteOpen" class="absolute z-20 mt-2 w-56 rounded-xl bg-white shadow-lg ring-1 ring-black/5 overflow-hidden">
            <ul class="py-1 max-h-64 overflow-auto">
              <li v-for="s in sites" :key="s.value" @click="chooseSite(s)" class="px-3 py-2 text-sm hover:bg-gray-50 cursor-pointer flex items-center justify-between">
                <span class="truncate">{{ s.label }}</span>
                <svg v-if="s.label===selectedSite" class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </template>

    <div class="py-6 md:py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Top Counters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
          <div class="rounded-2xl bg-white shadow border border-gray-100">
            <div class="p-6 flex items-center justify-center">
              <div class="flex flex-col items-center justify-center">
                <p class="text-sm text-gray-500">New</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ stats?.new_count ?? 0 }}</p>
              </div>
             
            </div>
          </div>
          <div class="rounded-2xl bg-white shadow border border-gray-100">
            <div class="p-6 flex items-center justify-center">
              <div class="flex flex-col items-center justify-center">
                <p class="text-sm text-gray-500">Requested</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ stats?.requested_count ?? 0 }}</p>
              </div>
             
            </div>
          </div>
          <div class="rounded-2xl bg-white shadow border border-gray-100">
            <div class="p-6 flex items-center justify-center">
              <div class="flex flex-col items-center justify-center">
                <p class="text-sm text-gray-500">Delivered</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ stats?.delivered_count ?? 0 }}</p>
              </div>
            
            </div>
          </div>
        </div>

        <!-- Add Ticket -->
        <div class="mt-6 md:mt-8">
          <Link :href="route('employee.tickets.create')" class="block w-full inline-flex items-center justify-center rounded-full bg-indigo-700 hover:bg-indigo-800 text-white h-12 px-6 md:px-10 font-medium">
            <span class="text-lg">+ Add Ticket</span>
          </Link>
        </div>

        <!-- Add Ticket + Search + Daily Report -->
        <div class="mt-6 md:mt-8">
          <div class="rounded-2xl bg-white shadow border border-gray-100 px-4 md:px-6 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6 space-y-4 md:space-y-0">

              <div class="flex-1 flex space-x-3">
                <div class="w-48">
                  <select v-model="searchBy" class="block w-full h-12 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-700">
                    <option disabled value="">Select Search By</option>
                    <option value="phone">Customer Phone Number</option>
                    <option value="ticket">Ticket Number</option>
                    <option value="plate">Car Plate</option>
                  </select>
                </div>
                <div class="flex-1">
                  <input v-model="searchQuery" :placeholder="searchPlaceholder" class="block w-full h-12 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 px-4" />
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Daily Report -->
        <div class="mt-6 md:mt-8">
          <button type="button" @click="dateModalOpen = true" class="w-full md:w-auto inline-flex items-center justify-center h-12 px-6 rounded-full border border-indigo-300 text-indigo-700 hover:bg-indigo-50">Daily Report</button>
        </div>
        <!-- Requested Cars / All Tickets -->
        <div class="mt-2">
          <div class="rounded-2xl bg-white shadow border border-gray-100">
            <!-- Tabs + Sort/Date -->
            <div class="px-4 md:px-6 pt-5 flex items-center justify-between">
              <div class="flex space-x-6">
                <button @click="activeTab = 'requested'" :class="tabClass('requested')">Requested Cars</button>
                <button @click="activeTab = 'all'" :class="tabClass('all')">All Tickets</button>
              </div>
              <div class="flex items-center space-x-6 text-indigo-700 font-medium">
                <button class="hover:opacity-80" @click="toggleSort">Sort</button>
                <button class="hover:opacity-80" @click="dateModalOpen = true">Date</button>
              </div>
            </div>

            <!-- Table -->
            <div class="mt-4 overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-900/95 text-white">
                  <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ticket Number</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Customer Need Car at</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Customer Phone Number</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Total Price/Payment Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Car Brand</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Car Plate</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Note</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="filteredTickets.length === 0">
                    <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                      <div class="inline-flex flex-col items-center">
                        <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                        <span class="mt-3">No Data</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-for="t in paginatedTickets" :key="t.id" class="odd:bg-white even:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ t.reference }}</td>
                    <td class="px-6 py-4">
                      <button @click="openStatusModal(t)" :class="statusPillClass(t.status)" class="px-3 py-1 rounded-full text-xs font-semibold cursor-pointer">{{ t.status_label || t.status }}</button>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ t.need_at || '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ t.customer_phone || t.customer_phone_number || '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ t.total_price ?? 'Free' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 md:px-6 py-4 flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <select v-model.number="perPage" class="h-9 rounded-lg border-gray-300 text-sm">
                  <option :value="10">10 / page</option>
                  <option :value="20">20 / page</option>
                  <option :value="50">50 / page</option>
                </select>
              </div>
              <div class="flex items-center space-x-2">
                <button class="h-9 w-9 rounded-md border border-gray-300 disabled:opacity-40" :disabled="page===1" @click="page--">«</button>
                <span class="px-3">{{ page }}</span>
                <button class="h-9 w-9 rounded-md border border-gray-300 disabled:opacity-40" :disabled="page>=maxPage" @click="page++">»</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Date Modal -->
    <div v-if="dateModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/40" @click="dateModalOpen=false"></div>
      <div class="relative w-full max-w-lg mx-auto bg-white rounded-2xl shadow-xl">
        <div class="px-6 py-4 flex items-center justify-between border-b">
          <h3 class="text-lg font-semibold text-gray-800">Select Date</h3>
          <button class="text-red-500" @click="dateModalOpen=false">✕</button>
        </div>
        <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">From</label>
            <input type="date" v-model="reportFromDate" class="block w-full rounded-lg border-gray-300" />
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">To</label>
            <input type="date" v-model="reportToDate" class="block w-full rounded-lg border-gray-300" />
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">From</label>
            <select v-model="reportFromTime" class="block w-full rounded-lg border-gray-300">
              <option v-for="t in timeOptions" :key="'f-'+t" :value="t">{{ t }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">To</label>
            <select v-model="reportToTime" class="block w-full rounded-lg border-gray-300">
              <option v-for="t in timeOptions" :key="'t-'+t" :value="t">{{ t }}</option>
            </select>
          </div>
        </div>
        <div class="px-6 pb-6 flex flex-col space-y-3 items-center justify-between">
          <button class="block w-full h-12 px-8 rounded-full bg-indigo-700 text-white hover:bg-indigo-800" @click="applyReport">Ok</button>
          <button class="block w-full h-12 px-8 rounded-full border border-gray-300" @click="dateModalOpen=false">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Status Modal -->
    <div v-if="statusModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/40" @click="statusModalOpen=false"></div>
      <div class="relative w-full max-w-md mx-auto bg-white rounded-2xl shadow-xl">
        <div class="px-6 py-4 flex items-center justify-between border-b">
          <h3 class="text-lg font-semibold text-gray-800">SELECT STATUS</h3>
          <button class="text-red-500" @click="statusModalOpen=false">✕</button>
        </div>
        <div class="px-6 py-6 space-y-3">
          <label class="flex items-center justify-between p-4 rounded-xl border cursor-pointer" :class="selectedStatus==='ready' ? 'border-fuchsia-500 bg-fuchsia-50' : 'border-gray-200'">
            <div class="flex items-center space-x-3">
              <span class="h-3 w-3 rounded-full bg-fuchsia-600"></span>
              <span class="text-fuchsia-700 font-medium">Car Ready By Employee</span>
            </div>
            <input type="radio" class="hidden" value="ready" v-model="selectedStatus" />
          </label>
          <label class="flex items-center justify-between p-4 rounded-xl border cursor-pointer" :class="selectedStatus==='delivered' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200'">
            <div class="flex items-center space-x-3">
              <span class="h-3 w-3 rounded-full bg-emerald-600"></span>
              <span class="text-emerald-700 font-medium">Car Delivered By Employee</span>
            </div>
            <input type="radio" class="hidden" value="delivered" v-model="selectedStatus" />
          </label>
        </div>
        <div class="px-6 pb-6">
          <button class="w-full h-12 rounded-full bg-indigo-700 text-white hover:bg-indigo-800" @click="submitStatus">Submit</button>
        </div>
      </div>
    </div>

    <!-- QR Modal (preview only) -->
    <div v-if="qrModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/40" @click="qrModalOpen=false"></div>
      <div class="relative w-full max-w-md mx-auto bg-white rounded-2xl shadow-xl">
        <div class="px-6 py-4 flex items-center justify-between border-b">
          <h3 class="text-lg font-semibold text-gray-800">Ticket Number# {{ activeTicket?.reference }}</h3>
          <button class="text-red-500" @click="qrModalOpen=false">✕</button>
        </div>
        <div class="px-6 py-6 text-center">
          <div class="inline-block p-3 border rounded-xl">
            <img :src="activeTicket?.qr_url || placeholderQr" alt="QR" class="h-40 w-40 object-contain" />
          </div>
          <div class="mt-6 space-y-3">
            <button class="w-full h-11 rounded-xl border border-indigo-300 text-indigo-700">Print Full Ticker QR</button>
            <button class="w-full h-11 rounded-xl border border-indigo-300 text-indigo-700">Print Ticket Number</button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/***** Keep custom styles minimal; rely on Tailwind *****/
</style>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const props = defineProps({
  stats: Object,
  recentTickets: Array,
  auth: Object,
  sites: { type: Array, default: () => [] }, // e.g., [{label:'Nua', value:'nua'}]
  currentSite: { type: String, default: '' },
});

/* Site selector */
const siteOpen = ref(false);
const sites = computed(() => (props.sites && props.sites.length ? props.sites : [{ label: props.currentSite || 'Nua', value: props.currentSite || 'nua' }]));
const selectedSite = ref(localStorage.getItem('uv:selectedSite') || (sites.value[0]?.label || 'Nua'));
const chooseSite = (site) => {
  selectedSite.value = site.label;
  siteOpen.value = false;
  localStorage.setItem('uv:selectedSite', site.label);
  toast.success(`Switched to ${site.label}`);
  // Optionally trigger server-side switch with Inertia if route exists
  // router.post(route('employee.site.switch'), { site: site.value })
};

/* Search */
const searchBy = ref('');
const searchQuery = ref('');
const searchPlaceholder = computed(() => {
  if (searchBy.value === 'phone') return 'Request Search';
  if (searchBy.value === 'ticket') return 'Ticket Number';
  if (searchBy.value === 'plate') return 'Car Plate';
  return 'Request Search';
});

/* Tabs */
const activeTab = ref('requested');

/* Tickets source (use provided list as sample) */
const tickets = computed(() => props.recentTickets || []);

/* Filtering */
const filteredTickets = computed(() => {
  if (!searchQuery.value) return tickets.value;
  const q = String(searchQuery.value).toLowerCase();
  return tickets.value.filter((t) => {
    if (searchBy.value === 'phone') return String(t.customer_phone || t.customer_phone_number || '').toLowerCase().includes(q);
    if (searchBy.value === 'ticket') return String(t.reference || t.id || '').toLowerCase().includes(q);
    if (searchBy.value === 'plate') return String(t.car_plate || '').toLowerCase().includes(q);
    return (
      String(t.reference || '').toLowerCase().includes(q) ||
      String(t.customer_phone || '').toLowerCase().includes(q)
    );
  });
});

/* Pagination */
const perPage = ref(10);
const page = ref(1);
const maxPage = computed(() => Math.max(1, Math.ceil(filteredTickets.value.length / perPage.value)));
const paginatedTickets = computed(() => {
  if (page.value > maxPage.value) page.value = maxPage.value;
  const start = (page.value - 1) * perPage.value;
  return filteredTickets.value.slice(start, start + perPage.value);
});

/* Sort button placeholder */
const toggleSort = () => {
  toast.info('Sorting not changed — demo UI');
};

/* Pills */
const statusPillClass = (s) => {
  const v = String(s || '').toLowerCase();
  if (v.includes('ready')) return 'bg-fuchsia-100 text-fuchsia-700';
  if (v.includes('deliver')) return 'bg-emerald-100 text-emerald-700';
  if (v.includes('request')) return 'bg-amber-100 text-amber-700';
  return 'bg-gray-100 text-gray-700';
};

const tabClass = (tab) => `pb-3 border-b-2 ${activeTab.value===tab ? 'border-indigo-700 text-indigo-700 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'}`;

/* Date modal */
const dateModalOpen = ref(false);
const reportFromDate = ref(new Date().toISOString().slice(0,10));
const reportToDate = ref(new Date().toISOString().slice(0,10));
const timeOptions = Array.from({ length: 24 }, (_, i) => {
  const h = ((i + 11) % 12) + 1;
  const am = i < 12 ? 'AM' : 'PM';
  return `${h}:00${am}`;
});
const reportFromTime = ref('12:00PM');
const reportToTime = ref('4:00AM');
const applyReport = () => {
  dateModalOpen.value = false;
  toast.success('Report filters applied');
};

/* Status modal */
const statusModalOpen = ref(false);
const selectedStatus = ref('ready');
const activeTicket = ref(null);
const openStatusModal = (t) => { activeTicket.value = t; statusModalOpen.value = true; };
const submitStatus = () => {
  statusModalOpen.value = false;
  toast.success('Ticket status updated successfully!');
};

/* QR modal */
const qrModalOpen = ref(false);
const placeholderQr = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160"><rect width="160" height="160" fill="white"/><rect x="10" y="10" width="20" height="20" fill="black"/><rect x="130" y="10" width="20" height="20" fill="black"/><rect x="10" y="130" width="20" height="20" fill="black"/><rect x="70" y="70" width="20" height="20" fill="black"/></svg>';

/* Notifications (kept from original) */
const notifications = ref([]);
const showNotification = (notification) => {
  toast.info(
    `<div class=\"flex flex-col\"><span class=\"font-semibold\">${notification.title}</span><span class=\"text-sm\">${notification.message}</span>${notification.url ? `<a href=\"${notification.url}\" class=\"text-blue-500 hover:underline mt-1 text-xs\">View Details →</a>` : ''}</div>`,
    { position: 'top-right', autoClose: 8000, hideProgressBar: false, closeOnClick: true, pauseOnHover: true, draggable: true, toastClassName: '!bg-white !text-gray-800 !shadow-lg', bodyClassName: 'p-0' }
  );
};

let echoInstance = null;
let channel = null;

onMounted(() => {
  const initEcho = (attempt = 0) => {
    echoInstance = window.Echo;
    if (!echoInstance) {
      if (attempt < 10) setTimeout(() => initEcho(attempt + 1), 200);
      else console.warn('Echo is not initialized after retries; skipping realtime setup.');
      return;
    }
    if (!props.auth?.user?.id) return;
    channel = echoInstance.private(`user.${props.auth.user.id}`);
    if (channel && typeof channel.subscribed === 'function') channel.subscribed(() => { console.debug('Echo: subscribed to', `user.${props.auth.user.id}`); });
    channel.listen('.vehicle.requested', (data) => { showNotification({ title: 'Vehicle Requested', message: data.message, url: data.url, ticketId: data.ticket_id }); });
    if (props.auth?.user?.tenant_id) {
      const tenantChannel = echoInstance.private(`tenant.${props.auth.user.tenant_id}`);
      if (tenantChannel && typeof tenantChannel.subscribed === 'function') tenantChannel.subscribed(() => { console.debug('Echo: subscribed to', `tenant.${props.auth.user.tenant_id}`); });
      tenantChannel.listen('.vehicle.requested', (data) => { showNotification({ title: 'Vehicle Requested', message: data.message, url: data.url, ticketId: data.ticket_id }); });
    }
  };
  initEcho();
});

onUnmounted(() => {
  if (channel) {
    channel.stopListening('.vehicle.requested');
    if (echoInstance) echoInstance.leave(`user.${props.auth.user?.id}`);
  }
});

defineExpose({ showNotification });
</script>
