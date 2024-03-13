<script setup>
import { ref } from 'vue'

const headers = ref([
    { title: 'ID', key: 'id', align: 'start', sortable: true },
    { title: 'Textbox', key: 'textbox', sortable: true },
])
const items = ref([])
const itemsPerPage = ref(10)
const page = ref(1)
const total = ref(0)
const loading = ref(false)

const loadItems = async ({ page, itemsPerPage, sortBy }) => {
    console.log(page)
    console.log(itemsPerPage)
    console.log(sortBy)
    loading.value = true
    try {
        await axios
            .get('/api/data-table-item', {
                params: {
                    page: page,
                    sortBy: sortBy,
                    itemsPerPage: itemsPerPage,
                },
            })
            .then((res) => {
                let result = res.data.result
                console.log(result)
                console.log(result.total)
                items.value = result.data
                total.value = result.total
                // itemsPerPage.value = result.per_page
            })

        loading.value = false
    } catch (e) {
        console.log(e)
    }
}
</script>

<template>
    <div>
        <h1>data table items</h1>

        <div>
            <v-data-table-server
                :headers="headers"
                :items="items"
                :items-length="total"
                v-model:items-per-page="itemsPerPage"
                @update:options="loadItems"
                :loading="loading"
            >
            </v-data-table-server>
        </div>
    </div>
</template>
