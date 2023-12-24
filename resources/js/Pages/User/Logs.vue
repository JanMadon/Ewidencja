<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-semibold text-2xl text-gray-800 leading-tight">User logs</h1>
        </template>

        <nav class="flex items-center justify-between p-1 bm-3">
            <p>
                <ChangeMonth @period="dateFromChangeMontchComponent" />
            </p>
            <p>...</p>
        </nav>
        <div class="flex-1 overflow-auto">
            <p v-if="!daysData.length" class="text-center pt-10">The database does not contain any records.</p>
            <table v-else class="min-w-full w-[900]">
                <thead class="bg-gray-200 border-b">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            LP.
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Date
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Enter
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Exit
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Logs
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Working time
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Premia
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(dayData, index) of daysData" :key="index" class="bg-white border-b ">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ (Object.keys(daysData)).indexOf(index) + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ dayData.logs[0] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ dayData.logs[dayData.logs.length - 1] }}
                        </td>
                        <td @click="showModal([index, dayData])"
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 transition duration-300 ease-in-out hover:bg-blue-100 cursor-pointer text-center"
                            :class="{ 'bg-red-200': dayData.logs.length % 2 }">
                            {{ dayData.logs.length }} {{ dayData.logs.length % 2 ? "(error)" : "" }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ dayData.work_time }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            +
                        </td>

                    </tr>
                </tbody>
            </table>
            <div class="py-8 text-center text-sm text-gray-400">
                <!-- {{ id }} -->
                <br>
                <!-- {{ daysData }} -->
            </div>
            <div ref="loadMoreIntersect"></div>
        </div>
        <Modal :show="isVisible">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Date: {{ dayData[0] }}
                </h2>
                <div class="flex justify-evenly mb-4">
                    <div>
                        <div class="text-center  p-2">Enter:
                            <strong>{{ dayData[1].logs[0] }}</strong>
                        </div>
                        <div class="p-2">Exit:
                            <strong>{{ dayData[1].logs[dayData[1].logs.length - 1] }}</strong>
                        </div>
                    </div>
                    <div >
                        <div class="text-center  p-2">Time at work:
                            <strong>{{ dayData[1].time }}</strong>
                        </div>
                        <div class="   p-2">Break time:
                            <strong>{{ dayData[1].break_time }}</strong>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <table class="ml-20 text-center w-[100]">
                        <thead class="bg-gray-200 border-b">
                            <tr>
                                <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    LP.
                                </th>
                                <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Time record
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(log, index) in dayData[1].logs">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ log }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <form @submit.prevent="addNewLog(dayData[0])" class=" flex flex-col justify-end items-center ">
                        <label for="appt">New record:</label>
                        <input v-model=formData.newRecord :formDate.date="dayData[0]"   type="time" id="appt" name="appt" required />
                        <div class="mt-6 flex justify-end mt-20">
                            <SecondaryButton @click.prevent="closeModal">Cancel</SecondaryButton>
                            <PrimaryButton class="ms-3">Send request</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>

        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch ,onMounted  } from 'vue';
import ChangeMonth from '@/Components/app/ChangeMonth.vue';

const props = defineProps({
    id: Number,
    recordAdded: String,
    daysData: Object,
})

const formData = useForm({
    newRecord: null,
})

const isVisible = ref(false);
const dayData = ref({});

watch(()=>props.recordAdded, showAlert)

function showModal(dayData) {
    this.dayData = dayData
    isVisible.value = true;
}

function closeModal() {
    dayData.value = {};
    isVisible.value = false;
}
function addNewLog(day) {
    // sprawdz czy takiego loga jużnie ma tego dnia

    router.put(route('my.addLogRequest'), {'newRecord': day +' '+  formData.newRecord})
    formData.newRecord = null;
    closeModal();
}

const dateFromChangeMontchComponent = (period) => {
     router.post(route('my.logs.period'), {'id': props.id ,'date': period });
}

function showAlert() {
    if(props.recordAdded) {
        alert('Rekord ' + props.recordAdded + ' has been sent for admin approval')
        location.reload(true);
        props.recordAdded = ''
    }
}



</script>




<style scoped></style>
