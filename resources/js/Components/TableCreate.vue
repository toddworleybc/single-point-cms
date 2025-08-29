<script setup>
import { defineProps } from 'vue';


const { tableData } = defineProps({
    tableData: {
        type: Array,
        required: true
    }
});


const headings = Object.getOwnPropertyNames(tableData[0]);



</script>

<template>
    <div>

        <table class="table-auto border-collapse border border-gray-400 w-full">

            <thead>
                <tr>
                    <th v-for="(heading, i) in headings" :key="i">
                        {{ heading }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(tableRow, i) in tableData" :key="i">
                    <td v-for="(data, j) in tableRow" :key="j">{{ data ? data : "null" }}</td>
                </tr>
            </tbody>
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