<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted, watch } from 'vue'
import { Chart, registerables } from 'chart.js'
import { LineChart } from 'vue-chart-3'

import AppLayout from '@/Layouts/AppLayout.vue'
import CalcSituationTable from '@/Components/MyComponents/CalcSituationTable.vue'
import TopReservationList from '@/Components/MyComponents/TopReservationList.vue'
import TopInpatientList from '@/Components/MyComponents/TopInpatientList.vue'

// controllerから渡ってくる
const {
    borderMoney, // 逆紹介ボーダー金額
    scenarios,
    extractingDate, // 最新更新日 2022-08-25
    startYearDate, // 今年の開始日 2021-01-01 月次年間の検索に使う
    startSubYearDate, // 1年前の同日 月次過去1年間の検索に使う 2021-08-25
    monthDateLabels, // 今月の日付配列
    thirtyDateLabels, // 過去30日の日付配列
    yearMonthLabels, // 今年の月配列
    subYearMonthLabels, // 過去1年の月配列
} = defineProps({
    borderMoney: Number,
    scenarios: Array,
    extractingDate: String,
    startYearDate: String,
    startSubYearDate: String,
    monthDateLabels: Array,
    thirtyDateLabels: Array,
    yearMonthLabels: Array,
    subYearMonthLabels: Array,
})

onMounted(() => {
    // console.log('onMounted')

    // シナリオマスタ取得
    scenarios.forEach((scenario, i) => {
        scenarios[i].label =
            scenario.scenario_control_sysid + ':' + scenario.display_name
    })

    // 日付
    fromDate.value = monthDateLabels[0]
    toDate.value = extractingDate

    chartLabels.value = monthDateLabels
    limitedRangeDateLabels.value = chartLabels.value

    // console.log(startYearDate)
    // console.log(startSubYearDate)

    // sliderValues
    sliderValueMax.value = chartLabels.value.length - 1
    sliderValues.value[1] = sliderValueMax.value

    // データ取得
    getCalcChartData()
    getCalcSituationData()
    getReservationData()
    getInpatientData()
})

// 検索
const clickSearchButton = () => {
    if (termType.value === 0) {
        getCalcChartData()
    }
    if (termType.value === 1) {
        getCalcChartTermMonthData()
    }
    getCalcSituationData()
    getReservationData()
    getInpatientData()
}

// 日付
const fromDate = ref('') // 検索用開始日、終了日
const toDate = ref('')
const labels = ref([])

const doctors = [
    { id: '001238', name: 'テスト医師1238' },
    { id: '001150', name: 'doctor_001150' },
]

// 検索条件form params
const form = reactive({
    doctors: [],
    scenarios: ['91401', '91402', '91405', '91408', '91416'],
})

/**
 * 算定状況Chart（算定実績チャート、算定チャート）
 */
Chart.register(...registerables)
const chartRef = ref()
// ChartOption
const chartOptions = ref({})

// color
const color = [
    '#36A2EB',
    '#FF6384',
    '#4BC0C0',
    '#FF9F04',
    '#9966FF',
    '#FFCD56',
    '#C9CBCF',
]

// 期間の種類 0:日次 1:月次
const termType = ref(0)
watch(termType, () => {
    // 日次
    if (termType.value === 0) {
        if (dateType.value === 0) {
            // 今月
            chartLabels.value = monthDateLabels
            fromDate.value = monthDateLabels[0]
        }
        if (dateType.value === 1) {
            // 過去30日
            chartLabels.value = thirtyDateLabels
            fromDate.value = thirtyDateLabels[0]
        }

        getCalcChartData()
    }
    // 月次
    if (termType.value === 1) {
        if (monthType.value === 0) {
            // 今年 2022-01-01~2022-08-25
            chartLabels.value = yearMonthLabels
            fromDate.value = startYearDate
            labels.value = yearMonthLabels
        }
        if (monthType.value === 1) {
            // 過去1年 // 2022-08-25→2021-08-25~2022-08-25
            chartLabels.value = subYearMonthLabels
            fromDate.value = startSubYearDate
            labels.value = subYearMonthLabels
        }

        getCalcChartTermMonthData()
    }

    // labelスライダーを戻す
    limitedRangeDateLabels.value = chartLabels.value
    startRangeIndex.value = 0
    sliderValueMax.value = chartLabels.value.length - 1
    sliderValues.value = [0, sliderValueMax.value]
})

// 集計範囲　0:今月 1:過去30日
const dateType = ref(0)
watch(dateType, () => {
    // データ対象範囲を変更する
    if (dateType.value === 0) {
        fromDate.value = monthDateLabels[0]
        chartLabels.value = monthDateLabels
    }
    if (dateType.value === 1) {
        fromDate.value = thirtyDateLabels[0]
        chartLabels.value = thirtyDateLabels
    }

    // labelスライダーを戻す
    limitedRangeDateLabels.value = chartLabels.value
    startRangeIndex.value = 0
    sliderValueMax.value = chartLabels.value.length - 1
    sliderValues.value = [0, sliderValueMax.value]

    // 対象範囲で算定チャートデータを取りに行く
    getCalcChartData()
})

