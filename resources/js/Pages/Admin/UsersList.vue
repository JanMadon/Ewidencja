<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-semibold text-2xl text-gray-800 leading-tight">Employees list</h1>
        </template>
        <nav class="flex items-center justify-between p-1 bm-3">
            <p>...</p>
            <div class="flex py-2">
                <div class="flex items-center mr-10">
                    <input checked id="checked-checkbox" type="checkbox" v-model="onlyActive"
                        class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500   focus:ring-2">
                    <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900">Show only active</label>
                </div>
                <PrimaryButton @click.prevent="createNewUser">create new user</PrimaryButton>
            </div>
        </nav>
        <div class="flex-1 overflow-auto">
            <table class="min-w-full w-[900]">
                <thead class="bg-gray-200 border-b">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            <input type="checkbox">
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            ID
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Name
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Firstname
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Lastname
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Email
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Premia
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Status
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Privileges
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            Opcion
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) of users"
                        v-show="user.is_active || !onlyActive"
                        @dblclick="showUserLogs(user.id)"
                        class="bg-white border-b transition duration-300 ease-in-out hover:bg-blue-100 cursor-pointer">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <input type="checkbox">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ user.id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ user.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ user.firstname }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ user.lastname }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ user.email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ user.is_premia ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ user.is_active ? 'Active' : 'Not active' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ user.is_admin ? 'Admin' : 'Regular' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            <SecondaryButton class="mr-2" @click.prevent="redirectToUserBill(user.id)">Bill
                            </SecondaryButton>
                            <PrimaryButton @click.prevent="redirectToUserEdit(user.id)">Edit</PrimaryButton>

                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="py-8 text-center text-sm text-gray-400">
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
import { ref, watch } from 'vue';

const props = defineProps({
    users: Object,
})

const onlyActive = ref(true)

function redirectToUserBill(id) {
    router.visit(route('user.bill', id));
}

function redirectToUserEdit(id) {
    router.visit(route('user.edit', id));
}

function showUserLogs(id) {
    router.get(route('user.logs', id));
}

function createNewUser() {
    console.log('create new user')
    router.get(route('user.create'));
}


</script>




<style scoped></style>
