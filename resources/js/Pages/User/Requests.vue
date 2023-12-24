<template>
    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 bm-3">
            <p>...</p>
            <p>...</p>
        </nav>
        <div class="flex overflow-auto">
            <table class="min-w-full w-[900]">
                <thead class="text-center bg-gray-300 border-b">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            LP
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            Request ID
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            User name
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            Approved by
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            Status
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            Created at
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-center">
                            Options
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(userRequests, index) of usersRequests.data" :class="{'bg-red-100': userRequests.status === 'rejected', 'bg-green-100': userRequests.status === 'accpeted'} " >
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ index+1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ userRequests.logId }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ userRequests.userName ?? 'unown, id=' + userRequests.userId }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ userRequests.approvedBy }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ userRequests.status }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            {{ userRequests.createdAt }}
                        </td>

                        <td class=" py-4 whitespace-nowrap text-center font-medium text-gray-900 ">
                            <SecondaryButton v-show="!userRequests.approvedBy" class="scale-90"
                             @click.prevent="moveToTrash(userRequests.logId)">
                                to trash
                            </SecondaryButton>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class=" flex justify-center">
            <Link
                class="mx-5 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                v-if="usersRequests.prev_page_url" :href="usersRequests.prev_page_url">
            Prev page
            </Link>
            <Link
                class="mx-5 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                v-if="usersRequests.next_page_url" :href="usersRequests.next_page_url">
            Next page
            </Link>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    usersRequests: Object,
})

function moveToTrash(requestId) {
    router.delete(route('my.deleteRequest', requestId));
}


</script>




<style scoped></style>
