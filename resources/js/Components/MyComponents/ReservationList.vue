<script setup>
import axios from 'axios'
import { computed, ref, watch } from 'vue'

const props = defineProps({
    data: Array,
    borderMoney: Number,
})

const reservationDatas = computed(() => props.data)
const tableDatas = ref([])

// テーブル用に変換
watch(reservationDatas, () => {
    tableDatas.value = reservationDatas.value
})

const headers = [
    { title: '予約日時', align: 'start', key: 'reserved_datetime' },
    { title: '患者番号', align: 'center', key: 'personal_id' },
    { title: '患者名', align: 'center', key: 'full_name' },
    { title: '年齢', align: 'center', key: 'age' },
    { title: '診療科', align: 'center', key: 'section_name' },
    { title: '予約内容', align: 'center', key: 'reserved_type' },
    { title: '予約医師', align: 'center', key: 'doctor_name' },
    { title: '主病名', align: 'start', key: 'diagnosis', sortable: false },
    {
        title: '指導・管理',
        align: 'start',
        key: 'calc_patient',
        sortable: false,
    },
    { title: '逆紹介', align: 'center', key: 'latest_point1' },
]

/**
 *
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
 * ！を✓に変える check_flg:null or 0の時
 * ✓を！に変える check_flg:1 の時
 * calcPatientCheckを登録or更新
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
    <v-container>
        <v-data-table
            :headers="headers"
            :items="tableDatas"
            items-per-page-text="表示行数"
            class="reservation-table"
        >
            <!-- 主病名 -->
            <template v-slot:item.diagnosis="{ value }">
                <template v-for="disease in value">
                    <div v-if="disease.primary_disease">
                        {{ disease.disease_name }}
                    </div>
                </template>
            </template>

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

            <!-- 逆紹介 -->
            <template v-slot:item.latest_point1="{ value }">
                <v-icon color="blue-darken-2" v-if="value < props.borderMoney">
                    mdi-check-bold
                </v-icon>
            </template>
        </v-data-table>
    </v-container>
</template>

<style>
.reservation-table table th {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    background-color: #1867c0;
    color: white;
    font-size: 1rem;
}
.reservation-table table tr {
    border: 1px #aaaaaa;
}
.reservation-table table td {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    font-size: 1rem;
}
</style>
