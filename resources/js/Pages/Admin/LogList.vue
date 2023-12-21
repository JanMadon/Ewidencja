<template>
    <AuthenticatedLayout>
       
        <template #header>
            <h1 class="font-semibold text-2xl text-gray-800 leading-tight">Recent raw logs</h1>
        </template>
        <nav class="flex justify-end p-1 bm-3">
            <PrimaryButton @click="updateLog">send log to DB</PrimaryButton>
        </nav>
        <div class="flex justify-center overflow-auto">
            <table class=" w-[600]">
                <thead class="bg-gray-200 border-b">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Checkbox
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            ID
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Name
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Date
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Time
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(log, index) of logs.data" :key="index" :class="{ 'bg-gray-200': index % 2 === 1 }">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ (index + 1) * logs.current_page }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ log.employee_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ log.user ? log.user.name : 'unknown' }}

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ log.date_time.split(' ')[0] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ log.date_time.split(' ')[1] }}
                        </td>

                    </tr>
                </tbody>
            </table>
            <div class="py-8 text-center text-sm text-gray-400">
                <!-- {{ logs }} -->
            </div>
            <div ref="loadMoreIntersect">


            </div>

        </div>
        <div class=" flex justify-center">
            <Link
                class="mx-5 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                v-if="logs.prev_page_url" :href="logs.prev_page_url">Prev page</Link>
            <Link
                class="mx-5 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                v-if="logs.next_page_url" :href="logs.next_page_url">Next page</Link>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import ChangeMonth from '@/Components/app/ChangeMonth.vue'




const setTime = ref('')

const props = defineProps({
    logs: Object
})

function updateLog() {
    // TO DO czy napewno???
    router.post(route('log.set'));
}

const dateFromChangeMontchComponent = (date) => {
    setTime.value = date
}




</script>




<style scoped></style>