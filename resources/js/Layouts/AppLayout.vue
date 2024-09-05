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
</script>
<template>
    <v-app>
        <v-app-bar color="primary">
            <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer">
            </v-app-bar-nav-icon>

            <v-app-bar-title> Sample-Laravel-Vue </v-app-bar-title>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" disable-resize-watcher>
            <v-list @click:select="clickLink">
                <v-list-item
                    title="トップ"
                    value="top"
                    color="primary"
                    variant="tonal"
                >
                    <template v-slot:prepend>
                        <v-icon icon="mdi-home" color="primary"></v-icon>
                    </template>
                </v-list-item>
                <v-list-item
                    title="外来予約リスト"
                    value="reservation"
                    color="primary"
                    variant="tonal"
                >
                    <template v-slot:prepend>
                        <v-icon
                            icon="mdi-clipboard-list-outline"
                            color="primary"
                        ></v-icon>
                    </template>
                </v-list-item>
                <v-list-item
                    title="在院患者リスト"
                    value="inpatient"
                    color="primary"
                    variant="tonal"
                >
                    <template v-slot:prepend>
                        <v-icon
                            icon="mdi-clipboard-list-outline"
                            color="primary"
                        ></v-icon>
                    </template>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <slot />
        </v-main>
    </v-app>
</template>
