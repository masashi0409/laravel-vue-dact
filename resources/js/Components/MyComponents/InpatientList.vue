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

/**
 * 指導・管理算定なら青、未算定ならオレンジ
 * param e calcPatient 患者別算定状況データ
 */
const actionCalcPatient = (e) => {
    if (e.achievements_count > 0) {
        return 'text-blue-darken-4'
    } else {
        return 'text-orange-darken-4'
    }
}

/**
 * 未算定の指導・管理をクリック
 * ！を✓に変える （check_flg:null or 0の時）
 * ✓を！に変える （check_flg:1 の時）
 * @param e calcPatient 患者別算定状況データ
 */
const clickUnCalcIcon = async (e) => {
    let requestCheckFlg = false
    if (e.check_flg == null || e.check_flg == 0) {
        requestCheckFlg = true
    }
    if (e.check_flg == 1) {
        requestCheckFlg = false
    }

    try {
        await axios
            .post('/api/calc-patient-check', {
                personal_id: e.personal_id,
                scenario_id: e.scenario_control_sysid,
                check_flg: requestCheckFlg,
            })
            .then((res) => {
                console.log(res.data)
                if (requestCheckFlg) {
                    e.check_flg = 1
                } else {
                    e.check_flg = 0
                }
            })
    } catch (e) {
        console.log(e)
    }
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

                        <template
                            v-if="
                                calcPatient.check_flg == null ||
                                calcPatient.check_flg == 0
                            "
                        >
                            <v-icon
                                color="orange"
                                v-if="calcPatient.achievements_count == 0"
                                @click="clickUnCalcIcon(calcPatient)"
                            >
                                mdi-exclamation
                            </v-icon>
                        </template>
                        <template v-if="calcPatient.check_flg == 1">
                            <v-icon
                                color="green"
                                v-if="calcPatient.achievements_count == 0"
                                @click="clickUnCalcIcon(calcPatient)"
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
