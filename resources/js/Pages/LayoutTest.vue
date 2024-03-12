<script setup>
import MyLayout from "@/Layouts/MyLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>
<template>
    <Head title="Layout Test" />

    <MyLayout>
        <v-main>
            <v-container>
                <v-card
                    height="200px"
                    max-width="500px"
                    color="blue-lighten-5"
                    tonal
                >
                    <v-card-title>Layout Test Page</v-card-title>
                    <v-card-text>
                        ヘッダ（v-app-barとv-navigation-drawer）とフッターをLayoutに配置した
                    </v-card-text>
                </v-card>
            </v-container>
        </v-main>
    </MyLayout>
</template>
