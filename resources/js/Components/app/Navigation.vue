<template>
    <nav class="w-[150px]">
        <div class="h-[80px] px-3 flex items-content gap-3">
            <Link href="/">
            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
            LaraStorage

            </Link>
        </div>
        <div class="px-3">
            <!-- <CreateNewDropdown /> -->
            <div class="py-3 ">
                <!-- <pre>{{$page}}</pre> -->
                <NavLink href = "route('myFiles')" :active="$page.props.route_name === 'myFiles'">My Files</NavLink>
                <NavLink href="route('file.sharedWithMe')" :active="$page.props.route_name === 'file.sharedWithMe'">Shered with me</NavLink>
                <NavLink href="route('file.sharedByMe')" :active="$page.props.route_name === 'file.sharedByMe'">Shered by me</NavLink>
                <NavLink href="route('trash')" :active="$page.props.route_name === 'trash'">Trash</NavLink>
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