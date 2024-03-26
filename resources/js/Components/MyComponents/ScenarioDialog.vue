<script setup>
import { ref, onMounted, watch } from 'vue'

const { editSearchScenario, editUnsearchScenario } = defineProps({
    editSearchScenario: Array,
    editUnsearchScenario: Array,
})

const emit = defineEmits(['clickSubmit', 'clickCancel'])

onMounted(() => {
    console.log(editSearchScenario)
    console.log(editUnsearchScenario)
})

// テーブルの設定
const unSearchItemPerPage = ref(5)
const unSearchHeaders = [
    {
        title: '未選択算定シナリオ',
        align: 'start',
        key: 'name',
    },
]
const search = ref()

// unsearchのシナリオテーブルデータ
const unSearchItems = ref(editUnsearchScenario)

// unsearchテーブルで選択されたもの
const selectedUnsearch = ref([])
watch(selectedUnsearch, () => {
    console.log(selectedUnsearch.value)
})

// 保存ボタンを押したら親コンポーネントに選択したシナリオを送る
const returnData = ref({
    name: 'click scenario dialog ok',
})

const submit = () => {
    emit('clickSubmit', returnData.value)
}

const cancel = () => {
    emit('clickCancel')
}
</script>
<template>
    <div>
        <v-card>
            <v-card-title>初期条件選択（算定シナリオ）</v-card-title>
            <v-card-text>
                <v-row>
                    <!-- unsearchテーブル -->
                    <v-col cols="5">
                        <v-container class="left-table-container">
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="検索"
                                single-line
                                hide-details
                                class="unsearch-search-text m-2"
                            ></v-text-field>

                            <v-data-table
                                :headers="unSearchHeaders"
                                :items="unSearchItems"
                                item-value="id"
                                show-select
                                v-model="selectedUnsearch"
                                v-model:items-per-page="unSearchItemPerPage"
                                :search="search"
                                class="unsearch-table search-table"
                            ></v-data-table>
                        </v-container>
                    </v-col>

                    <v-col cols="2">
                        <div class="m-10 mt-32">
                            <v-btn color="primary">>></v-btn>
                        </div>
                        <div class="m-10"><v-btn color="primary">></v-btn></div>
                        <div class="m-10"><v-btn color="primary"><</v-btn></div>
                        <div class="m-10">
                            <v-btn color="primary"><<</v-btn>
                        </div>
                    </v-col>

                    <!-- searchテーブル -->
                    <v-col cols="5">
                        <v-container class="right-table-container">
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="検索"
                                single-line
                                hide-details
                                class="unsearch-search-text m-2"
                            ></v-text-field>

                            <v-data-table
                                :headers="unSearchHeaders"
                                :items="unSearchItems"
                                item-value="id"
                                show-select
                                v-model="selectedUnsearch"
                                v-model:items-per-page="unSearchItemPerPage"
                                :search="search"
                                class="unsearch-table search-table"
                            ></v-data-table>
                        </v-container>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="cancel"> キャンセル </v-btn>
                <v-btn color="primary" @click="submit"> 選択 </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<style scoped>
.left-table-container {
    height: 600px;
    margin: 30px;
    margin-left: 100px;
}
.right-table-container {
    height: 600px;
    margin: 30px;
}
.unsearch-table {
    width: 400px;
}
.unsearch-search-text {
    max-width: 375px;
}
</style>

<style>
.search-table table th {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    background-color: #1867c0;
    color: white;
    /* font-size: 0.875rem; */
}
.search-table table tr {
    border: 1px #aaaaaa;
}
.search-table table td {
    border: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa !important;
    /* font-size: 0.875rem; */
}
</style>
