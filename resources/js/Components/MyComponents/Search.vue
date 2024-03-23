<script setup>
    import { reactive, ref, onMounted } from 'vue'

    const doctors = [
        { id: '000256', name: 'doctor_00256' },
        { id: '001150', name: 'doctor_001150' }
    ]
    
    
</script>
<template>
    <v-sheet max-width="300px">
        <v-select v-model="form.selectedDoctors" :items="doctors" item-title="name" item-value="id" multiple></v-select>
    </v-sheet>
</template>
