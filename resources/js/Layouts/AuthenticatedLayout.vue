

<template>
    <!-- <div class="min-h-screen bg-gray-100"> -->
    <div class="h-screen bg-gray-50 flex w-full gap-4">
        <Navigation class="border-solid border-2 border-gren-500" />
        <!-- Page Heading -->
        <!-- Page Content -->
        <main class="flex flex-col flex-1 px-4 overflow-hidden">
{{ $slots.header }}
            <NavTop $slots.header = 'a'/>

            <div class="flex-1 flex flex-col overflow-hidden">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import Navigation from '@/Components/app/Navigation.vue';
import NavTop from '@/Components/app/NavTop.vue';
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
