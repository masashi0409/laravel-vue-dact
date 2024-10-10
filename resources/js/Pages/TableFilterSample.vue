<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, ref, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

// const { accessItems } = defineProps({
//   accessItems: Array,
// })

const accessItems = [
  {
    id: 1,
    access_datetime: '2024-09-16 10:36:00',
    access_screen: 'アクセス履歴',
    user_id: 'shimano',
    user_name: 'しまの',
    role: '管理者',
  },
  {
    id: 2,
    access_datetime: '2024-09-16 09:00:00',
    access_screen: '一覧',
    user_id: 'test',
    user_name: 'テスト',
    role: '管理者',
  },
  {
    id: 3,
    access_datetime: '2024-09-15 10:36:00',
    access_screen: 'アクセス履歴',
    user_id: 'tester',
    user_name: 'テスタ',
    role: '一般者',
  },
  {
    id: 4,
    access_datetime: '2024-09-16 10:36:00',
    access_screen: 'ダッシュボード',
    user_id: 'shimano',
    user_name: 'しまの',
    role: '一般者',
  },
]

const headers = ref([
  {
    title: 'ID',
    align: 'center',
    key: 'id',
    filterable: false,
    filter: 'text', // 複数日付選択フィルターにしたい？
    isFiltered: false,
    sortable: true,
    isSorted: false,
    sortOrderBy: 'asc',
  },
  {
    title: 'アクセス日時',
    align: 'center',
    key: 'access_datetime',
    filterable: false,
    filter: 'dates', // 複数日付選択フィルターにしたい？
    isFiltered: false,
    sortable: true,
    isSorted: false,
    sortOrderBy: 'asc',
  },
  {
    title: 'アクセス画面',
    align: 'center',
    key: 'access_screen',
    filterable: true,
    filter: 'text', // 項目からマルチ選択フィルター？
    isFiltered: false,
    sortable: true,
    isSorted: false,
    sortOrderBy: 'asc',
  },
  {
    title: 'ユーザID',
    align: 'center',
    key: 'user_id',
    filterable: true,
    filter: 'text', // テキストフィルター
    isFiltered: false,
    sortable: true,
    isSorted: false,
    sortOrderBy: 'asc',
  },
  {
    title: 'ユーザ名',
    align: 'center',
    key: 'user_name',
    filterable: true,
    filter: 'text',
    isFiltered: false,
    sortable: true,
    isSorted: false,
    sortOrderBy: 'asc',
  },
  {
    title: '権限',
    align: 'center',
    key: 'role',
    filterable: false,
    filter: 'multi-select',
    isFiltered: false,
    sortable: false,
    isSorted: false,
    sortOrderBy: 'asc',
  },
])

const tableItems = ref(accessItems)
const filteredItems = ref([...tableItems.value])
const sortedItems = ref([...filteredItems.value])

const loading = ref(false)
const headerTitle = (title) => {
  return title + 'フィルタ'
}

const filterInputs = ref([]) // フィルタの入力値

// テキストフィルタ 部分一致
const textFilter = (item, key, input) => {
  console.log(item[key])
  console.log(input)
  console.log(item[key].includes(input))
  if (item[key]) {
    return item[key].includes(input)
  }
}

const execFilter = (columns) => {
  // フィルタリセット
  headers.value.forEach((h) => (h.isFiltered = false))
  // ソートリセット
  columns.forEach((c) => (c.isSorted = false))

  filteredItems.value = []
  loading.value = true

  console.log(filterInputs.value)
  console.log(filterInputs.value.length > 0)

  if (filterInputs.value.length > 0) {
    filteredItems.value = tableItems.value.filter((item) => {
      return filterInputs.value.every((filterInput, index) => {
        headers.value[index].isFiltered = true
        if (headers.value[index].filter === 'text') {
          return textFilter(item, headers.value[index].key, filterInput)
        }
      })
    })
  } else {
    filteredItems.value = [...tableItems.value]
    sortedItems.value = [...filteredItems.value]
  }
  console.log(headers.value)
  sortedItems.value = filteredItems.value
  loading.value = false
}

const clearFilterInput = (index, columns) => {
  // filterInputs.value[index] = ''
  delete filterInputs.value[index]
  execFilter(columns)
}

const toggleSort = (column, columns) => {
  // ソートリセット
  columns.forEach((c) => {
    if (c.key != column.key) c.isSorted = false
  })
  /// console.log(column)
  // console.log(column.isSorted)
  // console.log(column.sortOrderBy)
  if (column.isSorted) {
    if (column.sortOrderBy === 'asc') {
      column.sortOrderBy = 'desc'
    } else if (column.sortOrderBy === 'desc') {
      // desc→ソートなし
      column.isSorted = false
      column.sortOrderBy = 'asc'
      sortedItems.value = [...filteredItems.value]
      return true
    }
  } else {
    // ソートなし→asc
    column.isSorted = true
    column.sortOrderBy = 'asc'
  }

  // console.log('↓')
  // console.log(column.isSorted)
  // console.log(column.sortOrderBy)
  sort(column)
}

