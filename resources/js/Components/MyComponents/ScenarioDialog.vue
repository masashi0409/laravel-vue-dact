<script setup>
import { ref, onMounted, watch } from 'vue'

const { editSearchScenario, editUnsearchScenario } = defineProps({
    editSearchScenario: Array,
    editUnsearchScenario: Array,
})

const emit = defineEmits(['clickSubmit', 'clickCancel'])

onMounted(() => {
    // console.log(editSearchScenario)
    // console.log(editUnsearchScenario)
})
/**
 * unsearchテーブルの設定・データ
 */
const unSearchItemPerPage = ref(10)
const unSearchHeaders = [
    {
        title: '未選択算定シナリオ',
        align: 'start',
        key: 'name',
    },
]
const filterUnsearch = ref()
const unSearchItems = ref(editUnsearchScenario) // unsearchのシナリオテーブルデータ
const selectedUnsearch = ref([]) // unsearchテーブルで選択されたもの
// watch(selectedUnsearch, () => {
//     console.log(selectedUnsearch.value)
// })

/**
 * searchテーブルの設定
 */
const searchItemPerPage = ref(10)
const searchHeaders = [
    {
        title: '選択算定シナリオ',
        align: 'start',
        key: 'name',
    },
]
const filterSearch = ref()
const searchItems = ref(editSearchScenario) // searchのシナリオテーブルデータ
const selectedSearch = ref([]) // searchテーブルで選択されたもの
// watch(selectedSearch, () => {
//     console.log(selectedSearch.value)
// })

/**
 * addボタン系
 */
// unsearchTableで選択されているものをsearchTableDataへ
const addSearch = () => {
    // console.log(selectedUnsearch.value)
    selectedUnsearch.value.forEach((s) => {
        // console.log(s)
        let selectedScenario = unSearchItems.value.find((i) => i.id === s) // nameが必要なため
        // searchItemsにaddする
        searchItems.value.push(selectedScenario)

        // unSearchから削除する
        unSearchItems.value = unSearchItems.value.filter((i) => i.id !== s)
    })
    selectedUnsearch.value = []
}

const addAllSearch = () => {
    searchItems.value = [...searchItems.value, ...unSearchItems.value]
    unSearchItems.value = []
}

// searchTableDataで選択されているものをunsearchTableへ
const addUnsearch = () => {
    // console.log(selectedSearch.value)
    selectedSearch.value.forEach((s) => {
        let selectedScenario = searchItems.value.find((i) => i.id === s) // nameが必要なため
        // unSearchItemsにaddする
        unSearchItems.value.push(selectedScenario)

        // searchから削除する
        searchItems.value = searchItems.value.filter((i) => i.id !== s)
    })
    selectedSearch.value = []
}

const addAllUnSearch = () => {
    unSearchItems.value = [...unSearchItems.value, ...searchItems.value]
    searchItems.value = []
}

/**
 * 選択ボタン submit
 */
// 選択ボタンを押したら親コンポーネントに選択したシナリオを送る

const submit = () => {
    emit('clickSubmit', searchItems.value, unSearchItems.value)
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
                                v-model="filterUnsearch"
                                append-icon="mdi-magnify"
                                label="検索"
                                single-line
                                hide-details
                                class="filter-text m-2"
                            ></v-text-field>

                            <v-data-table
                                :headers="unSearchHeaders"
                                :items="unSearchItems"
                                item-value="id"
                                show-select
                                v-model="selectedUnsearch"
                                v-model:items-per-page="unSearchItemPerPage"
                                :search="filterUnsearch"
                                class="unsearch-table search-table"
                            ></v-data-table>
                        </v-container>
                    </v-col>

                    <v-col cols="2">
                        <div class="m-10 mt-32">
                            <v-btn color="primary" @click="addAllSearch"
                                >>></v-btn
                            >
                        </div>
                        <div class="m-10">
                            <v-btn color="primary" @click="addSearch">></v-btn>
                        </div>
                        <div class="m-10">
                            <v-btn color="primary" @click="addUnsearch"
                                ><</v-btn
                            >
                        </div>
                        <div class="m-10">
                            <v-btn color="primary" @click="addAllUnSearch"
                                ><<</v-btn
                            >
                        </div>
                    </v-col>

                    <!-- searchテーブル -->
                    <v-col cols="5">
                        <v-container class="right-table-container">
                            <v-text-field
                                v-model="filterSearch"
                                append-icon="mdi-magnify"
                                label="検索"
                                single-line
                                hide-details
                                class="filter-text m-2"
                            ></v-text-field>

                            <v-data-table
                                :headers="searchHeaders"
                                :items="searchItems"
                                item-value="id"
                                show-select
                                v-model="selectedSearch"
                                v-model:items-per-page="searchItemPerPage"
                                :search="filterSearch"
                                class="search-table"
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
    /* height: 500px; */
    margin: 30px;
    margin-left: 100px;
}
.right-table-container {
    /* height: 500px; */
    margin: 30px;
}

.filter-text {
    max-width: 375px;
}

/* テーブルrow高さ */
.v-table--density-default {
    --v-table-row-height: 36px;
}
</style>

<style>
.search-table {
    width: 400px;
}
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
