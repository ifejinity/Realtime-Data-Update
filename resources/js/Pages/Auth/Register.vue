<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive, ref } from "vue";
let props = defineProps({
    users: Object
})
const myChart = ref();
let options = {
    chart: {
        height: 380,
        width: "100%",
        type: "area",
        animations: {
            initialAnimation: {
                enabled: false
            }
        }
    },
    xaxis: {
        type: "datetime"
    }
}
let series = reactive([
    {
        name: "Users",
        data: [
            ...props.users
        ]
    }
]);

const updateChart = (event) => {
    myChart.value.updateSeries ([
        {
            data: [
                ...event.user
            ]
        }
    ], true)
}

onMounted(() => {
    window.Echo.channel('user')
    .listen('.user-registered', (event) => {
        console.log('Event received:', event);
        updateChart(event);
    });
});
</script>

<template>
    <Head title="Users" />
    <div
        class="w-full flex justify-center items-center p-[24px] min-h-screen">
        <apexchart 
            ref="myChart"
            width="500" 
            type="area" 
            :options="options" 
            :series="series">
        </apexchart>
    </div>
</template>
