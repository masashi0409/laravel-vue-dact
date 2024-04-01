<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SearchCondition from '@/Components/MyComponents/SearchCondition/SearchCondition.vue'
import InpatientList from '@/Components/MyComponents/InpatientList.vue'

/**
 * 在院患者リストページ
 *
 * 検索条件コンポーネント
 * 在院患者リストコンポーネント
 */

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

    getInpatientData()
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
                    doctors: form.doctors,
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

        <!--在院患者リスト-->
        <v-container>
            <InpatientList :data="inpatientData.data" />
        </v-container>
    </AppLayout>
</template>
