<template>
    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 bm-3">
            <p>
            <ChangeMonth @date="dateFromChangeMontchComponent"/>
            </p>
            <PrimaryButton @click="updateLog">update log</PrimaryButton>
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
                            Time
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Date
                        </th>


                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(log, index) of logs" :class="{ 'bg-gray-200': index % 2 === 1 }">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Checkbox
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ log.employee_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            name
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ log.date_time.split(' ')[1] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ log.date_time.split(' ')[0] }}
                        </td>

                    </tr>
                </tbody>
            </table>
            <div class="py-8 text-center text-sm text-gray-400">
                {{ logs[1] }}
            </div>
            <div ref="loadMoreIntersect"></div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router } from '@inertiajs/vue3';
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