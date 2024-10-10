<script setup>
import { useCounterStore } from '@/Store/counter'
const counterStore = useCounterStore()
</script>
<template>
  <v-container>
    <div class="text-h5 mb-4">Counter Component</div>
    <p>{{ counterStore.count }}</p>
    <v-btn @click="counterStore.count++" color="primary">+</v-btn>
  </v-container>
</template>
