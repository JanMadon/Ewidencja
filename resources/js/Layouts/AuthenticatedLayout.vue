

<template>
    <!-- <div class="min-h-screen bg-gray-100"> -->
    <div class="h-screen bg-gray-50 flex w-full gap-0">
        <Navigation class="border-solid border-2 border-gren-600" />
        <!-- Page Heading -->
        <!-- Page Content -->
        <main class="flex flex-col flex-1 overflow-hidden">
            <nav class="flex items-center justify-end  w-full  border-b-2">

                <header class="  w-full text-center" v-if="$slots.header">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>
                <!-- Primary Navigation Menu -->
                <!-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 b:red"> -->
                <div class="relative inline-block text-left">
                    <div class="flex justify-between h-16">
                        <div class="flex">

                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <Dropdown class="pr-5">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-l leading-4 font-medium rounded-md text-gray-500 bg-indigo-100 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- <DropdownLink :href="route('profile.edit')">
                                            Profile
                                        </DropdownLink> -->
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="flex-1 flex flex-col overflow-hidden mx-5">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import Navigation from '@/Components/app/Navigation.vue';
// import NavTop from '@/Components/app/NavTop.vue';
// import UserSettingsDropdown from '@/Components/app/UserSettingsDropdown.vue'
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>
