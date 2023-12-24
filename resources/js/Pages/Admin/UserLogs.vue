<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-semibold text-2xl text-gray-800 leading-tight">Employee logs</h1>
        </template>
        <nav class="flex items-center justify-between p-1 bm-3">
            <div class="text-xl">
                <ChangeMonth @period="dateFromChangeMontchComponent" />
            </div>
            <div class="flex">
                <PrimaryButton class="mr-5" @click.prevent="showAddRecordModal">Add new record</PrimaryButton>
                <SecondaryButton class="mr-5" @click.prevent="goToBill">Bill</SecondaryButton>
                <p class="text-xl">User: {{ user.name }}</p>
            </div>
        </nav>
        <div class="flex-1 overflow-auto">
            <table class="min-w-full w-[900]">
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
                            {{ dayData.premia ? 'YES' : 'NO' }}
                        </td>

                    </tr>
                </tbody>
            </table>
            <div class="py-8 text-center text-sm text-gray-400">
                <!-- {{ id }} -->
                <br>
                <!-- {{ daysData}} -->
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
                    <div>
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
                        <label for="time">Set new record:</label>
                        <input v-model=formData.newRecord :formDate.date="dayData[0]" type="time" id="time1"
                            required />
                        <div class="mt-6 flex justify-end mt-20">
                            <SecondaryButton @click.prevent="closeModal">Cancel</SecondaryButton>
                            <PrimaryButton class="ms-3">Add record</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
            {{ }}

        </Modal>
        <Modal :show="addNewRecordModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Add new record for user: {{ user.name }}
                </h2>

                <form>
                    <div class="flex justify-evenly my-10">
                        <div class="flex flex-col text-center">
                            <label for="date">Set date:</label>
                            <input v-model="dayData" type="date" id="date" max="2030-12-31" required/>
                        </div>
                        <div class="flex flex-col text-center">
                            <label for="time">Set time:</label>
                            <input v-model=formData.newRecord type="time" id="time2" name="time" required />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end mt-20">
                        <SecondaryButton @click.prevent="closeModal">Cancel</SecondaryButton>
                        <PrimaryButton @click.prevent="addNewLog(dayData)" class="ms-3">Add record</PrimaryButton>
                    </div>
                </form>



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
import { ref, watch } from 'vue';
import ChangeMonth from '@/Components/app/ChangeMonth.vue';

const props = defineProps({
    id: String,
    user: Object,
    recordAdded: String,
    daysData: Object,
})

const formData = useForm({
    newRecord: null,
})

const isVisible = ref(false);
const addNewRecordModal = ref(false);
const dayData = ref('');

watch(() => props.recordAdded, showAlert)

function showModal(dayData) {
    this.dayData = dayData
    isVisible.value = true;
}

function showAddRecordModal() {
    addNewRecordModal.value = true
}

function closeModal() {
    formData.newRecord = null;
    dayData.value = {};
    isVisible.value = false;
    addNewRecordModal.value = false;
}
function addNewLog(day) {
    if((day in props.daysData) && (props.daysData[day].logs.includes(formData.newRecord + ':00'))) {
            alert('This record already exists.')
            return
    }

    if(day && formData.newRecord) {
        router.put(route('user.addLog', props.id), { 'newRecord': day + ' ' + formData.newRecord })
    } else {
        alert('Enter the correct date and time')
    }
    closeModal();
}

const dateFromChangeMontchComponent = (period) => {
    router.post(route('user.logs.period', props.id), { 'date': period });
}

function showAlert() {
    if (props.recordAdded) {
        alert('Rekord ' + props.recordAdded + ' has been added to the database')
        location.reload(true);
        props.recordAdded = ''
    }
}

function goToBill() {
    router.get(route('user.bill', props.id))
}


</script>
