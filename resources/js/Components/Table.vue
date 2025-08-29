<script setup>
import { defineProps } from 'vue';


defineProps({
    headings: {
        type: Array,
        required: true,
    },
    
});



</script>

<template>
    <div>

        <table class="table-auto border-collapse border border-gray-400">

          

        </table>
    </div>
</template>

<style>
    th, td {
        border: 1px solid gray;
        padding: 8px;
        text-align: left;
    }
</style>