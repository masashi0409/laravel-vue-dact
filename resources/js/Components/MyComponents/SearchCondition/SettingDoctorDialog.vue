<script setup>
import { ref, onMounted, watch } from 'vue'

/**
 * 初期検索条件設定 医師 ダイアログ
 */

const { editSearchDoctor, editUnSearchDoctor } = defineProps({
    editSearchDoctor: Array,
    editUnSearchDoctor: Array,
})

const emit = defineEmits(['clickSubmit', 'clickCancel'])

onMounted(() => {
    unSearchTableDatas.value = []
    editingUnSearchDoctor.value.forEach((d) => {
        unSearchTableDatas.value.push({
            id: d.id,
            name: d.name,
        })
    })
    searchTableDatas.value = []
    editingSearchDoctor.value.forEach((d) => {
        searchTableDatas.value.push({
            id: d.id,
            name: d.name,
        })
    })
})

/**
 * unsearchテーブルの設定・データ
 */
const unSearchHeaders = [
    {
        title: '未選択医師',
        align: 'start',
        key: 'name',
    },
]
const unSearchItemPerPage = ref(10)
const filterUnsearch = ref()
const editingUnSearchDoctor = ref(editUnSearchDoctor) // props受け取り用
const unSearchTableDatas = ref([]) // テーブル用
watch(editingUnSearchDoctor, () => {
    unSearchTableDatas.value = []
    editingUnSearchDoctor.value.forEach((e) => {
        unSearchTableDatas.value.push({
            id: e.id,
            name: e.name,
        })
    })
})
const selectedUnsearch = ref([]) // unSearchテーブルで選択されたもの

/**
 * searchテーブルの設定
 */

const searchHeaders = [
    {
        title: '選択算定シナリオ',
        align: 'start',
        key: 'name',
    },
]
const searchItemPerPage = ref(10)
const filterSearch = ref()
const editingSearchDoctor = ref(editSearchDoctor)
const searchTableDatas = ref([])
watch(editingSearchDoctor, () => {
    searchTableDatas.value = []
    editingSearchDoctor.value.forEach((e) => {
        searchTableDatas.value.push({
            id: e.id,
            name: e.name,
        })
    })
})
const selectedSearch = ref([]) // searchテーブルで選択されているもの

/**
 * addボタン
 */
const addSearch = () => {
    console.log(editingUnSearchDoctor.value)
    selectedUnsearch.value.forEach((s) => {
        let selected = editingUnSearchDoctor.value.find((i) => i.id === s) // nameが必要なため
        editingSearchDoctor.value.push(selected) // editingSearchDoctorにaddする
        // unsearchテーブルのデータから削除する
        editingUnSearchDoctor.value = editingUnSearchDoctor.value.filter(
            (i) => i.id !== s
        )
    })
    searchTableDatas.value = []
    editingSearchDoctor.value.forEach((d) => {
        searchTableDatas.value.push({
            id: d.id,
            name: d.name,
        })
    })
    selectedUnsearch.value = [] // 選択を解除
}

// searchTableDataで選択されているものをunsearchTableへ
const addUnsearch = () => {
    // console.log(selectedSearch.value)
    selectedSearch.value.forEach((s) => {
        let selected = editingSearchDoctor.value.find((i) => i.id === s) // nameが必要なため
        // unSearchItemsにaddする
        editingUnSearchDoctor.value.push(selected)

        // searchから削除する
        editingSearchDoctor.value = editingSearchDoctor.value.filter(
            (i) => i.id !== s
        )
    })

    unSearchTableDatas.value = []
    editingUnSearchDoctor.value.forEach((d) => {
        unSearchTableDatas.value.push({
            id: d.id,
            name: d.name,
        })
    })

    selectedSearch.value = []
}

// unsearchTableをすべてsearchTableへ
const addAllSearch = () => {
    editingSearchDoctor.value = [
        ...editingSearchDoctor.value,
        ...editingUnSearchDoctor.value,
    ]
    editingUnSearchDoctor.value = []

    searchTableDatas.value = []
    editingSearchDoctor.value.forEach((d) => {
        searchTableDatas.value.push({
            id: d.id,
            name: d.name,
        })
    })
}

// searchTableをすべてUnsearchTableへ
const addAllUnSearch = () => {
    editingUnSearchDoctor.value = [
        ...editingUnSearchDoctor.value,
        ...editingSearchDoctor.value,
    ]
    editingSearchDoctor.value = []

    UnSearchTableDatas.value = []
    editingSearchDoctor.value.forEach((d) => {
        unSearchTableDatas.value.push({
            id: d.id,
            name: d.name,
        })
    })
}

/**
 * 選択ボタン submit
 */
const submit = () => {
    emit('clickSubmit', editingSearchDoctor.value, editingUnSearchDoctor.value)
}

const cancel = () => {
    emit('clickCancel')
}
</script>

<template>
    <v-card>
        <v-card-title>初期条件選択（医師）</v-card-title>
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
                            :items="unSearchTableDatas"
                            item-value="id"
                            show-select
                            v-model="selectedUnsearch"
                            v-model:items-per-page="unSearchItemPerPage"
                            :search="filterUnsearch"
                            class="unsearch-table search-table"
                        >
                        </v-data-table>
                    </v-container>
                </v-col>

                <!-- ボタン -->
                <v-col cols="2">
                    <div class="m-10 mt-32">
                        <v-btn color="primary" @click="addAllSearch">>></v-btn>
                    </div>
                    <div class="m-10">
                        <v-btn color="primary" @click="addSearch">></v-btn>
                    </div>
                    <div class="m-10">
                        <v-btn color="primary" @click="addUnsearch"><</v-btn>
                    </div>
                    <div class="m-10">
                        <v-btn color="primary" @click="addAllUnSearch"
                            ><<</v-btn
                        >
                    </div>
                </v-col>

                <!-- searchテーブル -->
                <v-col cols="5">
                    <v-container class="left-table-container">
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
                            :items="searchTableDatas"
                            item-value="id"
                            show-select
                            v-model="selectedSearch"
                            v-model:items-per-page="searchItemPerPage"
                            :search="filterSearch"
                            class="unsearch-table search-table"
                        >
                        </v-data-table>
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
