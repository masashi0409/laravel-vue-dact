<script setup>
import { router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const props = defineProps({
    data: Array,
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
            calc_patient: d.calc_patient,
            calc_trigger: d.calc_trigger,
        })
    })
})

const headers = [
    { title: '入院日', align: 'start', key: 'admission_date' },
    { title: '入院日数', align: 'start', key: 'inpatient_date' },
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
</script>
<template>
    <div class="text-h6 mb-4">
        在院患者リスト
        <v-btn
            icon="mdi-clipboard-list-outline"
            @click.prevent="clickLinkInpatient"
        >
        </v-btn>
    </div>
    <div>
        <v-data-table
            :headers="headers"
            :items="tableDatas"
            class="inpatient-table"
        >
            <!-- 指導・管理 -->
            <template v-slot:item.calc_patient="{ value }">
                <template v-for="calcPatient in value">
                    <v-sheet :class="actionCalcPatient(calcPatient)">
                        {{ calcPatient.display_name }}
                        <v-icon
                            color="green"
                            v-if="calcPatient.achievements_count > 0"
                        >
                            mdi-check-bold
                        </v-icon>
                        <template
                            v-if="
                                calcPatient.check_flg == null ||
                                calcPatient.check_flg == 0
                            "
                        >
                            <v-icon
                                color="orange"
                                v-if="calcPatient.achievements_count == 0"
                            >
                                mdi-exclamation
                            </v-icon>
                        </template>
                        <template v-if="calcPatient.check_flg == 1">
                            <v-icon
                                color="green"
                                v-if="calcPatient.achievements_count == 0"
                            >
                                mdi-check-bold
                            </v-icon>
                        </template>
                    </v-sheet>
                </template>
            </template>

            <!-- 詳細（算定トリガー） -->
            <template v-slot:item.calc_trigger="{ value }">
                <template v-for="calcTrigger in value">
                    <div>{{ calcTrigger.item_name }}</div>
                </template>
            </template>

            <template #bottom></template>
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
