<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SearchCondition from '@/Components/MyComponents/SearchCondition.vue'
import InpatientList from '@/Components/MyComponents/InpatientList.vue'

/**
 * 在院患者リストページ
 *
 * 検索条件コンポーネント
 * 在院患者リストコンポーネント
 */

const {
    scenarios,
    extractingDate, // 最新更新日 2022-08-25
    initSearchConditionScenario, // 初期条件算定シナリオ
    initUnSearchConditionScenario,
    masterScenario,
} = defineProps({
    scenarios: Array,
    extractingDate: String,
    initSearchConditionScenario: Array,
    initUnSearchConditionScenario: Array,
    masterScenario: Array,
})

onMounted(() => {
    // 検索初期条件をapiパラメータに設定
    form.scenarios = initSearchConditionScenario.map((i) => i['id'])

    getInpatientData()
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
    scene: 'inpatient',
})

const clickSearchButton = () => {
    getInpatientData()
}

const inpatientData = reactive({})

const getInpatientData = async () => {
    try {
        await axios
            .get('/api/inpatients/', {
                params: {
                    doctors: form.selectedDoctors,
                    scene: form.scene,
                    extractingDate: extractingDate,
                },
            })
            .then((res) => {
                inpatientData.data = res.data.data
            })
    } catch (e) {
        console.log(e)
    }
}
</script>
<template>
    <Head title="在院患者リスト" />

    <AppLayout>
        <v-container>
            <div class="text-h5">在院患者リスト</div>
        </v-container>

        <!-- 検索条件 -->
        <SearchCondition
            :searchScenario="searchScenario"
            :unSearchScenario="unSearchScenario"
            :masterScenario="masterScenario"
            @emitSearchScenario="changeSearchScenario"
            @clickSearchButton="clickSearchButton"
        />

        <!--在院患者リスト-->
        <v-container>
            <InpatientList :data="inpatientData.data" />
        </v-container>
    </AppLayout>
</template>
