<template>
    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 bm-3">
            <p>
                <ChangeMonth @period="dateFromChangeMontchComponent" />
            </p>
            <p>nawigacja</p>
        </nav>
        <div class="overflow-x-auto">

            <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-md shadow-md overflow-x-auto">
                <h2 class="text-2xl font-semibold mb-6">Employee: {{ user.name }}</h2>
                <h4 class="text-xl font-semibold mb-6">Id: {{ user.id }} </h4>


                <form @submit.prevent="submitForm()" class="flex flex-col">
                    <table class="min-w-full w-[900]">
                        <thead class="bg-gray-400 text-center border">
                            <tr>
                                <th colspan="2" class="text-l font-medium px-6 py-4 text-center border-2">
                                    Summary - [Month]
                                    <p v-if="data.errors" class="text-sm text-red-600">User has {{ data.errors }} error(s) in
                                        the selected period</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    Hours worked
                                </td>
                                <td class="text-center">
                                    {{ data.workTime }}
                                </td>
                            </tr>
                            <tr class="bg-gray-200">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    Double hours
                                </td>
                                <td class="text-center">
                                    {{ data.doubleHours }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    Standard working time
                                </td>
                                <td class="w-full text-center">
                                    <input v-if="selectedOption === 'custom'" v-model="customOption" placeholder="set value"
                                        class="w-[30%] text-center">
                                    <select v-else v-model="selectedOption" id="mySelector" disabled class="border-none cursor-pointer">
                                        <option v-for="(option, index) in options" :key="index" :value="option">
                                            {{ option }}
                                        </option>
                                        <option :value="selectedOption" v-html="selectedOption"></option>
                                        <option value="custom" v-if="allowCustomOption">set</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="bg-gray-200">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    Salary 
                                    <p class="text-xs">valid from</p>
                                </td>
                                <td class="text-center">
                                    {{ data.salary }}
                                    <p class="text-xs">{{ data.salaryVaildFrom }}</p>
                                </td>
                            </tr>
                            <tr class="border">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    Net payment
                                </td>
                                <td class="text-center font-bold">
                                    {{ payment }}
                                </td>
                            </tr>
                        </tbody>

                    </table>
                    <!-- Przycisk submit zajmuje obie kolumny -->
                    <div>
                        <SecondaryButton @click.prevent="returnPage" class="mr-5 mt-5">return</SecondaryButton>
                        <!-- <PrimaryButton type="submit">save</PrimaryButton> -->
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import ChangeMonth from '@/Components/app/ChangeMonth.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { onMounted } from 'vue';
import { watch } from 'vue';


const options = ref([144, 152, 160, 168, 176, 184,]);
const selectedOption = ref();
const customOption = ref('');
const allowCustomOption = ref(true);
const payment = ref(0);
const salatyInput = ref(false);
const newSalaty = ref(0)
const newSalatyValidFrom = ref('')

const props = defineProps({
    user: Object,
    data: Object,
})


function showSalaryInput() {
 salatyInput.value = true;
}
watch(() => [props.data.workTime, selectedOption.value, customOption.value], countSalary)

function setNewSalary() {
    if( confirm(`Should I set a new salary ${newSalaty.value} PLN from ${newSalatyValidFrom.value}?`) ){
        router.put(route('user.bill.setSalary', [props.user.id]), {
            'newSalry': newSalaty.value,
            'validFrom': newSalatyValidFrom.value
        })
    console.log('ustaw nową stawkę')
    }
    newSalaty.value = 0
    newSalatyValidFrom.value = 0 
    salatyInput.value = false
}

function countSalary() {
    if (props.data.workTime) {
        const [hours, minutes, seconds] = props.data.workTime.split(':');
        const totalHours = parseInt(hours) + parseInt(minutes) / 60 + parseInt(seconds) / 3600;
        const plusDoubleHours = totalHours + props.data.doubleHours;
        if (selectedOption.value === 'custom') {
            const partOfPayment = plusDoubleHours / customOption.value
            payment.value = (props.data.salary * partOfPayment).toFixed(2)
        } else {
            const partOfPayment = plusDoubleHours / selectedOption.value
            payment.value = (props.data.salary * partOfPayment).toFixed(2)
        }
    }

}

function setOptionYaer(period) {

    const year = period.slice(0, 4)
    const month = period.slice(5, 7)
    let monthValue = []

    switch (year) {
        case '2023': // sprzwdz godziny dla 2023
            monthValue = [168, 168, 168, 168, 160, 160, 184, 168, 168, 184, 152, 160]
            break;
        case '2024':
            monthValue = [168, 168, 168, 168, 160, 160, 184, 168, 168, 184, 152, 160]
            break;
        case '2025':
            monthValue = [168, 160, 168, 168, 160, 160, 184, 160, 176, 184, 144, 168]
            break;
        case '2026':
            monthValue = [160, 160, 176, 168, 160, 168, 184, 160, 176, 176, 160, 168]
            break;
        default:
            monthValue = []
            break;
    }

    if (monthValue.length) {
        selectedOption.value = monthValue[month - 1];
    } else {
        selectedOption.value = 'no data'
    }

}

function returnPage() {
        router.visit(route('users.list') );
}

const dateFromChangeMontchComponent = (period) => {
    setOptionYaer(period)
    router.post(route('dashboardUser.period'), { 'date': period });
}

</script>




<style scoped></style>