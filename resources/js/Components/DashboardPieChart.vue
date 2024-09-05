<script setup>
/** ダッシュボード用円グラフ */
import { ref, onMounted } from 'vue'
import { Chart, registerables } from 'chart.js'
import { PieChart } from 'vue-chart-3'
import ChartDataLabels from 'chartjs-plugin-datalabels'

Chart.register(ChartDataLabels)
Chart.defaults.set('plugins.datalabels', {
    color: 'black',
    font: {
        size: 14,
    },
    formatter: (value, ctx) => {
        let label = ctx.chart.data.labels[ctx.dataIndex]
        return label + '\n' + value + '%'
    },
})
Chart.register(...registerables)
const chartRef = ref()

const chartData = ref({
    labels: ['内科', '外科', '小児科'],
    datasets: [
        {
            label: 'pie chart',
            data: [60, 30, 10],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
            ],
            hoverOffset: 10,
        },
    ],
})
</script>
<template>
    <div class="chart-wrapper">
        <PieChart ref="chartRef" :chartData="chartData" />
    </div>
</template>
<style scoped>
.chart-wrapper {
    margin-top: 30px;
    max-height: 100%;
    height: 90%;
    width: 100%;
}
</style>
