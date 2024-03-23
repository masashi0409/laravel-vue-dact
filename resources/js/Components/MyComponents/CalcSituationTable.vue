<script setup>
import { computed } from "vue"

const props = defineProps({
    data: Array
})

const headers = [
    { title: 'シナリオ名', align:'start', key:'scenario_control_name', width: 500 },
    { title: '算定実績', align:'center', key:'calc_archive_count', width: 150 },
    { title: '算定件数', align:'center', key:'calc_count', width: 150 },
    { title: '未算定件数', align:'center', key:'uncalc_count', width: 150 },
    {
        title: '算定率',
        align:'center',
        key:'calc_ratio',
        width: 150,
        sort: (a, b) => { // 単位がついているものをソートする
            a = a.replace(/[^0-9]/g, '');
            b = b.replace(/[^0-9]/g, '');
            return a - b;
        }
 }
]

const tableDatas = computed(() => props.data)

</script>

<template>
    <div class="text-h6">
        算定状況
    </div>
    <div>
        <v-data-table
            :headers="headers"
            :items="tableDatas"
        >
        <template #bottom></template>
        </v-data-table>
    </div>
</template>