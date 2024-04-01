<script setup>
import { router } from '@inertiajs/vue3'
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { convertArrayToString } from '@/common'
import SettingDoctorDialog from '@/Components/MyComponents/SearchCondition/SettingDoctorDialog.vue'
import SettingScenarioDialog from '@/Components/MyComponents/SearchCondition/SettingScenarioDialog.vue'

/**
 * 初期条件変更ダイアログ
 */

// 検索条件コンポーネントからのデータ 設定済の検索条件
const {
    searchDoctor,
    unSearchDoctor,
    searchConditionScenario,
    unSearchConditionScenario,
} = defineProps({
    searchDoctor: Array,
    unSearchDoctor: Array,
    searchConditionScenario: Array,
    unSearchConditionScenario: Array,
})

// 保存ボタン押すまでは編集時の検索条件を表示したいので保持
const editSearchDoctor = ref(searchDoctor)
const editUnSearchDoctor = ref(unSearchDoctor)
const editSearchScenario = ref(searchConditionScenario)
const editUnSearchScenario = ref(unSearchConditionScenario)

const emit = defineEmits(['clickCancel'])

onMounted(() => {
    console.log('initialSearchCondition')
    console.log(editSearchDoctor.value)
    console.log(editUnSearchDoctor.value)
})

const doctorDialogFlg = ref(false)
const senarioDialogFlg = ref(false)

// 医師ダイアログからemit
const onDoctorSubmit = (editingSearchDoctor, editingUnSearchDoctor) => {
    doctorDialogFlg.value = false
    editSearchDoctor.value = editingSearchDoctor
    editUnSearchDoctor.value = editingUnSearchDoctor
}

// シナリオダイアログからemitされる
const onScenarioSubmit = (editingSearchScenario, editingUnsearchScenario) => {
    senarioDialogFlg.value = false
    editSearchScenario.value = editingSearchScenario
    editUnSearchScenario.value = editingUnsearchScenario
}

// 保存api用
const form = reactive({
    searchConditions: [], //画面で選択した新しい検索条件
})
// 保存ボタンを押下 選択された条件をseachConditionApiで保存
const clickSave = async () => {
    console.log('click save')

    form.searchConditions = [
        ...editSearchDoctor.value,
        ...editSearchScenario.value,
    ]
    console.log(form.searchConditions)
    try {
        await axios
            .post('/api/update-search-condition', {
                searchConditions: form.searchConditions,
            })
            .then((res) => {
                console.log(res)
                alert(res.data.message)

                router.visit('/')
            })
    } catch (e) {
        console.log(e)
    }
}

const clickBack = () => {
    emit('clickCancel')
}
</script>

<template>
    <div>
        <v-card>
            <v-card-title>初期条件設定</v-card-title>
            <v-card-text class="initial-search-condition-card">
                <v-row>
                    <v-col cols="2" class="col-condition-label">
                        <v-sheet class="condition-label">医師</v-sheet>
                    </v-col>
                    <v-col cols="8" class="col-condition-value">
                        <v-sheet class="condition-value">
                            <template v-if="editSearchDoctor.length > 0">
                                {{ convertArrayToString(editSearchDoctor) }}
                                <v-tooltip activator="parent">
                                    {{ convertArrayToString(editSearchDoctor) }}
                                </v-tooltip>
                            </template>
                            <template v-if="editSearchDoctor.length === 0">
                                すべての医師
                            </template>
                        </v-sheet>
                    </v-col>
                    <v-col cols="2"
                        ><v-btn
                            color="primary"
                            @click.stop="doctorDialogFlg = true"
                            >編集</v-btn
                        ></v-col
                    >
                </v-row>
                <v-row>
                    <v-col cols="2" class="col-condition-label">
                        <v-sheet class="condition-label">診療科</v-sheet>
                    </v-col>
                    <v-col cols="8" class="col-condition-value">
                        <v-sheet class="condition-value">test</v-sheet>
                    </v-col>
                    <v-col cols="2"><v-btn color="primary">編集</v-btn></v-col>
                </v-row>
                <v-row>
                    <v-col cols="2" class="col-condition-label">
                        <v-sheet class="condition-label">病棟</v-sheet>
                    </v-col>
                    <v-col cols="8" class="col-condition-value">
                        <v-sheet class="condition-value">test</v-sheet>
                    </v-col>
                    <v-col cols="2"><v-btn color="primary">編集</v-btn></v-col>
                </v-row>
                <v-row>
                    <v-col cols="2" class="col-condition-label">
                        <v-sheet class="condition-label">算定シナリオ</v-sheet>
                    </v-col>
                    <v-col cols="8" class="col-condition-value">
                        <v-sheet class="condition-value">
                            <template v-if="editSearchScenario.length > 0">
                                {{ convertArrayToString(editSearchScenario) }}
                                <v-tooltip activator="parent">
                                    {{
                                        convertArrayToString(editSearchScenario)
                                    }}
                                </v-tooltip>
                            </template>
                            <template v-if="editSearchScenario.length === 0">
                                すべての算定シナリオ
                            </template>
                        </v-sheet>
                    </v-col>
                    <v-col cols="2"
                        ><v-btn
                            color="primary"
                            @click.stop="senarioDialogFlg = true"
                            >編集</v-btn
                        ></v-col
                    >
                </v-row>
                <div class="mt-8">
                    <v-row>
                        <v-spacer></v-spacer>
                        <v-col cols="1">
                            <v-btn color="" @click="clickBack">戻る</v-btn>
                        </v-col>
                        <v-col cols="2">
                            <v-btn color="primary" @click="clickSave"
                                >保存</v-btn
                            >
                        </v-col>
                    </v-row>
                </div>
            </v-card-text>
        </v-card>
    </div>

    <!-- 医師ダイアログ -->
    <v-dialog v-model="doctorDialogFlg">
        <SettingDoctorDialog
            :editSearchDoctor="editSearchDoctor"
            :editUnSearchDoctor="editUnSearchDoctor"
            @clickSubmit="onDoctorSubmit"
            @clickCancel="doctorDialogFlg = false"
        />
    </v-dialog>
    <!-- シナリオダイアログ -->
    <v-dialog v-model="senarioDialogFlg">
        <SettingScenarioDialog
            :editSearchScenario="editSearchScenario"
            :editUnsearchScenario="editUnSearchScenario"
            @clickSubmit="onScenarioSubmit"
            @clickCancel="senarioDialogFlg = false"
        />
    </v-dialog>
</template>

<style scope>
.initial-search-condition-card {
    height: 500px;
}
.col-condition-label {
    padding-right: 0px;
}
.col-condition-value {
    padding-left: 0px;
}
.condition-label {
    border: 1px solid #aaaaaa;
    text-align: center;
    padding: 2px;
    background-color: #1867c0;
    color: white;
}
.condition-value {
    border: 1px solid #aaaaaa;
    text-align: center;
    padding: 2px;
    /* オーバーした要素を非表示にする*/
    overflow: hidden;
    /* 改行を半角スペースに変換することで、1行にする */
    white-space: nowrap;
    /* オーバーしたテキストを３点リーダーにする */
    text-overflow: ellipsis;
}
</style>
