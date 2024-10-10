<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const drawer = ref(false)

const clickLink = (routeName) => {
  if (routeName.id === 'top') {
    router.visit('/')
  }
  if (routeName.id === 'reservation') {
    router.visit('/reservation')
  }
  if (routeName.id === 'inpatient') {
    router.visit('/inpatient')
  }
}

const link = (routeName) => {
  if (routeName === 'reservation') {
    router.visit('/reservation')
  }
}
</script>
<template>
  <v-app>
    <v-app-bar color="primary">
      <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

      <v-app-bar-title>Sample-Laravel-Vue</v-app-bar-title>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" disable-resize-watcher>
      <!-- <v-list @click:select="clickLink">
        <v-list-item title="トップ" value="top" color="primary" variant="tonal">
          <template v-slot:prepend>
          </template>
        </v-list-item>
        <v-list-item title="外来予約リスト" value="reservation" color="primary" variant="tonal">
          <template v-slot:prepend>
            <v-icon icon="mdi-clipboard-list-outline" color="primary"></v-icon>
          </template>
        </v-list-item>
        <v-list-item title="在院患者リスト" value="inpatient" color="primary" variant="tonal">
          <template v-slot:prepend>
            <v-icon icon="mdi-clipboard-list-outline" color="primary"></v-icon>
          </template>
        </v-list-item>
      </v-list> -->

      <v-list>
        <v-list-item key="1" value="1">
          <v-list-item-title>Menu1</v-list-item-title>
          <v-menu activator="parent" location="right" open-on-hover :close-on-content-click="false">
            <v-list>
              <v-list-item key="1-1" value="1-1">
                <v-list-item-title>Menu1-1</v-list-item-title>
                <v-menu
                  activator="parent"
                  location="right"
                  open-on-hover
                  :close-on-content-click="false"
                >
                  <v-list>
                    <v-list-item key="1-1-1" value="1-1-1" @click="link('reservation')">
                      <v-list-item-title>Menu1-1-1</v-list-item-title>
                    </v-list-item>
                    <v-list-item key="1-1-2" value="1-1-2">
                      <v-list-item-title>Menu1-1-2</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </v-list-item>
              <v-list-item key="1-2" value="1-2">
                <v-list-item-title>Menu1-1</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-list-item>
        <v-list-item key="2" value="2">
          <v-list-item-title>Menu2</v-list-item-title>
        </v-list-item>
        <v-list-item key="3" value="3">
          <v-list-item-title>Menu3</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-main>
      <slot />
    </v-main>
  </v-app>
</template>
