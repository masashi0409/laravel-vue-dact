<script setup>
import MyLayout from '@/Layouts/MyLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const doctors = [
    { id: 1, name: 'aaa' },
    { id: 2, name: 'bbb' },
    { id: 3, name: 'ccc' },
    { id: 4, name: 'ddd' },
    { id: 5, name: 'eee' },
    { id: 6, name: 'fff' },
    { id: 7, name: 'ggg' },
    { id: 8, name: 'hhh' },
]

const selectedDoctors = ref([])

const getData = () => {
    console.log(selectedDoctors.value)
}

const itemdata = ref([
    'Tokyo',
    'Osaka',
    'kyoto',
    'Aichi',
    'Fukuoka',
    'Sendai',
    'Sapporo',
    'Hiroshima',
])

const selected = ref([])
</script>
<template>
    <Head title="TestLayout" />

    <MyLayout>
        <v-container>
            <v-sheet class="w-50">
                <v-select
                    v-model="selectedDoctors"
                    :items="doctors"
                    item-title="name"
                    item-value="id"
                    multiple
                >
                </v-select>
            </v-sheet>

            <v-btn @click="getData">検索</v-btn>
        </v-container>

        <v-container>
            <v-select
                variant="outlined"
                :items="itemdata"
                label="Select"
                class="ma-2"
                v-model="selected"
            ></v-select>
            {{ selected }}
        </v-container>
    </MyLayout>
</template>
