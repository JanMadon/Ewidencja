<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-semibold text-2xl text-gray-800 leading-tight">Create employee</h1>
        </template>
        <nav class="flex items-center justify-between p-1 bm-3">
            <p>...</p>
            <p>...</p>
        </nav>
        <div>
            <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
                <h2 class="text-2xl font-semibold mb-6 text-center">User creator form </h2>

                <form @submit.prevent="submitForm" class="grid grid-cols-2 gap-4">
                    <!-- Lewa strona formularza -->
                    <div class="col-span-1">
                        <div class="mb-4">
                            <label for="id" class="block text-gray-700 text-sm font-bold mb-2">id:</label>
                            <input v-model="formData.id" type="number" id="id"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-blue-500"
                                placeholder="id" />
                        </div>
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                            <input v-model="formData.name" type="text" id="name"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-blue-500"
                                placeholder="name" required  />
                        </div>
                        <div class="mb-4">
                            <label for="firstname" class="block text-gray-700 text-sm font-bold mb-2">Firstname:</label>
                            <input v-model="formData.firstname" type="text" id="name"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-blue-500"
                                placeholder="firstname" required  />
                        </div>
                        <div class="mb-4">
                            <label for="lastname" class="block text-gray-700 text-sm font-bold mb-2">Lastname:</label>
                            <input v-model="formData.lastname" type="text" id="name"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-blue-500"
                                placeholder="lastname" required  />
                        </div>


                    </div>

                    <!-- Prawa strona formularza -->
                    <div class="col-span-1">
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                            <input v-model="formData.email" type="email" id="email"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-blue-500"
                                placeholder="email" required  />
                        </div>

                        <div class="flex flex-col text-gray-700 text-sm">
                            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Working since:</label>
                            <input v-model="formData.workingSince"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-blue-500"
                                type="date" id="date" />
                        </div>

                        <div class="ml-5 mb-4 mt-12 text-gray-700 text-sm font-bold flex ">Premia:
                            <label class="relative inline-flex items-center cursor-pointer ml-10">
                                <input v-model="formData.isPremia" type="checkbox" value="" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                                <span v-if="formData.isPremia" class="ms-3 text-sm font-medium text-gray-900 ">Yes</span>
                                <span v-else class="ms-3 text-sm font-medium text-gray-900 ">No</span>
                            </label>
                        </div>

                        <div class="ml-5 mb-4 mt-5 text-gray-700 text-sm font-bold flex ">Status:
                            <label class="relative inline-flex items-center cursor-pointer ml-11">
                                <input v-model="formData.isActive" type="checkbox" value="" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                                <span v-if="formData.isActive" class="ms-3 text-sm font-medium text-gray-900 ">Active</span>
                                <span v-else class="ms-3 text-sm font-medium text-gray-900 ">Not active</span>
                            </label>
                        </div>

                        <div class="ml-5 mb-4 mt-5 text-gray-700 text-sm font-bold flex ">Privileges:
                            <label class="relative inline-flex items-center cursor-pointer ml-6">
                                <input v-model="formData.isAdmin" type="checkbox" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                                <span v-if="formData.isAdmin" class="ms-3 text-sm font-medium text-gray-900 ">Admin</span>
                                <span v-else class="ms-3 text-sm font-medium text-gray-900 ">Regular</span>
                            </label>
                        </div>

                        <div class="flex justify-end mt-10">
                            <SecondaryButton @click.prevent="returnPage" class="mr-5">return</SecondaryButton>
                            <PrimaryButton type="submit">create</PrimaryButton>
                        </div>
                    </div>

                    <!-- Przycisk submit zajmuje obie kolumny -->


                </form>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useAttrs } from 'vue';


// const props = defineProps({

// })
const admin = ref(false)

const attrs = useAttrs()

const formData = useForm({
    id: '',
    name: '',
    firstname: '',
    lastname: '',
    email: '',
    workingSince: '',
    isPremia: false,
    isActive: true,
    isAdmin: false,
});

const submitForm = () => {
    if (confirm('Are you sure you want to create new user?')) {
        formData.post(route('user.createPost'));
    }
};


function returnPage() {
    if (window.confirm('Are you sure you want to discard the changes?')) {
        formData.reset();
        router.visit(route('users.list'));
    }

}


</script>




<style scoped></style>
