<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted, watch } from 'vue'

import DiagnosisTable from '@/Components/MyComponents/MedicalHistory/DiagnosisTable.vue'

/**
 * 診療履歴ページ
 */

const { personal, diagnosis } = defineProps({
    personal: Object,
    diagnosis: Array,
})

onMounted(() => {
    console.log(diagnosis)
})
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

            <!-- diagnosis table -->
            <DiagnosisTable :diagnosis="diagnosis"></DiagnosisTable>
        </v-card>
    </v-container>
</template>

<style>
.card-title {
    background-color: #1867c0;
    color: white;
}
</style>
