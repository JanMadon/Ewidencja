
<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-semibold text-2xl text-gray-800 leading-tight">Administrator dashboard</h1>
        </template>

        <div class="py-12">
            <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">You're logged in as admin!</div>
                </div>
            </div> -->
            <div class="container mx-auto mt-8 flex flex-wrap justify-start ">
                <!-- Using v-for to dynamically render employee tiles -->
                <div v-for="(user, index) in usersLogs" v-show="user.length % 2"
                    class="bg-white border border-gray-300 rounded shadow-md p-4 text-center m-4 w-64">
                    <h3 class="text-lg font-semibold">{{ index ? index : `unknown, id:${user[0].employee_id}`}}</h3>
                    <p class="text-gray-600"> ... department</p>
                    <p class="text-gray-600"> start: {{ user[user.length-1].date_time.slice(10) }} </p>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    usersLogs: Object,
})

</script>
