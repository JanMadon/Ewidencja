<template>
    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 bm-3">
            
        </nav>
        <div class="flex-1 overflow-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-[30px] max-w-[30px] pr-0">
                            <Checkbox />
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            ID
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Name 
                        </th>
                        <th  class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Firstname
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Lastname
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Email
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Right
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Status
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Opcje
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-[30px] max-w-[30px] pr-0">
                            <Checkbox />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-yellow-500" >
                            ####
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center">
                            Jacek
                        </td>
                        <td  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            aaa
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            bbb
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            ccc
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">
                            ddd
                        </td>
                    </tr>
                </tbody>
            </table>
            <div  class="py-8 text-center text-sm text-gray-400">
                There is no data in this folder
            </div>
            <div ref="loadMoreIntersect"></div>
        </div>
    </AuthenticatedLayout>

</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>