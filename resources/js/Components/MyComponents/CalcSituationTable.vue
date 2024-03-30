<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
    data: Array,
})

const headers = [
    {
        title: 'シナリオ名',
        align: 'start',
        key: 'display_name',
        width: 500,
    },
    {
        title: '算定実績（前日比）',
        align: 'center',
        key: 'calc_archive_count',
        width: 150,
    },
    {
        title: '算定件数(前日比)',
        align: 'center',
        key: 'calc_count',
        width: 150,
    },
    {
        title: '未算定件数(前日比)',
        align: 'center',
        key: 'uncalc_count',
        width: 150,
    },
    {
        title: '算定率',
        align: 'center',
        key: 'calc_ratio',
        width: 150,
        sort: (a, b) => {
            // 単位がついているものをソートする
            a = a.replace(/[^0-9]/g, '')
            b = b.replace(/[^0-9]/g, '')
            return a - b
        },
    },
    {
        title: '先月比',
        align: 'center',
        key: 'diff_prev_month_calc_ratio',
        width: 150,
        sortable: false, // TODO ソートするならマイナスと単位がついているものをソートするロジック必要
    },
    {
        title: '前年同日比',
        align: 'center',
        key: 'diff_prev_year_calc_ratio',
        width: 150,
        sortable: false, // TODO ソートするならマイナスと単位がついているものをソートするロジック必要
    },
]

const calcSituationDatas = computed(() => props.data)
const tableDatas = ref([])
watch(calcSituationDatas, () => {
    tableDatas.value = []
    calcSituationDatas.value.forEach((d) => {
        tableDatas.value.push({
            display_name: d.display_name,
            calc_archive_count: `${d.calc_archive_count} (+${d.diffPrevArchiveCount}件)`,
            calc_count: `${d.calc_count} (${d.diffPrevCalcCount})`,
            uncalc_count: `${d.uncalc_count} (${d.diffPrevUncalcCount})`,
            calc_ratio: d.calc_ratio,
            diff_prev_month_calc_ratio: d.diffPrevMonthCalcRatio,
            diff_prev_year_calc_ratio: d.diffPrevYearCalcRatio,
        })
    })
})

const getColor = (value) => {
    if (value > 0) {
        return 'text-blue-darken-4'
    } else if (value < 0) {
        return 'text-orange-darken-4'
    } else if (value === 0) {
        return
    }
}
</script>

<template>
    <div class="text-h5 mb-4">当月状況</div>
    <div class="mb-4">
        <v-data-table
            :headers="headers"
            :items="tableDatas"
            class="calc-situation-table"
        >
            <template v-slot:item.diff_prev_month_calc_ratio="{ value }">
                <v-sheet :class="getColor(value)">
                    <template v-if="value > 0"> +{{ value }}% </template>
                    <template v-if="value < 0"> {{ value }}% </template>
                    <template v-if="value === 0"> {{ value }}% </template>
                </v-sheet>
            </template>
            <template v-slot:item.diff_prev_year_calc_ratio="{ value }">
                <v-sheet :class="getColor(value)">
                    <template v-if="value > 0"> +{{ value }}% </template>
                    <template v-if="value < 0"> {{ value }}% </template>
                    <template v-if="value === 0"> {{ value }}% </template>
                </v-sheet>
            </template>
            <template #bottom></template>
        </v-data-table>
    </div>
</template>

<style>
.calc-situation-table table th {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    background-color: #1867c0;
    color: white;
    font-size: 0.875rem;
}
.calc-situation-table table tr {
    border: 1px #aaaaaa;
}
.calc-situation-table table td {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    font-size: 0.875rem;
}
</style>
