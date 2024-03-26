<script setup>
import { router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const props = defineProps({
    data: Array,
    borderMoney: Number,
})

const clickLinkReservation = () => {
    router.visit('/reservation')
}

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
    { title: '逆紹介', align: 'center', key: 'latest_point1' }, // latest_point1が施設マスタのborder_moneyより小なら逆紹介
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
    <div class="text-h5 mb-4">
        外来予約リスト
        <v-btn
            icon="mdi-clipboard-list-outline"
            @click.prevent="clickLinkReservation"
        >
        </v-btn>
    </div>
    <div>
        <v-data-table
            :headers="headers"
            :items="tableDatas"
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
                        <v-icon
                            color="orange"
                            v-if="calcPatient.achievements_count == 0"
                        >
                            mdi-exclamation
                        </v-icon>
                    </v-sheet>
                </template>
            </template>

            <!-- 逆紹介 -->
            <template v-slot:item.latest_point1="{ value }">
                <v-icon color="blue-darken-2" v-if="value < props.borderMoney">
                    mdi-check-bold
                </v-icon>
            </template>

            <template #bottom></template>
        </v-data-table>
    </div>
</template>

<style>
.reservation-table table th {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    background-color: #1867c0;
    color: white;
    font-size: 0.875rem;
}
.reservation-table table tr {
    border: 1px #aaaaaa;
}
.reservation-table table td {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    font-size: 0.875rem;
}
</style>
