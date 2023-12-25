<template>
    <nav class="w-[200px]">
        <div class="h-[80px] px-3 flex justify-center gap-3">
            <Link :href="route('dashboard')" class="flex flex-col items-center">
                <ApplicationLogo class="block w-auto fill-current text-gray-800" />
               <p> Ewidecja </p>
            </Link>
        </div>
        <div class="px-3">
            <!-- <CreateNewDropdown /> -->
            <div v-if="$page.props.auth.user.is_admin" class="py-3 ">
                <!-- <pre>{{$page.props.auth.user.is_admin}}</pre> -->
                <!-- <NavLink :href="route('dashboard')" :active="$page.props.route_name === 'dashboard'">Dashboard</NavLink> -->
                <NavLink :href="route('dashboardAdmin')" :active="$page.props.route_name === 'dashboardAdmin'">Admin Dashboard</NavLink>
                <NavLink :href="route('users.list')" :active="$page.props.route_name === 'users.list'">Employees</NavLink>
                <NavLink :href="route('users.requests')" :active="$page.props.route_name === 'users.requests'">Requests</NavLink>
                <NavLink :href="route('log.list')" :active="$page.props.route_name === 'log.list'">Log List</NavLink>
                <NavLink :href="route('users.trash')" :active="$page.props.route_name === 'users.trash'">Trash</NavLink>
                <NavLink :href="route('users.hostory')" :active="$page.props.route_name === 'users.hostory'">History</NavLink>

            </div>
            <!-- <div v-else class="py-3 "> -->
            <div class="py-3 ">
                <NavLink :href="route('dashboardUser')" :active="$page.props.route_name === 'dashboardUser.period'">
                    User Dashboard
                </NavLink>
                <NavLink :href="route('my.logs')" :active="$page.props.route_name === 'my.logs.period'">My logs</NavLink>
                <NavLink :href="route('my.requests')" :active="$page.props.route_name === 'my.requests'">My requests</NavLink>
                <NavLink :href="route('my.trash')" :active="$page.props.route_name === 'my.trash'">Trash</NavLink>
            </div>

        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
//import CreateNewDropdown from '@/Components/app/CreateNewDropdown.vue';
import NavLink from '@/Components/NavLink.vue';




</script>