const sort = (column) => {
  if (column.sortOrderBy === 'asc') {
    sortedItems.value.sort((a, b) => {
      if (a[column.key] > b[column.key]) {
        return 1
      } else {
        return -1
      }
    })
  } else {
    // desc
    sortedItems.value.sort((a, b) => {
      if (a[column.key] < b[column.key]) {
        return 1
      } else {
        return -1
      }
    })
  }
  //console.log(filteredItems.value)
}
</script>

<template>
  <Head title="テーブルフィルタサンプル" />
  <AppLayout>
    <v-container>
      <v-card>
        <v-card-title class="mb-2 text-center bg-secondary">アクセス履歴</v-card-title>
        <v-card-text>
          <!-- 一覧 -->
          <v-data-table
            :headers="headers"
            :items="sortedItems"
            :fixed-header="true"
            items-per-page-text="表示件数"
            no-data-text="データがありませんでした"
            class="daily-data-table scrollable-table"
            density="compact"
            :loading="loading"
            t
            :items-per-page="10"
          >
            <!-- ローディング -->
            <template v-slot:loading>
              <v-progress-circular
                indeterminate
                :size="40"
                color="primary"
                class="mt-4"
              ></v-progress-circular>
              <div class="mx-auto m-4 text-body-1">データ取得中です</div>
            </template>

            <!-- v-data-tableのtopに表示 -->
            <template v-slot:top="{ pagination, options, updateOptions }">
              <div class="flex">
                <v-data-table-footer
                  :pagination="pagination"
                  :options="options"
                  @update:options="updateOptions"
                  items-per-page-text="表示件数"
                  class="me-auto"
                />
              </div>
            </template>

            <template v-slot:headers="headersSlot">
              <template v-if="headersSlot.columns.length > 0">
                <template v-for="(column, index) in headersSlot.columns">
                  <th>
                    <tr>
                      {{ column.title }}
                      <!-- filter -->
                      <i
                        v-if="column.filterable"
                        class="header-column-button mdi-filter-variant mdi filter-icon mr-1 v-icon v-icon--size-default"
                        :class="{ filterOn: column.isFiltered }"
                      >
                        <template v-if="column.filter == 'text'">
                          <v-menu
                            activator="parent"
                            location="right"
                            transition="fade-transition"
                            :close-on-content-click="false"
                            class="filter-menu"
                            max-height="300"
                          >
                            <div style="background-color: white; width: 280px">
                              <v-text-field
                                v-model="filterInputs[index]"
                                class="pa-4"
                                type="text"
                                :label="headerTitle(column.title)"
                                variant="outlined"
                              ></v-text-field>
                              <!--@update:modelValue="textChange"-->
                              <v-btn
                                small
                                color="outline"
                                class="ml-2 mb-2"
                                @click="clearFilterInput(index, headersSlot.columns)"
                              >
                                clear
                              </v-btn>
                              <v-btn
                                small
                                color="primary"
                                class="ml-2 mb-2"
                                @click="execFilter(headersSlot.columns)"
                              >
                                filter
                              </v-btn>
                            </div>
                          </v-menu>
                        </template>
                      </i>

                      <!-- Sort -->
                      <template v-if="column.sortable">
                        <template v-if="column.isSorted">
                          <i
                            v-if="column.sortOrderBy === 'asc'"
                            @click="toggleSort(column, headersSlot.columns)"
                            class="header-column-button mdi-sort-ascending mdi v-icon notranslate v-theme--light v-icon--size-default v-data-table-header__sort-icon"
                            aria-hidden="true"
                          ></i>
                          <i
                            v-if="column.sortOrderBy === 'desc'"
                            @click="toggleSort(column, headersSlot.columns)"
                            class="header-column-button mdi-sort-descending mdi v-icon notranslate v-theme--light v-icon--size-default v-data-table-header__sort-icon"
                            aria-hidden="true"
                          ></i>
                        </template>
                        <template v-else>
                          <i
                            @click="toggleSort(column, headersSlot.columns)"
                            class="header-column-button mdi-sort mdi v-icon notranslate v-theme--light v-icon--size-default v-data-table-header__sort-icon"
                          ></i>
                        </template>
                      </template>
                    </tr>
                  </th>
                </template>
              </template>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-container>
  </AppLayout>
</template>

<style>
.filterOn {
  color: rgb(13, 150, 143);
}
</style>
