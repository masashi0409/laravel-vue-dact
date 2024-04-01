<script setup>
import { ref, onMounted, watch } from 'vue'

/**
 * 検索条件選択 医師 ダイアログ
 */

const { masterDoctor, editSearchDoctor } = defineProps({
    masterDoctor: Array,
    editSearchDoctor: Array,
})

const emit = defineEmits(['clickSubmit', 'clickCancel'])

/**
 * selectテーブルの設定
 */
const selectHeaders = [
    {
        title: '医師',
        align: 'start',
        key: 'name',
    },
]
const filterText = ref()
const items = ref(masterDoctor)
const tableDatas = ref([])
watch(items, () => {
    tableDatas.value = []
    items.value.forEach((d) => {
        tableDatas.value.push({
            id: d.doctor_id,
            name: d.name,
        })
    })
})
// テーブルで選択されている
const selectedItems = ref([])

onMounted(() => {
    tableDatas.value = []
    items.value.forEach((d) => {
        tableDatas.value.push({
            id: d.doctor_id,
            name: d.name,
        })
    })

    editSearchDoctor.forEach((initialSelectDoctor) => {
        selectedItems.value.push(initialSelectDoctor.id)
    })
})

/**
 * 選択ボタン submit
 */
// 選択ボタンを押したら親コンポーネントに選択したシナリオを送る

const submit = () => {
    emit('clickSubmit', selectedItems.value)
}

const cancel = () => {
    emit('clickCancel')
}
</script>
<template>
    <v-card class="select-table-card">
        <v-card-title>医師選択</v-card-title>
        <v-card-text>
            <v-text-field
                v-model="filterText"
                append-icon="mdi-magnify"
                label="検索"
                single-line
                hide-details
                class="filter-text m-2"
            ></v-text-field>
            <v-data-table
                :headers="selectHeaders"
                :items="tableDatas"
                item-value="id"
                show-select
                v-model="selectedItems"
                :search="filterText"
                class="select-table"
                :fixed-header="true"
                height="400"
                :items-per-page="100"
            >
                <template v-slot:item.name="{ value }">
                    {{ value }}
                </template>
                <!-- ページングなし -->
                <!-- <template #bottom /> -->
            </v-data-table>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="cancel"> キャンセル </v-btn>
            <v-btn color="primary" @click="submit"> 選択 </v-btn>
        </v-card-actions>
    </v-card>
</template>
