<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted, watch } from 'vue'
import axios from 'axios'

import DiagnosisTable from '@/Components/MyComponents/MedicalHistory/DiagnosisTable.vue'
import CostChart from '@/Components/MyComponents/MedicalHistory/CostChart.vue'

/**
 * 診療履歴ページ
 */

const { personal, diagnosis } = defineProps({
    personal: Object,
    diagnosis: Array,
})

onMounted(() => {
    console.log('medical history')
    console.log(diagnosis)

    // 診療コストチャートデータ取得
    getCostPatientData()
})

const outPatientCosts = ref([])
/**
 * 診療コストチャートデータ取得
 */
const getCostPatientData = async () => {
    try {
        await axios
            .get('/api/outpatient-cost', {
                params: {
                    personalId: personal.personal_id,
                },
            })
            .then((res) => {
                console.log('get cost patient data.')
                outPatientCosts.value = res.data.outPatientCosts
                console.log(outPatientCosts.value)
            })
    } catch (e) {
        console.log(e)
    }
}
</script>
<template>
    <Head title="診療履歴" />

    <v-container>
        <v-card>
            <v-card-title class="card-title d-flex justify-center"
                >診療履歴</v-card-title
            >

            <v-card-text>
                <v-container class="d-flex">
                    <div class="mr-8">患者番号：{{ personal.personal_id }}</div>
                    <div>患者名：{{ personal.full_name }}</div>
                </v-container>
            </v-card-text>

            <CostChart :outPatientCosts="outPatientCosts" />

            <!-- diagnosis table -->
            <DiagnosisTable :diagnosis="diagnosis" />
        </v-card>
    </v-container>
</template>

<style>
.card-title {
    background-color: #1867c0;
    color: white;
}
</style>
