<script setup>
    import { Chart, registerables } from "chart.js";
    import { LineChart } from "vue-chart-3";
    import { reactive } from "vue"

    Chart.register(...registerables);

    const lineData = reactive({
        labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20','21', '22', '23', '24', '25', '26', '27', '28', '29', '30'],
        datasets: [{
            label: 'B001_2_特定薬剤治療管理料1',
            data: ['3', , '8', , , , , , '12', , , , , , , , , , , '28', , , '46', , , , , , , ],
            backgroundColor: "rgb(75, 192, 192)",
            borderColor: "rgb(75, 192, 192)",
            tension: 0,
            spanGaps: true,
        }]
    })
</script>

<template>
    <LineChart :chartData="lineData" />
</template>
