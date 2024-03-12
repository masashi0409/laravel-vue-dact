<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Vuetify Demo" />

    <v-app>
        <v-app-bar color="primary">
            <template v-slot:prepend>
                <v-app-bar-nav-icon></v-app-bar-nav-icon>
            </template>

            <v-app-bar-title> Application </v-app-bar-title>

            <template v-slot:append>
                <v-btn> Sign-In </v-btn>

                <v-btn>
                    <v-icon> mdi-home </v-icon>
                </v-btn>
            </template>
        </v-app-bar>
    </v-app>
</template>
