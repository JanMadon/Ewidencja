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
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Opcje
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) of users" :class="{ 'bg-gray-100': index % 2 === 1 }"
                        class="bg-white border-b transition duration-300 ease-in-out hover:bg-blue-100 cursor-pointer">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Checkbox
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ user.id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center">
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
                            {{ user.premia }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ user.status }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ user.privileges }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            <PrimaryButton @click="redirectToUserEdit(user.id)">Edit</PrimaryButton>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="py-8 text-center text-sm text-gray-400">
                {{ users }}
            </div>
            <div ref="loadMoreIntersect"></div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    users: Object,
})

function redirectToUserEdit(id){
    console.log(id);
    router.visit(route('user.edit', id));
}


</script>




<style scoped></style>