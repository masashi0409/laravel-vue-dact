<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SearchCondition from '@/Components/MyComponents/SearchCondition.vue'
import ReservationList from '@/Components/MyComponents/ReservationList.vue'

/**
 * 外来予約リストページ
 *
 * 検索条件コンポーネント
 * 外来予約リストコンポーネント
 */

const {
    borderMoney, // 逆紹介ボーダー金額,
    extractingDate,
    initSearchConditionScenario, // 初期条件算定シナリオ
    initUnSearchConditionScenario,
    masterScenario,
} = defineProps({
    borderMoney: Number,
    extractingDate: String,
    initSearchConditionScenario: Array,
    initUnSearchConditionScenario: Array,
    masterScenario: Array,
})

onMounted(() => {
    // 検索初期条件をapiパラメータに設定
    form.scenarios = initSearchConditionScenario.map((i) => i['id'])

    getReservationData()
})

/**
 * 検索条件
 */
const searchScenario = ref(initSearchConditionScenario)
const unSearchScenario = ref(initUnSearchConditionScenario)

// 検索条件コンポーネントからシナリオ変更のemitを受ける
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
    selectedDoctors: [],
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
                    doctors: form.selectedDoctors,
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
            :searchScenario="searchScenario"
            :unSearchScenario="unSearchScenario"
            :masterScenario="masterScenario"
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
