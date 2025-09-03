<script setup>


import { computed, defineProps } from 'vue';


const props = defineProps({
    control: {
        type: Object,
        required: true
    }
});




const classObj = computed( () => ({
    'bg-green-600': props.control.type === 'success',
    'bg-red-600': props.control.type === 'error',
    'bg-yellow-600': props.control.type === 'warning',
    'bg-blue-600': props.control.type === 'info'
} ));



function closeBanner() {
    props.control.message = "",
    props.control.type = "",
    props.control.pulse = false
}//


</script>

<template>
    <div @click.prevent="closeBanner" v-if="props.control.message && props.control.type" :class="classObj" class="text-center cursor-pointer capitalize items-center mt-6 text-white text-lg py-2 px-4 rounded-sm mx-auto max-w-6xl">
        
        <p :class="{'animate-pulse' : props.control.pulse }">{{ props.control.message }}</p>
      

    </div>
</template>
