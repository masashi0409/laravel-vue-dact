<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import ScenarioDialog from '@/Components/MyComponents/ScenarioDialog.vue'
import { convertArrayToString } from '@/common'

// searchConditionからのデータ 設定済の検索条件
const { searchConditionScenario, unSearchConditionScenario } = defineProps({
    searchConditionScenario: Array,
    unSearchConditionScenario: Array,
})

// 保存ボタン押すまでは編集時の検索条件を表示したいので保持
const editSearchScenario = ref(searchConditionScenario)
const editUnSearchScenario = ref(unSearchConditionScenario)

const emit = defineEmits(['clickCancel'])

onMounted(() => {
    // console.log('onMounted')
    // console.log(editSearchScenario.value)
    // console.log(editUnSearchScenario.value)
})

const senarioDialogFlg = ref(false)

// シナリオsubmit
// submitされたら表示・保存用でシナリオを保持
// DBからの値を表示していたものが、ダイアログからemitされた場合はそちらを表示する
const onScenarioSubmit = (editingSearchScenario, editingUnsearchScenario) => {
    // console.log('onScenarioSubmit')
    senarioDialogFlg.value = false
    // console.log(editingSearchScenario)
    // console.log(editingUnsearchScenario)
    editSearchScenario.value = editingSearchScenario
    editUnSearchScenario.value = editingUnsearchScenario
}

const cancelScenarioDialog = () => {
    senarioDialogFlg.value = false
}

// 保存api用
const form = reactive({
    searchConditions: [], //画面で選択した新しい検索条件
})
// 保存ボタンを押下 選択された条件をseachConditionApiで保存
const clickSave = async () => {
    // console.log('click save')
    form.searchConditions = [...editSearchScenario.value]
    // console.log(form.searchConditions)
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
                    <v-col cols="6" class="col-condition-value">
                        <v-sheet class="condition-value">test</v-sheet>
                    </v-col>
                    <v-col cols="2"><v-btn color="primary">編集</v-btn></v-col>
                </v-row>
                <v-row>
                    <v-col cols="2" class="col-condition-label">
                        <v-sheet class="condition-label">診療科</v-sheet>
                    </v-col>
                    <v-col cols="6" class="col-condition-value">
                        <v-sheet class="condition-value">test</v-sheet>
                    </v-col>
                    <v-col cols="2"><v-btn color="primary">編集</v-btn></v-col>
                </v-row>
                <v-row>
                    <v-col cols="2" class="col-condition-label">
                        <v-sheet class="condition-label">病棟</v-sheet>
                    </v-col>
                    <v-col cols="6" class="col-condition-value">
                        <v-sheet class="condition-value">test</v-sheet>
                    </v-col>
                    <v-col cols="2"><v-btn color="primary">編集</v-btn></v-col>
                </v-row>
                <v-row>
                    <v-col cols="2" class="col-condition-label">
                        <v-sheet class="condition-label">算定シナリオ</v-sheet>
                    </v-col>
                    <v-col cols="6" class="col-condition-value">
                        <v-sheet class="condition-value">
                            <template v-if="editSearchScenario.length > 0">
                                {{ convertArrayToString(editSearchScenario) }}
                            </template>
                            <template v-if="editSearchScenario.length === 0">
                                すべての算定シナリオ
                            </template>
                            <template v-if="editSearchScenario.length > 0">
                                <v-tooltip activator="parent">
                                    {{
                                        convertArrayToString(editSearchScenario)
                                    }}
                                </v-tooltip>
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
                <div>
                    <v-row>
                        <v-spacer></v-spacer>
                        <v-col cols="1">
                            <v-btn color="" @click="clickBack">戻る</v-btn>
                        </v-col>
                        <v-col cols="4">
                            <v-btn color="primary" @click="clickSave"
                                >保存</v-btn
                            >
                        </v-col>
                    </v-row>
                </div>
            </v-card-text>
        </v-card>
    </div>

    <!-- シナリオダイアログ -->
    <v-dialog v-model="senarioDialogFlg">
        <ScenarioDialog
            :editSearchScenario="editSearchScenario"
            :editUnsearchScenario="editUnSearchScenario"
            @clickSubmit="onScenarioSubmit"
            @clickCancel="cancelScenarioDialog"
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
