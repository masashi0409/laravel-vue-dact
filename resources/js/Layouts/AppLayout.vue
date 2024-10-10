<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const drawer = ref(false)
</script>
<template>
  <v-app>
    <v-app-bar color="primary">
      <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-app-bar-title>Sample-Laravel-Vue</v-app-bar-title>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" disable-resize-watcher>
      <v-list>
        <v-list-item
          title="ダッシュボード"
          value="top"
          color="primary"
          variant="tonal"
          @click="router.visit('/grid-layout')"
        >
          <template v-slot:prepend>
            <v-icon icon="mdi-view-dashboard-outline" color="primary"></v-icon>
          </template>
        </v-list-item>
        <v-list-item
          title="Pinia Sample"
          value="top"
          color="primary"
          variant="tonal"
          @click="router.visit('/pinia-sample')"
        >
          <template v-slot:prepend>
            <v-icon icon="mdi-view-dashboard-outline" color="primary"></v-icon>
          </template>
        </v-list-item>
        <v-list-item
          title="算定支援"
          value="top"
          color="primary"
          variant="tonal"
          @click="router.visit('/')"
        >
          <template v-slot:prepend>
            <v-icon icon="mdi-chart-line" color="primary"></v-icon>
          </template>
        </v-list-item>
        <v-list-item
          title="予約患者"
          value="top"
          color="primary"
          variant="tonal"
          @click="router.visit('/reservation')"
        >
          <template v-slot:prepend>
            <v-icon icon="mdi-clipboard-list-outline" color="primary"></v-icon>
          </template>
        </v-list-item>
        <v-list-item
          title="在院患者"
          value="top"
          color="primary"
          variant="tonal"
          @click="router.visit('/inpatient')"
        >
          <template v-slot:prepend>
            <v-icon icon="mdi-clipboard-list-outline" color="primary"></v-icon>
          </template>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-main>
      <slot />
    </v-main>
  </v-app>
</template>
