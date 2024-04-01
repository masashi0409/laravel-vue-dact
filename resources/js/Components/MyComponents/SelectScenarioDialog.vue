<script setup>
import { ref, onMounted, computed, watch } from 'vue'

const { masterScenarios, editSearchScenario } = defineProps({
    masterScenarios: Array,
    editSearchScenario: Array,
})

const emit = defineEmits(['clickSubmit', 'clickCancel'])

onMounted(() => {
    // console.log(masterScenarios)
    // console.log(editSearchScenario)

    tableDatas.value = []
    searchItems.value.forEach((d) => {
        tableDatas.value.push({
            id: d.scenario_control_sysid,
            scenario: {
                name: d.display_name,
                color: d.color_code,
            },
        })
    })

    // searchItemsにeditSearchScenario
    editSearchScenario.forEach((initialSelectScenario) => {
        // console.log(initialSelectScenario)
        selectedItems.value.push(initialSelectScenario.id)
    })
})

/**
 * searchテーブルの設定
 */
const searchHeaders = [
    {
        title: '選択算定シナリオ',
        align: 'start',
        key: 'scenario',
    },
]
const filterText = ref()
const searchItems = ref(masterScenarios) // テーブルデータ
// テーブル用
const tableDatas = ref([])
watch(searchItems, () => {
    tableDatas.value = []
    searchItems.value.forEach((d) => {
        tableDatas.value.push({
            id: d.scenario_control_sysid,
            scenario: {
                name: d.display_name,
                color: d.color_code,
            },
        })
    })
})
// テーブルで選択されている
const selectedItems = ref([])
watch(selectedItems, () => {
    console.log(selectedItems.value)
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

const getScenarioColor = (color) => {
    return `color: #${color}`
}
</script>
<template>
    <v-card>
        <v-card-title>算定シナリオ選択</v-card-title>
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
                :headers="searchHeaders"
                :items="tableDatas"
                item-value="id"
                show-select
                v-model="selectedItems"
                :search="filterText"
                class="select-table"
                :fixed-header="true"
                height="400"
                :items-per-page="-1"
            >
                <!-- シナリオ 色 名前 -->
                <template v-slot:item.scenario="{ value }">
                    <v-sheet>
                        <v-icon
                            :style="getScenarioColor(value.color)"
                            icon="mdi-square"
                        ></v-icon>
                        {{ value.name }}
                    </v-sheet>
                </template>
                <!-- ページングなし -->
                <template #bottom />
            </v-data-table>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="cancel"> キャンセル </v-btn>
            <v-btn color="primary" @click="submit"> 選択 </v-btn>
        </v-card-actions>
    </v-card>
</template>
