<template>
    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 bm-3">
            <p>nawigacja</p>
            <p>nawigacja</p>
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
                            Breaks
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
                    <tr class="bg-white border-b ">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Checkbox
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            2-10-2020
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            08:41:25
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            18:41:25
                        </td>
                        <td @click="showModal"
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 transition duration-300 ease-in-out hover:bg-blue-100 cursor-pointer text-center">
                            Breaks(eror)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            8:15:13
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            +
                        </td>

                    </tr>
                </tbody>
            </table>
            <div class="py-8 text-center text-sm text-gray-400">
            </div>
            <div ref="loadMoreIntersect"></div>
        </div>
        <Modal :show="isVisible">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Breaks: 25-06-2002
                </h2>

                <div class="flex flex-col  mb-4">
                    <div class="text-center  p-2">Enter:
                        <strong>12:25:11</strong>
                    </div>
                    <div class=" text-center  p-2">Exit:
                        <strong>12:25:11</strong>
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
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    1
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    12:25:33
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    1
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    12:25:33
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    <form @submit.prevent="submitForm()" class=" flex flex-col justify-end items-center ">
                        <label for="appt">Set new record:</label>
                        <input v-model=formData.newRecord type="time" id="appt" name="appt" 
                            required />
                        <div class="mt-6 flex justify-end mt-20">
                            <SecondaryButton @click.prevent="closeModal">Cancel</SecondaryButton>
                            <PrimaryButton class="ms-3">Add record</PrimaryButton>
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
import { ref } from 'vue';
import UserBreaks from '@/Components/app/UserBreaks.vue';

const props = defineProps({
    users: Object,
})

const formData = useForm({
    newRecord: null,
})

const isVisible = ref(false);

function showModal() {
    isVisible.value = true;
}
function closeModal() {
    isVisible.value = false;
}
function submitForm() {
    console.log(formData.newRecord)
    formData.post(route())
}
// const submitForm = () => {
//     console.log('jestem')
//     formData.post();
// };




</script>




<style scoped></style>