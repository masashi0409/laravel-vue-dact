<script setup>
    import { Chart, registerables } from "chart.js";
    import { LineChart } from "vue-chart-3";
    import { ref, reactive, watch, onBeforeUpdate, onBeforeMount, onUpdated } from "vue"

    Chart.register(...registerables);
    
    const chartRef = ref();

    const { calcData, text } = defineProps({
        calcData: Object,
        text: String
    })
    
    // watch(() => data, () => {
    //     console.log('watch calcData')
    //     console.log(data)
    // })
    
    // templateで更新がないと
    // onBeforeMount(() => {
    //     console.log('onBeforeMount')
    //     console.log(text)
    // })
    
    
    const datasets = ref([]);
    
    const color = [
        {
            backgroundColor: "rgb(75, 192, 192)",
            borderColor: "rgb(75, 192, 192)",
        },
        {
            backgroundColor: "#FFB266",
            borderColor: "#FFB266",
        }
    ]
    
    onBeforeUpdate(() => {
        console.log('onBeforeUpdated')
        console.log(calcData.data)
        console.log(calcData.scenarios)
        
        datasets.value = []
        
        calcData.scenarios.forEach((scenario, i) => {
            console.log(scenario)
            console.log(calcData.data[scenario])
            datasets.value.push({
                label: calcData.data[scenario].label,
                data: calcData.data[scenario].data,
                backgroundColor: color[i]['backgroundColor'],
                borderColor: color[i]['borderColor'],
            })
        })
        
        lineData.value = {
            labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20','21', '22', '23', '24', '25', '26', '27', '28', '29', '30'],
            datasets: datasets.value
        }
        
        chartRef.value.update();
    })
    
    watch(() => text, () => {
        console.log('watch text')
        console.log(text)
    })
    
    const lineData = ref({
        labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20','21', '22', '23', '24', '25', '26', '27', '28', '29', '30'],
        datasets: [
            {
                // label: 'B001_2_特定薬剤治療管理料1',
                label: '',
                // data: ['3', , '8', , , , , , '12', , , , , , , , , , , '28', , , '46', , , , , , , ],
                data: [],
                backgroundColor: "rgb(75, 192, 192)",
                borderColor: "rgb(75, 192, 192)",
                tension: 0,
                spanGaps: true,
            }
        ]
    })
</script>

<template>
    <LineChart ref="chartRef" :chartData="lineData" />
</template>
