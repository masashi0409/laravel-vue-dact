<script setup lang="ts">
/** グリッドレイアウト（ダッシュボード）サンプル */
import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import { throttle } from '@vexip-ui/utils'
import { GridLayout, GridItem } from 'grid-layout-plus'
import AppLayout from '@/Layouts/AppLayout.vue'
import DashboardPieChart from '@/Components/DashboardPieChart.vue'

const { gridLayout } = defineProps({
    gridLayout: Object,
})

const cardWidth = ref('1')
const cardHeight = ref('1')
const tickLabels = ref(['2', '3', '4', '5', '6'])

const colNumLabels = ref(['6', '7', '8', '9', '10', '11', '12'])
const heightLabels = ref(['170', '180', '190', '200', '210', '220'])
const varticalCompact = ref(false) // 縦に自動で詰めるか

const height = ref(170)
const draggable = ref(true)
const resizable = ref(true)
const colNum = ref(6)

const layoutItems = ref([])

onMounted(() => {
    console.log('onMounted')
    console.log(gridLayout)

    gridLayout.forEach((d) => {
        layoutItems.value.push({
            i: d.grid_id,
            x: d.x,
            y: d.y,
            w: d.w,
            h: d.h,
            grid_id: d.grid_id,
            name: d.name,
            type: d.type,
        })
    })
})

const resizedEvent = (i, newX, newY, newPxHeight, ewPxWidth) => {
    //layout.value[i].gridItemHeight = newPxHeight
}

const removeItem = (id: string) => {}
</script>

<template>
    <Head title="ダッシュボード" />
    <AppLayout>
        <v-container>
            <div>
                <GridLayout
                    v-model:layout="layoutItems"
                    :row-height="height"
                    :col-num="colNum"
                    :is-draggable="draggable"
                    :is-resizable="resizable"
                    :vertical-compact="varticalCompact"
                >
                    <GridItem
                        v-for="(item, index) in layoutItems"
                        :key="item.i"
                        :x="item.x"
                        :y="item.y"
                        :w="item.w"
                        :h="item.h"
                        :i="item.i"
                        @resized="resizedEvent"
                    >
                        <v-card class="fill-height">
                            <v-card-title
                                class="d-flex justify-between"
                                style="color: white; background-color: skyblue"
                            >
                                <div>
                                    {{ item.name }}
                                </div>
                                <v-btn
                                    variant="text"
                                    size="x-small"
                                    icon="mdi-close-thick"
                                    @click="removeItem(item.i)"
                                ></v-btn>
                            </v-card-title>
                            <v-card-text>
                                <template v-if="item.type == 'pie'">
                                    <DashboardPieChart />
                                </template>
                                <template v-else>
                                    <div
                                        class="d-flex justify-center align-end height-fill pt-11"
                                    >
                                        テキスト
                                    </div>
                                </template>
                            </v-card-text>
                        </v-card>
                    </GridItem>
                </GridLayout>
            </div>
        </v-container>
    </AppLayout>
</template>
