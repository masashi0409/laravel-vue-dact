<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SearchCondition from '@/Components/MyComponents/SearchCondition/SearchCondition.vue'
import ReservationList from '@/Components/MyComponents/ReservationList.vue'

/**
 * 外来予約リストページ
 *
 * 検索条件コンポーネント
 * 外来予約リストコンポーネント
 */

// todo doctor
const {
    borderMoney, // 逆紹介ボーダー金額,
    extractingDate,
    masterDoctor,
    initSearchConditionDoctor,
    initUnSearchConditionDoctor,
    masterScenario,
    initSearchConditionScenario, // 初期条件算定シナリオ
    initUnSearchConditionScenario,
} = defineProps({
    borderMoney: Number,
    extractingDate: String,
    masterDoctor: Array,
    initSearchConditionDoctor: Array,
    initUnSearchConditionDoctor: Array,
    masterScenario: Array,
    initSearchConditionScenario: Array,
    initUnSearchConditionScenario: Array,
})

onMounted(() => {
    // 検索初期条件をapiパラメータに設定
    form.doctors = initSearchConditionDoctor.map((i) => i['id'])
    form.scenarios = initSearchConditionScenario.map((i) => i['id'])

    getReservationData()
})

/**
 * 検索条件
 */
const searchDoctor = ref(initSearchConditionDoctor)
const unSearchDoctor = ref(initUnSearchConditionDoctor)
const searchScenario = ref(initSearchConditionScenario)
const unSearchScenario = ref(initUnSearchConditionScenario)

// 医師選択ダイアログから医師変更のemitを受ける
const changeSearchDoctor = (editSearchDoctor) => {
    searchDoctor.value = []
    unSearchDoctor.value = []
    masterDoctor.forEach((doctor) => {
        if (editSearchDoctor.includes(doctor.doctor_id)) {
            searchDoctor.value.push({
                id: doctor.doctor_id,
                name: doctor.name,
            })
        } else {
            unSearchDoctor.value.push({
                id: doctor.doctor_id,
                name: doctor.name,
            })
        }
    })

    form.doctors = editSearchDoctor
}
// 算定シナリオ選択ダイアログからシナリオ変更のemitを受ける
const changeSearchScenario = (editSearchScenario) => {
    searchScenario.value = []
    unSearchScenario.value = []
    masterScenario.forEach((scenario) => {
        if (editSearchScenario.includes(scenario.scenario_control_sysid)) {
            searchScenario.value.push({
                id: scenario.scenario_control_sysid,
                name: scenario.display_name,
                color: scenario.color_code,
            })
        } else {
            unSearchScenario.value.push({
                id: scenario.scenario_control_sysid,
                name: scenario.display_name,
                color: scenario.color_code,
            })
        }
    })

    form.scenarios = editSearchScenario
}

// apiパラメータform params
const form = reactive({
    doctors: [],
    scene: 'reservation',
})

const clickSearchButton = () => {
    getReservationData()
}

const reservationData = reactive({})

const getReservationData = async () => {
    // console.log('reservation get data')
    try {
        await axios
            .get('/api/reservations/', {
                params: {
                    doctors: form.doctors,
                    scene: form.scene,
                    extractingDate: extractingDate,
                },
            })
            .then((res) => {
                reservationData.data = res.data.data
                // console.log(reservationData.data)
            })
    } catch (e) {
        console.log(e)
    }
}
</script>

<template>
    <Head title="外来予約リスト" />

    <AppLayout>
        <v-container>
            <div class="text-h5">外来予約リスト</div>
        </v-container>

        <!-- 検索条件 -->
        <SearchCondition
            :masterDoctor="masterDoctor"
            :searchDoctor="searchDoctor"
            :unSearchDoctor="unSearchDoctor"
            :searchScenario="searchScenario"
            :unSearchScenario="unSearchScenario"
            :masterScenario="masterScenario"
            @emitSearchDoctor="changeSearchDoctor"
            @emitSearchScenario="changeSearchScenario"
            @clickSearchButton="clickSearchButton"
        />

        <!--外来予約リスト-->
        <v-container>
            <ReservationList
                :data="reservationData.data"
                :border-money="borderMoney"
            />
        </v-container>
    </AppLayout>
</template>

<style scoped>
.disable-events {
    pointer-events: none;
}
</style>
