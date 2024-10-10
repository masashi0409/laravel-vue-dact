<script setup>
import Counter from '@/Components/Counter.vue'
import { useCounterStore } from '@/Store/counter'
const counterStore = useCounterStore()
console.log(counterStore.count)
</script>
<template>
  <Head title="Layout Test" />

  <AppLayout>
    <v-container>
      <div class="text-h4 mb-4">Pinia Sample</div>
      <p>{{ counterStore.count }}</p>
      <v-btn @click="counterStore.count++" color="primary">+</v-btn>
    </v-container>

    <br />

    <Counter />
  </AppLayout>
</template>
