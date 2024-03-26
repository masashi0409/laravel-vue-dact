<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, onMounted } from 'vue'
import ScenarioDialog from '@/Components/MyComponents/ScenarioDialog.vue'

// searchConditionからのデータ 設定済の検索条件
const { searchConditionScenario, unSearchConditionScenario } = defineProps({
    searchConditionScenario: Array,
    unSearchConditionScenario: Array,
})

// 保存ボタン押すまでは編集時の検索条件を表示したいので保持
const editSearchScenario = ref(searchConditionScenario)
const editUnSearchScenario = ref(unSearchConditionScenario)

onMounted(() => {
    // console.log('onMounted')
    // console.log(editSearchScenario.value)
    // console.log(editUnSearchScenario.value)
})

// dialog
const senarioDialogFlg = ref(false)
const scenario = ref('test')

// シナリオsubmit
// submitされたら保存用と表示用でシナリオを保持
// DBからの値を表示していたものが、ダイアログからemitされた場合はそちらを表示する
const onScenarioSubmit = (params) => {
    senarioDialogFlg.value = false
    scenario.value = params.name
    console.log(scenario.value)
}

const cancelScenarioDialog = () => {
    senarioDialogFlg.value = false
}

// 保存ボタンを押下 選択された各条件をseachConditionApiで保存
const clickSave = () => {
    console.log('click save')
}

const clickBack = () => {
    console.log('click back')
}

const convertArrayToString = (objArr) => {
    return objArr
        .map((row) => {
            return [row['name']]
        })
        .join(', ')
}
</script>

<template>
    <Head title="ユーザ設定（初期条件）" />
    <AppLayout>
        <v-container class="mt-4 mb-4">
            <div class="text-h5">ユーザ設定</div>
        </v-container>

        <v-container>
            <div>
                <div class="mb-16">
                    <v-row>
                        <v-col cols="2" class="col-condition-label">
                            <v-sheet class="condition-label">医師</v-sheet>
                        </v-col>
                        <v-col cols="6" class="col-condition-value">
                            <v-sheet class="condition-value">test</v-sheet>
                        </v-col>
                        <v-col cols="2"
                            ><v-btn color="primary">編集</v-btn></v-col
                        >
                    </v-row>
                    <v-row>
                        <v-col cols="2" class="col-condition-label">
                            <v-sheet class="condition-label">診療科</v-sheet>
                        </v-col>
                        <v-col cols="6" class="col-condition-value">
                            <v-sheet class="condition-value">test</v-sheet>
                        </v-col>
                        <v-col cols="2"
                            ><v-btn color="primary">編集</v-btn></v-col
                        >
                    </v-row>
                    <v-row>
                        <v-col cols="2" class="col-condition-label">
                            <v-sheet class="condition-label">病棟</v-sheet>
                        </v-col>
                        <v-col cols="6" class="col-condition-value">
                            <v-sheet class="condition-value">test</v-sheet>
                        </v-col>
                        <v-col cols="2"
                            ><v-btn color="primary">編集</v-btn></v-col
                        >
                    </v-row>
                    <v-row>
                        <v-col cols="2" class="col-condition-label">
                            <v-sheet class="condition-label"
                                >算定シナリオ</v-sheet
                            >
                        </v-col>
                        <v-col cols="6" class="col-condition-value">
                            <v-sheet class="condition-value">
                                {{ convertArrayToString(editSearchScenario) }}
                                <v-tooltip activator="parent">
                                    {{
                                        convertArrayToString(editSearchScenario)
                                    }}
                                </v-tooltip>
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
                </div>
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
            </div>
        </v-container>

        <!-- シナリオダイアログ -->
        <v-dialog v-model="senarioDialogFlg">
            <ScenarioDialog
                :editSearchScenario="editSearchScenario"
                :editUnsearchScenario="editUnSearchScenario"
                @clickSubmit="onScenarioSubmit"
                @clickCancel="cancelScenarioDialog"
            />
        </v-dialog>
    </AppLayout>
</template>

<style scope>
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
    /* text-align: center; */
    padding: 2px;

    /* オーバーした要素を非表示にする*/
    overflow: hidden;

    /* 改行を半角スペースに変換することで、1行にする */
    white-space: nowrap;

    /* オーバーしたテキストを３点リーダーにする */
    text-overflow: ellipsis;
}
</style>
