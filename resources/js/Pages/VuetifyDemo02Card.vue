<script setup>
import MyLayout from "@/Layouts/MyLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const variants = ref([
    "elevated",
    "flat",
    "tonal",
    "outlined",
    "text",
    "plain",
]);
</script>
<template>
    <Head title="TestLayout" />

    <MyLayout>
        <v-container>
            <v-row>
                <v-col cols="4" v-for="val in variants">
                    <v-card :variant="val" color="blue-grey-darken-1" tonal>
                        <v-card-title>平家物語</v-card-title>
                        <v-card-subtitle>{{ val }}</v-card-subtitle>
                        <v-divider thickness="3"></v-divider>
                        <v-card-text>
                            祇園精舎の鐘の声、諸行無常の響きあり。
                            娑羅双樹の花の色、盛者必衰の理をあらわす。
                            おごれる人も久しからず、唯、春の夜の夢のごとし。
                            猛きものもついにはほろびぬ、 偏に風の前の塵に同じ。
                        </v-card-text>
                        <v-divider thickness="3"></v-divider>
                        <v-card-actions>
                            <v-btn :variant="val">源氏を選択</v-btn>
                            <v-btn :variant="val">平家を選択</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="4">
                    <v-card
                        variant="elevated"
                        prepend-icon="mdi-book-open-variant"
                        title="平家物語"
                        subtitle="prop で指定した場合"
                    >
                        <v-card-text>
                            祇園精舎の鐘の声、諸行無常の響きあり。
                            娑羅双樹の花の色、盛者必衰の理をあらわす。
                            おごれる人も久しからず、唯、春の夜の夢のごとし。
                            猛きものもついにはほろびぬ、 偏に風の前の塵に同じ。
                        </v-card-text>
                        <v-card-actions>
                            <v-btn>
                                <v-icon
                                    icon="mdi-flag-outline"
                                    color="black"
                                    class="me-2"
                                ></v-icon>
                                源氏を選択
                            </v-btn>
                            <v-btn>
                                <v-icon
                                    icon="mdi-flag-variant"
                                    color="red-darken-4"
                                    class="me-2"
                                ></v-icon>
                                平家を選択
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
                <v-col cols="4">
                    <v-card variant="elevated">
                        <template v-slot:prepend>
                            <v-icon
                                icon="mdi-book-open-variant"
                                color="primary"
                            ></v-icon>
                        </template>
                        <template v-slot:title> 平家物語 </template>
                        <template v-slot:subtitle>
                            v-slot で指定した場合
                        </template>
                        <v-card-text>
                            祇園精舎の鐘の声、諸行無常の響きあり。
                            娑羅双樹の花の色、盛者必衰の理をあらわす。
                            おごれる人も久しからず、唯、春の夜の夢のごとし。
                            猛きものもついにはほろびぬ、 偏に風の前の塵に同じ。
                        </v-card-text>
                        <v-card-actions>
                            <v-btn>
                                <v-icon
                                    icon="mdi-flag-outline"
                                    color="black"
                                    class="me-2"
                                ></v-icon>
                                源氏を選択
                            </v-btn>
                            <v-btn>
                                <v-icon
                                    icon="mdi-flag-variant"
                                    color="red-darken-4"
                                    class="me-2"
                                ></v-icon>
                                平家を選択
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MyLayout>
</template>
