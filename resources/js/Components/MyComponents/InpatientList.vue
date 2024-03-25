<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
    data: Array,
})

const inpetientDatas = computed(() => props.data)
const tableDatas = ref([])

// テーブル用に変換
watch(inpetientDatas, () => {
    // console.log('watch data')
    // console.log(inpetientDatas.value)

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
    { title: '入院日数', align: 'center', key: 'inpatient_date', width: 200 },
    { title: '患者番号', align: 'center', key: 'personal_id', width: 200 },
    { title: '患者名', align: 'center', key: 'full_name', width: 200 },
    { title: '年齢', align: 'center', key: 'age', width: 200 },
    { title: '診療科', align: 'center', key: 'section_name', width: 200 },
    { title: '病室', align: 'center', key: 'ward', width: 200 },
    { title: '医師', align: 'center', key: 'doctor_name', width: 200 },
    { title: '指導・管理', align: 'center', key: '', width: 200 },
    { title: '詳細', align: 'center', key: '', width: 200 },
]
</script>

<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="tableDatas"
            items-per-page-text="表示行数"
            class="inpatient-table"
        >
        </v-data-table>
    </div>
</template>

<style>
.inpatient-table table th {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    background-color: #1867c0;
    color: white;
    font-size: 0.875rem;
}
.inpatient-table table tr {
    border: 1px #aaaaaa;
}
.inpatient-table table td {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    font-size: 0.875rem;
}
</style>