// 月次集計範囲 0:今年 1:過去1年
const monthType = ref(0)
watch(monthType, () => {
    if (monthType.value === 0) {
        chartLabels.value = yearMonthLabels
        fromDate.value = startYearDate
        labels.value = yearMonthLabels
    }
    if (monthType.value === 1) {
        chartLabels.value = subYearMonthLabels
        fromDate.value = startSubYearDate
        labels.value = subYearMonthLabels
    }

    getCalcChartTermMonthData()

    // labelスライダーを戻す
    limitedRangeDateLabels.value = chartLabels.value
    startRangeIndex.value = 0
    sliderValueMax.value = chartLabels.value.length - 1
    sliderValues.value = [0, sliderValueMax.value]
})

// 累計トグル 0:累計 1:実績
const cumulativeType = ref([0])
watch(cumulativeType, () => {
    updateCalcChart() // 累計表示か実績表示か切り替えられたらチャートを更新する
})

const chartLabels = ref([]) // 対象日付配列

// labelスライダー
const sliderValues = ref([0, 10])
const sliderValueMax = ref(10)
const sliderLabel = (index) => {
    return chartLabels.value[index]
}
const limitedRangeDateLabels = ref([]) // chartLabelsからsliderで限定された日付配列
const startRangeIndex = ref() // startが何番ずれたか
const sliderEnd = (limitedRangeIndexs) => {
    limitedRangeDateLabels.value = chartLabels.value.slice(
        limitedRangeIndexs[0],
        limitedRangeIndexs[1] + 1
    )
    startRangeIndex.value = limitedRangeIndexs[0]
    // console.log(startRangeIndex.value)
    // console.log(limitedRangeDateLabels.value)
    updateCalcChart()
}

// Chart初期値
const lineData = ref({
    labels: limitedRangeDateLabels.value,
    datasets: [
        {
            label: '',
            data: [],
            tension: 0,
            spanGaps: true,
        },
    ],
})

// 算定チャート用データ
const calcData = reactive({
    scenarios: [],
    data: [],
})

// 日次算定チャートデータ取得
const getCalcChartData = async () => {
    try {
        await axios
            .get('/api/calc-chart-data', {
                params: {
                    scenarios: form.scenarios,
                    fromDate: fromDate.value,
                    toDate: toDate.value,
                },
            })
            .then((res) => {
                calcData.scenarios = form.scenarios
                calcData.data = res.data.data
                // console.log('api get calcChartData')
                // console.log(calcData)
                updateCalcChart() // データを取得したらチャートを更新
            })
    } catch (e) {
        console.log(e)
    }
}

// 月次算定チャートデータ取得
const getCalcChartTermMonthData = async () => {
    try {
        await axios
            .get('/api/calc-chart-term-month-data', {
                params: {
                    scenarios: form.scenarios,
                    fromDate: fromDate.value,
                    toDate: toDate.value,
                    monthLabels: labels.value,
                },
            })
            .then((res) => {
                calcData.scenarios = form.scenarios
                calcData.data = res.data.data
                // console.log('api get TermMonthCalcChartData')
                // console.log(calcData)
                updateCalcChart()
            })
    } catch (e) {
        console.log(e)
    }
}

// 算定チャート更新処理
const datasets = ref([])
const updateCalcChart = () => {
    // console.log('update chart')
    datasets.value = []
    calcData.scenarios.forEach((scenario, i) => {
        // console.log(scenario)
        // console.log(calcData.data[scenario])

        // 累計トグルで累計か実績データを入れる
        let data = []
        if (cumulativeType.value == 0) {
            data = calcData.data[scenario].ruikeiData.slice(
                startRangeIndex.value
            )
        } else {
            data = calcData.data[scenario].jissekiData.slice(
                startRangeIndex.value
            )
        }

        datasets.value.push({
            label: calcData.data[scenario].label,
            data: data,
            backgroundColor: color[i],
            borderColor: color[i],
        })
    })

    // datasetsを直接（chartRef.value.chartInstanceを用いるということ）更新しないとdatasetが元より減った時に対応できなかった
    // https://vue-chart-3.netlify.app/guide/usage/chart-instance.html
    chartRef.value.chartInstance.data.labels = limitedRangeDateLabels.value
    chartRef.value.chartInstance.data.datasets = datasets.value

    chartRef.value.update()
}

/**
 * 算定状況テーブルデータ取得
 */
const calcSituationData = reactive({})
const getCalcSituationData = async () => {
    try {
        await axios
            .get('/api/calc-situation-data', {
                params: {
                    scenarios: form.scenarios,
                    fromDate: monthDateLabels[0], // 今月の開始
                    toDate: extractingDate, // extractingDate
                },
            })
            .then((res) => {
                // console.log('api calc situation data')
                // console.log(res)
                calcSituationData.data = res.data.data
            })
    } catch (e) {
        console.log(e)
    }
}

/**
 * 外来予約リスト取得
 */
