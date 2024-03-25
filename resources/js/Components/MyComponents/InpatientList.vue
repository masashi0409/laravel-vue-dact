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
            calc_patient: d.calc_patient,
            calc_trigger: d.calc_trigger,
        })
    })
})

const headers = [
    { title: '入院日', align: 'start', key: 'admission_date' },
    { title: '入院日数', align: 'center', key: 'inpatient_date' },
    { title: '患者番号', align: 'center', key: 'personal_id' },
    { title: '患者名', align: 'center', key: 'full_name' },
    { title: '年齢', align: 'center', key: 'age' },
    { title: '診療科', align: 'center', key: 'section_name' },
    { title: '病室', align: 'center', key: 'ward' },
    { title: '医師', align: 'center', key: 'doctor_name' },
    {
        title: '指導・管理',
        align: 'start',
        key: 'calc_patient',
        sortable: false,
    },
    { title: '詳細', align: 'start', key: 'calc_trigger', sortable: false },
]

const actionCalcPatient = (e) => {
    if (e.achievements_count > 0) {
        return 'text-blue-darken-4'
    } else {
        return 'text-orange-darken-4'
    }
}

const clickunCalcIcon = (e) => {
    console.log(e)
}
</script>

<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="tableDatas"
            items-per-page-text="表示行数"
            class="inpatient-table"
        >
            <!-- 指導・管理 -->
            <template v-slot:item.calc_patient="{ value }">
                <template v-for="calcPatient in value">
                    <v-sheet
                        class="box"
                        :class="actionCalcPatient(calcPatient)"
                    >
                        {{ calcPatient.display_name }}
                        <v-icon
                            color="green"
                            v-if="calcPatient.achievements_count > 0"
                        >
                            mdi-check-bold
                        </v-icon>
                        <v-icon
                            color="orange"
                            v-if="calcPatient.achievements_count == 0"
                            @click="clickunCalcIcon(calcPatient)"
                        >
                            mdi-exclamation
                        </v-icon>
                    </v-sheet>
                </template>
            </template>

            <!-- 詳細（算定トリガー） -->
            <template v-slot:item.calc_trigger="{ value }">
                <template v-for="calcTrigger in value">
                    <div>{{ calcTrigger.item_name }}</div>
                </template>
            </template>
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
