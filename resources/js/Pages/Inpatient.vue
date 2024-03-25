<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import InpatientList from '@/Components/MyComponents/InpatientList.vue'

const {
    scenarios,
    extractingDate, // 最新更新日 2022-08-25
} = defineProps({
    scenarios: Array,
    extractingDate: String,
})

onMounted(() => {
    getInpatientData()
})

const doctors = [
    { id: '000256', name: 'doctor_00256' },
    { id: '001150', name: 'doctor_001150' },
]

// 検索条件form params
const form = reactive({
    selectedDoctors: [],
    scene: 'inpatient',
})

const inpatientData = reactive({})

const getInpatientData = async () => {
    console.log('inpatient get data')
    try {
        await axios
            .get('/api/inpatients/', {
                params: {
                    doctors: form.selectedDoctors,
                    scene: form.scene,
                    extractingDate: extractingDate,
                },
            })
            .then((res) => {
                inpatientData.data = res.data.data
                console.log(inpatientData.data)
            })
    } catch (e) {
        console.log(e)
    }
}
</script>
<template>
    <Head title="在院患者リスト" />

    <AppLayout>
        <v-container>
            <div class="text-h5">在院患者リスト</div>
        </v-container>

        <!--検索-->
        <v-container>
            <v-sheet max-width="300px">
                <v-select
                    v-model="form.selectedDoctors"
                    :items="doctors"
                    item-title="name"
                    item-value="id"
                    multiple
                ></v-select>
            </v-sheet>

            <v-btn elevation="2" @click="getInpatientData">検索</v-btn>
        </v-container>

        <!--在院患者リスト-->
        <v-container>
            <InpatientList :data="inpatientData.data" />
        </v-container>
    </AppLayout>
</template>
