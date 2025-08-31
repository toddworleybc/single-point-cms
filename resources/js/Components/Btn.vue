<script setup>

 import { computed } from 'vue';

 const { type } = defineProps({
    type: String
 });

 const btnClass = computed( () => ({
    'bg-blue-500' : !type,
    'hover:bg-blue-700' : !type,
    'bg-green-700' : type === 'success',
    'hover:bg-green-800' : type === 'success',
    'bg-yellow-700' : type === 'warning',
    'hover:bg-yellow-800' : type === 'warning',
    'bg-red-600' : type === 'danger',
    'hover:bg-red-800' : type === 'danger',
 }) );



</script>
<template>

    <button :class="btnClass" class="text-white font-bold py-2 px-4 rounded m-1 active:bg-gray-800">
        <slot />
    </button>

</template>
