<script setup>
import { onMounted, ref } from 'vue'
import { Chart, registerables } from 'chart.js'
import { LineChart } from 'vue-chart-3'

/**
 * 入院外来治診療合計金額チャート
 */
const { outPatientCosts } = defineProps({
    outPatientCosts: Array,
})

onMounted(() => {
    console.log('cost chart')

    console.log(outPatientCosts)
})

Chart.register(...registerables)
const chartRef = ref()

const chartData = ref({
    labels: [
        '2022-05-28',
        '2022-07-20',
        '2022-08-01',
        '2022-08-22',
        '2022-08-25',
    ],
    datasets: [
        {
            label: '外来',
            backgroundColor: '#98fb98',
            borderColor: '#66cdaa',
            data: [4030, 21250, 14500, 780, 9150],
            tension: 0,
            spanGaps: false,
        },
    ],
})
</script>

<template>
    <v-container>
        <LineChart ref="chartRef" :chartData="chartData" />
    </v-container>
</template>
