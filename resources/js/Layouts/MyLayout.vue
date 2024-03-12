<script setup>
import { ref } from 'vue'

const drawer = ref(false)

const test = (arg) => {
    console.log('test', arg.id)
}
</script>
<template>
    <v-app>
        <v-app-bar color="primary">
            <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer">
            </v-app-bar-nav-icon>

            <v-app-bar-title> Vuetify Demo </v-app-bar-title>

            <template v-slot:append>
                <!-- <v-btn> Sign-In </v-btn> -->
                <!-- <v-btn>
                    <v-icon> mdi-home </v-icon>
                </v-btn> -->
            </template>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer">
            <v-list @click:select="test">
                <v-list-item title="Home" value="home"></v-list-item>
                <v-list-item title="About" value="about"></v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <slot />
        </v-main>
        <!-- <v-footer color="primary"> (c) marcie </v-footer> -->
    </v-app>
</template>
