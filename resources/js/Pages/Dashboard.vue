<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">login dashboard</h2>
        </template>

        <div class="py-12 border-solid border-2 border-red-500 text-center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                    <p>
                        Unfortunately you were not found in the employee list. Contact your administrator!
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
