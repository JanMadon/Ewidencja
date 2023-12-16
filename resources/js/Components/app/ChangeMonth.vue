<template>
    <div>
        <SecondaryButton @click.prevent="moveMonth(-1)">Previous month</SecondaryButton>
        <span>{{ currentMonth }}</span>
        <SecondaryButton @click.prevent="moveMonth(1)">Next month</SecondaryButton>
    </div>
</template>



<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref, defineProps } from 'vue';


const currentDate = ref(new Date());
const setTime = ref('');

const emit = defineEmits();

const sendTimeToParent = () => {
    emit('period', setTime.value)
}

const formatDate = (date) => {
    prepereDateForBackend(date)
    const options = { month: 'long', year: 'numeric' };
    return new Intl.DateTimeFormat('en-US', options).format(date);
};

const currentMonth = ref(formatDate(currentDate.value));

const moveMonth = (number) => {
    currentDate.value.setMonth(currentDate.value.getMonth() + number);
    currentMonth.value = formatDate(currentDate.value);
};

function prepereDateForBackend(date) {
    let year = date.getFullYear();
    let month = ('0' + (date.getMonth() + 1)).slice(-2);
    let day = ('0' + date.getDate()).slice(-2);
    let hours = ('0' + date.getHours()).slice(-2);
    let minutes = ('0' + date.getMinutes()).slice(-2);
    let seconds = ('0' + date.getSeconds()).slice(-2);

    let secondDate = year + '-' + month + '-' + '01';

    setTime.value = secondDate;
    sendTimeToParent();
}


</script>