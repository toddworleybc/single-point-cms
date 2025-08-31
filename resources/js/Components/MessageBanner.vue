<script setup>
import MessageControl from '@/utilities/MessageControl';
import { computed } from 'vue';



const classObj = computed( () => ({
    'bg-green-600': MessageControl.type === 'success',
    'bg-red-600': MessageControl.type === 'error',
    'bg-yellow-600': MessageControl.type === 'warning',
    'bg-blue-600': MessageControl.type === 'info'
} ));



function closeBanner() {
    MessageControl.message = "",
    MessageControl.type = "",
    MessageControl.pulse = false
}//


</script>

<template>
    <div @click.prevent="closeBanner" v-if="MessageControl.message && MessageControl.type" :class="classObj" class="text-center cursor-pointer capitalize items-center mt-6 text-white text-lg py-2 px-4 rounded-sm mx-auto max-w-6xl">
        
        <p :class="{'animate-pulse' : MessageControl.pulse }">{{ MessageControl.message }}</p>
      

    </div>
</template>
