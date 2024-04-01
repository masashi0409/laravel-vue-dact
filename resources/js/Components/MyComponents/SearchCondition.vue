<script setup>
import { ref, reactive, onMounted } from 'vue'
import { convertArrayToString } from '@/common'
import InitialSearchConditionDialog from '@/Components/MyComponents/InitialSearchConditionDialog.vue'
import SelectScenarioDialog from '@/Components/MyComponents/SelectScenarioDialog.vue'

const { searchScenario, unSearchScenario, masterScenario } = defineProps({
    searchScenario: Array,
    unSearchScenario: Array,
    masterScenario: Array,
})

const emit = defineEmits(['emitSearchScenario', 'clickSearchButton'])

// 初期条件変更ダイアログ
const initialSearchConditionDialogFlg = ref(false)

// 検索条件変更ダイアログ
const senarioDialogFlg = ref(false)

const clickSearchButton = () => {
    emit('clickSearchButton')
}

// シナリオダイアログからemit
const onScenarioSubmit = (editingSearchScenario) => {
    console.log(searchScenario)
    console.log(editingSearchScenario)

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
                :searchConditionScenario="searchScenario"
                :unSearchConditionScenario="unSearchScenario"
                @clickCancel="initialSearchConditionDialogFlg = false"
            />
        </v-dialog>

        <v-row class="mb-4">
            <v-col>
                <div>医師</div>
                <v-sheet class="top-condition-value"> test </v-sheet>
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

        <!-- シナリオダイアログ -->
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
