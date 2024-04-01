<script setup>
import { ref, reactive, onMounted } from 'vue'
import { convertArrayToString } from '@/common'
import InitialSearchConditionDialog from '@/Components/MyComponents/SearchCondition/InitialSearchConditionDialog.vue'
import SelectDoctorDialog from '@/Components/MyComponents/SearchCondition/SelectDoctorDialog.vue'
import SelectScenarioDialog from '@/Components/MyComponents/SearchCondition/SelectScenarioDialog.vue'

/**
 * 検索条件コンポーネント
 *
 *  - 初期条件変更ダイアログコンポーネント
 *
 *  - 医師選択ダイアログコンポーネント
 *  - 算定シナリオ選択ダイアログコンポーネント
 *
 *   検索ボタン
 */

const {
    masterDoctor,
    searchDoctor,
    unSearchDoctor,
    masterScenario,
    searchScenario,
    unSearchScenario,
} = defineProps({
    masterDoctor: Array,
    searchDoctor: Array,
    unSearchDoctor: Array,
    masterScenario: Array,
    searchScenario: Array,
    unSearchScenario: Array,
})

const emit = defineEmits([
    'emitSearchDoctor',
    'emitSearchScenario',
    'clickSearchButton',
])

onMounted(() => {
    // console.log('search condition')
})

// 初期条件変更ダイアログ
const initialSearchConditionDialogFlg = ref(false)

// 検索条件変更ダイアログ
const doctorDialogFlg = ref(false)
const sectionDialogFlg = ref(false)
const wardDialogFlg = ref(false)
const senarioDialogFlg = ref(false)

const clickSearchButton = () => {
    emit('clickSearchButton')
}

// 医師ダイアログからemit
const onDoctorSubmit = (editingSearchDoctor) => {
    doctorDialogFlg.value = false

    // 検索コンポーネントからページにemit
    emit('emitSearchDoctor', editingSearchDoctor)
}

// シナリオダイアログからemit
const onScenarioSubmit = (editingSearchScenario) => {
    senarioDialogFlg.value = false

    // 検索コンポーネントからページにemit
    emit('emitSearchScenario', editingSearchScenario)
}
</script>

<template>
    <!--検索-->
    <v-container class="mt-4 mb-4 search-container">
        <div class="d-flex flex-row">
            <div class="text-h6">検索条件</div>
            <v-btn
                density="compact"
                icon="mdi-cog"
                @click="initialSearchConditionDialogFlg = true"
            ></v-btn>
        </div>

        <v-dialog v-model="initialSearchConditionDialogFlg" max-width="1200">
            <InitialSearchConditionDialog
                :searchDoctor="searchDoctor"
                :unSearchDoctor="unSearchDoctor"
                :searchConditionScenario="searchScenario"
                :unSearchConditionScenario="unSearchScenario"
                @clickCancel="initialSearchConditionDialogFlg = false"
            />
        </v-dialog>

        <v-row class="mb-4">
            <v-col>
                <div>医師</div>
                <v-sheet
                    class="top-condition-value"
                    @click.stop="doctorDialogFlg = true"
                >
                    <template v-if="searchDoctor.length > 0">
                        {{ convertArrayToString(searchDoctor) }}
                        <v-tooltip activator="parent">
                            {{ convertArrayToString(searchDoctor) }}
                        </v-tooltip>
                    </template>
                    <template v-if="searchDoctor.length === 0">
                        すべての医師
                    </template>
                </v-sheet>
            </v-col>
            <v-col>
                <div>診療科</div>
                <v-sheet class="top-condition-value"> test </v-sheet>
            </v-col>
            <v-col>
                <div>病棟</div>
                <v-sheet class="top-condition-value"> test </v-sheet>
            </v-col>
            <v-col>
                <div>算定シナリオ</div>
                <v-sheet
                    class="top-condition-value"
                    @click.stop="senarioDialogFlg = true"
                >
                    <template v-if="searchScenario.length > 0">
                        {{ convertArrayToString(searchScenario) }}
                    </template>
                    <template v-if="searchScenario.length === 0">
                        すべての算定シナリオ
                    </template>
                    <template v-if="searchScenario.length > 0">
                        <v-tooltip activator="parent">
                            {{ convertArrayToString(searchScenario) }}
                        </v-tooltip>
                    </template>
                </v-sheet>
            </v-col>
        </v-row>

        <!-- 医師選択ダイアログ -->
        <v-dialog v-model="doctorDialogFlg" max-width="600">
            <SelectDoctorDialog
                :masterDoctor="masterDoctor"
                :editSearchDoctor="searchDoctor"
                @clickSubmit="onDoctorSubmit"
                @clickCancel="doctorDialogFlg = false"
            />
        </v-dialog>

        <!-- 算定シナリオ選択ダイアログ -->
        <v-dialog v-model="senarioDialogFlg" max-width="600">
            <SelectScenarioDialog
                :masterScenario="masterScenario"
                :editSearchScenario="searchScenario"
                @clickSubmit="onScenarioSubmit"
                @clickCancel="senarioDialogFlg = false"
            />
        </v-dialog>

        <div class="d-flex">
            <v-spacer></v-spacer>
            <v-btn elevation="2" color="primary" @click="clickSearchButton"
                >検索</v-btn
            >
        </div>
    </v-container>
</template>

<style scoped>
.search-container {
    border: 1px solid #aaaaaa;
}

.top-condition-value {
    max-width: 300px;
    border: 1px solid #aaaaaa;
    text-align: center;
    padding: 2px;
    overflow: hidden; /* オーバーした要素を非表示にする*/
    white-space: nowrap; /* 改行を半角スペースに変換することで、1行にする */
    text-overflow: ellipsis; /* オーバーしたテキストを３点リーダーにする */
}
</style>
