<script setup>
import { router } from '@inertiajs/vue3'
import { computed, ref, watch } from "vue"

const props = defineProps({
    data: Array
})

const clickLinkInpatient = () => {
    router.visit('/inpatient')
}

const inpetientDatas = computed(() => props.data)
const tableDatas = ref([])

// テーブル用に変換
watch(inpetientDatas, () => {
    inpetientDatas.value.forEach((d) => {
        let ward = d.ward_code
        if (d.ward_type) {
            ward = d.ward_code + `(${d.ward_type})`
        }

        tableDatas.value.push({
            admission_date: d.admission_date,
            inpatient_date: d.inpatient_date,
            personal_id: d.personal_id,
            full_name: d.full_name,
            age: d.age,
            section_name: d.section_name,
            ward: ward,
            doctor_name: d.doctor_name,
        })
    })
})

const headers = [
    { title: '入院日', align: 'start', key: 'admission_date', width: 200 },
    { title: '入院日数', align: 'start', key: 'admission_count', width: 200 },
    { title: '患者番号', align: 'center', key: 'personal_id', width: 200 },
    { title: '患者名', align: 'center', key: 'full_name', width: 200 },
    { title: '年齢', align: 'center', key: 'age', width: 200 },
    { title: '診療科', align: 'center', key: 'section_name', width: 200 },
    { title: '病室', align: 'center', key: 'ward', width: 200 },
    { title: '医師', align: 'center', key: 'doctor_name', width: 200 },
    { title: '指導・管理', align: 'center', key: '', width: 200 },
    { title: '詳細', align: 'center', key: '', width: 200 },
];
</script>
<template>
    <div class="text-h6">
        在院患者リスト
        <v-btn icon="mdi-clipboard-list-outline" @click.prevent="clickLinkInpatient">
        </v-btn>
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