const reservationData = reactive({})
const getReservationData = async () => {
    try {
        await axios
            .get('/api/reservations', {
                params: {
                    doctors: form.doctors,
                    scene: 'top',
                    extractingDate: extractingDate,
                    // TODO 期間 当日～未来30日間？
                },
            })
            .then((res) => {
                reservationData.data = res.data.data
                // console.log('api get reservationData')
                // console.log(reservationData.data)
            })
    } catch (e) {
        console.log(e)
    }
}

/**
 * 在院患者リスト取得
 */
const inpatientData = reactive({})
const getInpatientData = async () => {
    try {
        await axios
            .get('/api/inpatients', {
                params: {
                    doctors: form.doctors,
                    scene: 'top',
                    extractingDate: extractingDate,
                    // TODO 期間　当日～過去30日間？
                },
            })
            .then((res) => {
                inpatientData.data = res.data.data
                // console.log('api get inPatientData')
                // console.log(inpatientData.data)
            })
    } catch (e) {
        console.log(e)
    }
}
</script>

<template>
    <Head title="Top" />
    <AppLayout>
        <!--検索-->
        <v-container class="mt-4 mb-4">
            <div class="text-h6">検索条件</div>
            <v-row>
                <v-col>
                    <v-select
                        clearable
                        label="医師"
                        v-model="form.doctors"
                        :items="doctors"
                        item-title="name"
                        item-value="id"
                        multiple
                    ></v-select>
                </v-col>
                <v-col>
                    <v-select
                        clearable
                        label="シナリオ"
                        v-model="form.scenarios"
                        :items="scenarios"
                        item-title="label"
                        item-value="scenario_control_sysid"
                        multiple
                    >
                    </v-select>
                </v-col>
            </v-row>

            <v-btn elevation="2" @click="clickSearchButton">検索</v-btn>
        </v-container>

        <!--算定状況-->
        <v-container>
            <div class="text-h5">算定状況</div>
        </v-container>
        <v-container>
            <v-row align="center" justify="center">
                <v-col class="text-center" cols="auto">
                    <v-btn-toggle
                        v-model="termType"
                        color="primary"
                        variant="outlined"
                    >
                        <v-btn :class="{ 'disable-events': termType == 0 }">
                            日次
                        </v-btn>
                        <v-btn :class="{ 'disable-events': termType == 1 }">
                            月次</v-btn
                        >
                    </v-btn-toggle>
                </v-col>
                <template v-if="termType == 0">
                    <v-col class="text-center" cols="auto">
                        <v-btn-toggle
                            v-model="dateType"
                            color="primary"
                            variant="outlined"
                        >
                            <v-btn
                                class="w-28"
                                :class="{ 'disable-events': dateType == 0 }"
                            >
                                今月
                            </v-btn>
                            <v-btn
                                class="w-28"
                                :class="{ 'disable-events': dateType == 1 }"
                            >
                                過去30日
                            </v-btn>
                        </v-btn-toggle>
                    </v-col>
                </template>
                <template v-if="termType == 1">
                    <v-col class="text-center" cols="auto">
                        <v-btn-toggle
                            v-model="monthType"
                            color="primary"
                            variant="outlined"
                        >
                            <v-btn
                                class="w-28"
                                :class="{ 'disable-events': monthType == 0 }"
                            >
                                今年度
                            </v-btn>
                            <v-btn
                                class="w-28"
                                :class="{ 'disable-events': monthType == 1 }"
                            >
                                過去1年
                            </v-btn>
                        </v-btn-toggle>
                    </v-col>
                </template>
                <v-col class="text-center" cols="auto">
                    <v-btn-toggle
                        v-model="cumulativeType"
                        color="primary"
                        variant="outlined"
                    >
                        <v-btn
                            :class="{ 'disable-events': cumulativeType == 0 }"
                        >
                            累計
                        </v-btn>
                        <v-btn
                            :class="{ 'disable-events': cumulativeType == 1 }"
                        >
                            実績
                        </v-btn>
                    </v-btn-toggle>
                </v-col>
            </v-row>
        </v-container>

        <v-container>
            <LineChart
                ref="chartRef"
                :chartData="lineData"
                :options="chartOptions"
            />
        </v-container>

        <v-container>
            <v-range-slider
                color="primary"
                :model-value="sliderValues"
                :step="1"
                :min="0"
                :max="sliderValueMax"
                thumb-label
                @end="sliderEnd"
            >
                <template v-slot:thumb-label="{ modelValue }">
                    <div class="text-center w-20">
                        {{ sliderLabel(modelValue) }}
                    </div>
                </template>
            </v-range-slider>
        </v-container>

        <!--算定状況テーブル-->
        <v-container>
            <CalcSituationTable :data="calcSituationData.data" />
        </v-container>

        <!--Top外来予約リスト-->
        <v-container>
            <TopReservationList
                :data="reservationData.data"
                :border-money="borderMoney"
            />
        </v-container>

        <!--Top在院患者リスト-->
        <v-container>
            <TopInpatientList :data="inpatientData.data" />
        </v-container>
    </AppLayout>
</template>

<style>
.disable-events {
    pointer-events: none;
}
</style>
