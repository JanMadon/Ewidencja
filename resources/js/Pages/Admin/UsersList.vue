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
                            Checkbox
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
                    <tr v-for="(user, index) of users" :class="{ 'bg-gray-100': index % 2 === 1 }" @dblclick="showUserLogs(user.id)" @click=""
                        class="bg-white border-b transition duration-300 ease-in-out hover:bg-blue-100 cursor-pointer">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Checkbox
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
                            {{ user.is_admin  ? 'Admin' : 'Regular' }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            <SecondaryButton class="mr-2" @click.prevent="redirectToUserBill(user.id)">Bill</SecondaryButton>
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

const props = defineProps({
    users: Object,
})

function redirectToUserBill(id){
    router.visit(route('user.bill', id)); 
}

function redirectToUserEdit(id){
    router.visit(route('user.edit', id));
}

function showUserLogs(id){
    router.get(route('user.logs', id));
}


</script>




<style scoped></style>