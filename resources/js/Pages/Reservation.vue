<script setup>
    import { Head } from '@inertiajs/vue3';
    import { reactive, ref, onMounted } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import ReservationList from '@/Components/MyComponents/ReservationList.vue'

    onMounted(() => {
        getReservationData()
    })

    const doctors = [
        { id: '000256', name: 'doctor_00256' },
        { id: '001150', name: 'doctor_001150' }
    ]

    // 検索条件form params
    const form = reactive({
        selectedDoctors: [],
        scene: 'reservation',
    })

    const reservationData = reactive({})

    const getReservationData = async () => {
        console.log('reservation get data')
        try {
            await axios.get('/api/reservations/', {
                    params: {
                        doctors: form.selectedDoctors,
                        scene: form.scene
                    }
                })
                .then(res => {
                    reservationData.data = res.data.data
                    console.log(reservationData.data)
                })
        }
        catch (e) {
            console.log(e)
        }
    }
</script>

<template>

    <Head title="Reservation List" />

    <AppLayout>
        <v-container>
            <div class="text-h5">外来予約リスト</div>
        </v-container>

        <!--検索-->
        <v-container>
            <v-sheet max-width="300px">
                <v-select v-model="form.selectedDoctors" :items="doctors" item-title="name" item-value="id" multiple></v-select>
            </v-sheet>

            <v-btn elevation="2" @click="getReservationData">検索</v-btn>
        </v-container>

        <!--外来予約リスト-->
        <v-container>
            <ReservationList :data="reservationData.data" />
        </v-container>
    </AppLayout>
</template>
