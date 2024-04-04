<script setup>
import { ref } from 'vue'

const { diagnosis } = defineProps({
    diagnosis: Array,
})

const headers = [
    { title: '病名コード', align: 'center', key: 'disease_code' },
    { title: '病名', align: 'start', key: 'disease_name' },
    { title: '疑い', align: 'center', key: 'suspicion' },
    { title: '主傷病', align: 'center', key: 'primary_disease' },
    { title: '開始日', align: 'center', key: 'problem_begin' },
    { title: '終了日', align: 'center', key: 'problem_end' },
    { title: '転帰', align: 'center', key: 'problem_outcome' },
]

const diagnosisData = ref(diagnosis)
const tableDatas = ref(diagnosisData.value)
</script>

<template>
    <v-container>
        <v-data-table
            :headers="headers"
            :items="tableDatas"
            class="daily-act-table"
        >
            <!-- 疑い -->
            <template v-slot:item.suspicion="{ value }">
                <template v-if="value === '＋'"> 〇 </template>
                <template v-else> {{ value }} </template>
            </template>

            <!-- 主傷病 -->
            <template v-slot:item.primary_disease="{ value }">
                <template v-if="value === '＋'"> 〇 </template>
                <template v-else> {{ value }} </template>
            </template>
        </v-data-table>
    </v-container>
</template>
<style>
.daily-act-table table th {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    background-color: #1867c0;
    color: white;
    font-size: 0.875rem;
}
.daily-act-table table tr {
    border: 1px #aaaaaa;
}
.daily-act-table table td {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    font-size: 0.875rem;
}
</style>
