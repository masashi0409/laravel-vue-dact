<script setup>
import MyLayout from '@/Layouts/MyLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const message = ref('')

const clickButton = (arg) => {
    message.value = arg
}

const isLoading = ref(false)
const load = () => {
    isLoading.value = true
}

const cancel = () => {
    isLoading.value = false
}

const clickLink = (routeName) => {
    console.log(routeName)
    router.visit(route(routeName))
}
</script>
<template>
    <Head title="Buttons" />

    <MyLayout>
        <v-container>
            <v-btn @click="clickButton('submit')">Submit</v-btn>
            <v-btn @click="clickButton('cancel')">Cancel</v-btn>
        </v-container>
        {{ message }}

        <div class="d-flex justify-space-between ma-2 pa-2">
            <v-btn color="primary">default</v-btn>
            <v-btn color="primary" variant="outlined">outlined</v-btn>
            <v-btn color="primary" variant="text">text</v-btn>
            <v-btn color="primary" variant="flat">flat</v-btn>
            <v-btn color="primary" icon="mdi-home"></v-btn>
        </div>

        <div class="d-flex justify-space-between bg-black ma-2 pa-2">
            <v-btn>default</v-btn>
            <v-btn variant="outlined">outlined</v-btn>
            <v-btn variant="text">text</v-btn>
            <v-btn variant="flat">flat</v-btn>
            <v-btn icon="mdi-home"></v-btn>
        </div>

        <div class="d-flex justify-space-between bg-white ma-2 pa-2">
            <v-btn color="black">default</v-btn>
            <v-btn color="black" variant="outlined">outlined</v-btn>
            <v-btn color="black" variant="text">text</v-btn>
            <v-btn color="black" variant="flat">flat</v-btn>
            <v-btn color="black" icon="mdi-home"></v-btn>
        </div>

        <div class="justify-space-between ma-2 pa-2">
            <v-btn color="black">
                <v-icon> mdi-home </v-icon>
            </v-btn>
            アイコンで四角ボタン
        </div>

        <!-- アイコンとテキストを同時に表示 -->
        <v-container>
            <v-btn prepend-icon="mdi-check-circle">
                <template v-slot:prepend>
                    <v-icon color="success"></v-icon>
                </template>
                Success
            </v-btn>
        </v-container>
        <v-container>
            <v-btn append-icon="mdi-check-circle">
                <template v-slot:append>
                    <v-icon color="warning"></v-icon>
                </template>
                warning
            </v-btn>
        </v-container>

        <!-- ロード中みたいなボタン -->
        <v-container>
            <v-btn @click="load" :loading="isLoading" class="ma-2">
                Load
            </v-btn>
            <v-btn @click="cancel" :disabled="!isLoading" class="ma-2">
                Cancel
            </v-btn>
        </v-container>

        <!-- ボタンのサイズ -->
        <v-sheet border class="my-4 py-4">
            <v-row align="center" justify="center">
                <v-col cols="auto">
                    <v-btn size="x-small">Extra small Button</v-btn>
                </v-col>

                <v-col cols="auto">
                    <v-btn size="small">Small Button</v-btn>
                </v-col>

                <v-col cols="auto">
                    <v-btn>Regular Button</v-btn>
                </v-col>

                <v-col cols="auto">
                    <v-btn size="large">Large Button</v-btn>
                </v-col>

                <v-col cols="auto">
                    <v-btn size="x-large">X-Large Button</v-btn>
                </v-col>
            </v-row>
        </v-sheet>

        <v-container>
            <div class="text-h5">Linkボタン</div>
            <!-- △ aタグ（文字のところ）クリックしないと遷移しない -->
            <v-btn class="ma-2">
                <a href="https://next.vuetifyjs.com/en/components/buttons/">
                    Vuetify3 Button
                </a>
            </v-btn>

            <!-- ふつうのaタグならこれでよい -->
            <v-btn
                class="ma-2"
                href="https://next.vuetifyjs.com/en/components/buttons/"
            >
                Vuetify3 Button
            </v-btn>

            <!-- inertiaのリンクコンポーネント -->
            <Link as="button" href="route('vuetifydemo02card')">test</Link>

            <!-- リンク（文字のところ）クリックしないと遷移しない -->
            <v-btn class="ma-2">
                <Link :href="route('vuetifydemo02card')">test</Link>
            </v-btn>

            <!-- ページごとロードされてしまう -->
            <v-btn class="ma-2" :href="route('vuetifydemo02card')">
                vuetifydemo02card
            </v-btn>

            <!-- × -->
            <!-- <Link as="v-btn" :href="route('vuetifydemo02card')">
                vuetifydemo02card
            </Link> -->
            <div>リンクボタン、vuetifyのv-btnをつかいながらinertiaのroute</div>
            <div>
                <v-btn
                    class="ma-2"
                    @click.prevent="clickLink('vuetifydemo02card')"
                >
                    vuetifydemo02card
                </v-btn>
            </div>
        </v-container>
    </MyLayout>
</template>
