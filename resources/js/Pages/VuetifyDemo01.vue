<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const drawer = ref();

const test = (arg) => {
    console.log("test", arg.id);
};
</script>

<template>
    <Head title="Vuetify Demo" />

    <v-app>
        <v-app-bar color="primary">
            <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer">
            </v-app-bar-nav-icon>

            <v-app-bar-title> Application </v-app-bar-title>

            <template v-slot:append>
                <v-btn> Sign-In </v-btn>

                <v-btn>
                    <v-icon> mdi-home </v-icon>
                </v-btn>
            </template>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer">
            <v-list @click:select="test">
                <v-list-item title="Home" value="home"></v-list-item>
                <v-list-item title="About" value="about"></v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <v-container>
                <v-sheet min-height="30em">
                    <p>Hoge 1</p>
                    <p>Hoge 2</p>
                    <p>Hoge 3</p>
                    <p>Hoge 4</p>
                    <p>Hoge 5</p>
                    <p>Hoge 1</p>
                    <p>Hoge 2</p>
                    <p>Hoge 3</p>
                    <p>Hoge 4</p>
                    <p>Hoge 5</p>
                </v-sheet>

                <div class="text-h6 mt-2">共通</div>
                <span>幅(width)の指定は有効</span><br />

                <div class="text-h6 mt-2">div</div>
                <div
                    height="200px"
                    max-width="500px"
                    class="bg-slate-100 rounded-lg"
                    elevation="3"
                >
                    <span>height 指定は無視される</span><br />
                    <span>max-width 指定は無視される</span><br />
                    <span>デフォルトでpaddingが設定されていない</span><br />
                    <span>影(elevation)の指定ができない</span><br />
                    <span>背景色、文字色の指定は class で行う</span><br />
                    <span>角(rounded)の指定は有効</span><br />
                </div>

                <div class="text-h6 mt-2">v-container</div>
                <v-container
                    height="200px"
                    max-width="500px"
                    class="bg-sky-100 rounded-lg"
                    elevation="3"
                >
                    <span>height 指定は無視される</span><br />
                    <span>max-width 指定は無視される</span><br />
                    <span>デフォルトでpaddingが設定されている</span><br />
                    <span>影(elevation) の指定ができない</span><br />
                    <span>背景色、文字色の指定は class で行う</span><br />
                    <span>角(rounded)の指定は有効</span><br />
                </v-container>

                <div class="text-h6 mt-2">v-sheet</div>
                <v-sheet
                    height="200px"
                    max-width="500px"
                    class="bg-red-lighten-5 rounded-lg"
                    elevation="3"
                >
                    <span>height 指定は有効</span><br />
                    <span>max-width 指定は有効</span><br />
                    <span>デフォルトでpaddingが設定されていない</span><br />
                    <span>影(elevation) の指定ができる</span><br />
                    <span>背景色、文字色の指定は class で行う</span><br />
                    <span>角(rounded)の指定は有効</span><br />
                </v-sheet>

                <div class="text-h6 mt-2">v-card</div>
                <v-card
                    height="200px"
                    max-width="500px"
                    color="blue-lighten-5"
                    tonal
                >
                    <span>height 指定は有効</span><br />
                    <span>max-width 指定は有効</span><br />
                    <span>デフォルトでpaddingが設定されていない</span><br />
                    <span
                        >色の指定は
                        <b>color</b>
                        で行う（背景色、文字色は自動で決まる）</span
                    ><br />
                    <span
                        >影(elevation)の指定はできるが variant
                        と競合することがある</span
                    ><br />
                    <span
                        >角(rounded)は指定はできるが variant
                        と競合することがある</span
                    ><br />
                </v-card>
            </v-container>
        </v-main>

        <v-footer color="primary"> (c) marcie </v-footer>
    </v-app>
</template>
