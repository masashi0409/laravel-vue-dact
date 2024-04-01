<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted, watch } from 'vue'
import { Chart, registerables } from 'chart.js'
import { LineChart } from 'vue-chart-3'

import { convertArrayToString } from '@/common'
import AppLayout from '@/Layouts/AppLayout.vue'
import CalcSituationTable from '@/Components/MyComponents/CalcSituationTable.vue'
import TopReservationList from '@/Components/MyComponents/TopReservationList.vue'
import TopInpatientList from '@/Components/MyComponents/TopInpatientList.vue'
import SelectScenarioDialog from '@/Components/MyComponents/SelectScenarioDialog.vue'
import InitialSearchConditionDialog from '@/Components/MyComponents/InitialSearchConditionDialog.vue'

// controllerから渡ってくる
const {
    borderMoney, // 逆紹介ボーダー金額
    masterScenarios,
    searchConditionScenario, // 初期検索条件算定シナリオ
    unSearchConditionScenario,
    extractingDate, // 最新更新日 2022-08-25
    prevDate,
    prevMonthDate, // 先月同日 2022-07-25
    startYearDate, // 今年の開始日 2021-01-01 月次年間の検索に使う
    startSubYearDate, // 1年前の同日 月次過去1年間の検索に使う 2021-08-25
    monthDateLabels, // 今月の日付配列
    thirtyDateLabels, // 過去30日の日付配列
    yearMonthLabels, // 今年の月配列
    subYearMonthLabels, // 過去1年の月配列
} = defineProps({
    borderMoney: Number,
    masterScenarios: Array,
    searchConditionScenario: Array,
    unSearchConditionScenario: Array,
    extractingDate: String,
    prevDate: String,
    prevMonthDate: String,
    startYearDate: String,
    startSubYearDate: String,
    monthDateLabels: Array,
    thirtyDateLabels: Array,
    yearMonthLabels: Array,
    subYearMonthLabels: Array,
})

onMounted(() => {
    // console.log('App onMounted')

    // 検索初期条件を検索条件に設定
    form.scenarios = searchConditionScenario.map((i) => i['id'])

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

// 検索条件
const searchScenario = ref(searchConditionScenario) // 算定シナリオ検索条件
const unSearchScenario = ref(unSearchConditionScenario)
// シナリオダイアログsubmit
const onScenarioSubmit = (editingSearchScenario) => {
    console.log(masterScenarios)
    console.log(searchScenario.value)
    console.log(editingSearchScenario)

    senarioDialogFlg.value = false

    // searchScenario、unsearchScenario作成しなおし
    searchScenario.value = []
    unSearchScenario.value = []
    masterScenarios.forEach((scenario) => {
        if (editingSearchScenario.includes(scenario.scenario_control_sysid)) {
            searchScenario.value.push({
                id: scenario.scenario_control_sysid,
                name: scenario.display_name,
                color: scenario.color_code,
            })
        } else {
            unSearchScenario.value.push({
                id: scenario.scenario_control_sysid,
                name: scenario.display_name,
                color: scenario.color_code,
            })
        }
    })

    form.scenarios = editingSearchScenario
}
const cancelScenarioDialog = () => {
    senarioDialogFlg.value = false
}

/** 検索ボタン */
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

/**
 * 検索ダイアログ
 */
const senarioDialogFlg = ref(false)

/**
 * 検索条件のparams
 */
const form = reactive({
    doctors: [], // 医師ID
    scenarios: [], // 算定シナリオID
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
        console.log(calcData.data[scenario])

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
            backgroundColor: '#' + calcData.data[scenario].color,
            borderColor: '#' + calcData.data[scenario].color,
        })
    })

    // datasetsを直接（chartRef.value.chartInstanceを用いるということ）更新しないとdatasetが元より減った時に対応できなかった
    // https://vue-chart-3.netlify.app/guide/usage/chart-instance.html
    chartRef.value.chartInstance.data.labels = limitedRangeDateLabels.value
    chartRef.value.chartInstance.data.datasets = datasets.value

    chartRef.value.update()
}

/**
 * 算定状況（当月状況）データ取得
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
                    prevDate: prevDate,
                    prevMonthDate: prevMonthDate,
                    prevYearDate: startSubYearDate,
                },
            })
            .then((res) => {
                console.log('api calc situation data')
                console.log(res)
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

const initialSearchConditionDialogFlg = ref(false)
</script>

<template>
    <Head title="Top" />
    <AppLayout>
        <!--検索-->
        <v-container class="mt-4 mb-4 search-container">
            <div class="text-h6">検索条件</div>

            <v-btn
                density="compact"
                icon="mdi-cog"
                @click="initialSearchConditionDialogFlg = true"
            ></v-btn>
            <v-dialog v-model="initialSearchConditionDialogFlg">
                <InitialSearchConditionDialog
                    :searchConditionScenario="searchScenario"
                    :unSearchConditionScenario="unSearchScenario"
                    @clickCancel="initialSearchConditionDialogFlg = false"
                />
            </v-dialog>

            <v-row class="mb-4">
                <v-col>
                    <div>医師</div>
                    <v-sheet class="top-condition-value"> test </v-sheet>
                </v-col>
                <v-col>
                    <div>診療科</div>
                    <v-sheet class="top-condition-value"> test </v-sheet>
                </v-col>
                <v-col>
                    <div>病棟</div>
                    <v-sheet class="top-condition-value"> test </v-sheet>
                </v-col>
                <v-col>
                    <div>算定シナリオ</div>
                    <v-sheet
                        class="top-condition-value"
                        @click.stop="senarioDialogFlg = true"
                    >
                        <template v-if="searchScenario.length > 0">
                            {{ convertArrayToString(searchScenario) }}
                        </template>
                        <template v-if="searchScenario.length === 0">
                            すべての算定シナリオ
                        </template>
                        <template v-if="searchScenario.length > 0">
                            <v-tooltip activator="parent">
                                {{ convertArrayToString(searchScenario) }}
                            </v-tooltip>
                        </template>
                    </v-sheet>
                </v-col>
            </v-row>

            <div class="d-flex">
                <v-spacer></v-spacer>
                <v-btn elevation="2" color="primary" @click="clickSearchButton"
                    >検索</v-btn
                >
            </div>
        </v-container>

        <!-- シナリオダイアログ -->
        <v-dialog v-model="senarioDialogFlg">
            <SelectScenarioDialog
                :masterScenarios="masterScenarios"
                :editSearchScenario="searchScenario"
                @clickSubmit="onScenarioSubmit"
                @clickCancel="cancelScenarioDialog"
            />
        </v-dialog>

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

<style scope>
.disable-events {
    pointer-events: none;
}

.search-container {
    border: 1px solid #aaaaaa;
}

.top-condition-value {
    max-width: 300px;
    border: 1px solid #aaaaaa;
    text-align: center;
    padding: 2px;
    overflow: hidden; /* オーバーした要素を非表示にする*/
    white-space: nowrap; /* 改行を半角スペースに変換することで、1行にする */
    text-overflow: ellipsis; /* オーバーしたテキストを３点リーダーにする */
}
</style>
