<template>
    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 bm-3">
            <p>
            <div>
                <SecondaryButton @click="moveMonth(-1)">Previous month</SecondaryButton>
                <span>{{ currentMonth }}</span>
                <SecondaryButton @click="moveMonth(1)">Next month</SecondaryButton>
            </div>
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

const currentDate = ref(new Date());

const props = defineProps({
    logs: Object
})

function updateLog() {
    // TO DO czy napewno???
    router.post(route('log.set'));
}

// Format date as "MMMM YYYY"
const formatDate = (date) => {
    prepereDateForBackend(date)
    console.log(date)
    const options = { month: 'long', year: 'numeric' };
    return new Intl.DateTimeFormat('en-US', options).format(date);
};

// Current month in "MMMM YYYY" format
const currentMonth = ref(formatDate(currentDate.value));

// Function to move to the next or previous month
const moveMonth = (number) => {
    currentDate.value.setMonth(currentDate.value.getMonth() + number);
    currentMonth.value = formatDate(currentDate.value);
};

function prepereDateForBackend(date) {
    let year = date.getFullYear();
    let month = ('0' + (date.getMonth() + 1)).slice(-2);
    let day = ('0' + date.getDate()).slice(-2);
    let hours = ('0' + date.getHours()).slice(-2);
    let minutes = ('0' + date.getMinutes()).slice(-2);
    let seconds = ('0' + date.getSeconds()).slice(-2);

    let secondDate = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

    console.log(secondDate);
}

</script>




<style scoped></style>