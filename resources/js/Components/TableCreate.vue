<script setup>
import { defineProps, defineEmits, computed } from 'vue';
// Props
const props = defineProps({
    tableData: {
        type: Array,
        required: true
    },
    emitSingle: {
        type: Boolean,
        default: false
    },
    routeLink: {
        type: String,
    },
    viewLinkText: {
        type: String
    },
    tableFilters: {
        type: Array,
        default: []
    },
    nullValue: {
        type: Object
    },
    changeHeading: {
        type: Object
    }
});
// Emits =====

const emit = defineEmits(['single-data']);

function singleToParent(singleData) {
    emit('single-data', singleData);
}

const filteredTable = computed(() => {
    if (props.tableFilters.length === 0) {
        return props.tableData;
    }
    return props.tableData.map(row => {
        let filteredRow = {};
        props.tableFilters.forEach(key => {
            filteredRow[key] = row[key] !== undefined ? row[key] : null;
        });
        return filteredRow;
    });
});



const headings = computed( () => {
    if(props.changeHeading === undefined) return Object.getOwnPropertyNames(filteredTable.value[0]);

    return Object.getOwnPropertyNames(filteredTable.value[0]).map( heading => {
        return props.changeHeading.hasOwnProperty(heading) ? props.changeHeading[heading] : heading;
    } );
} );



</script>

<template>
    <div>

        <table class="table-auto border-collapse border border-gray-400 w-full">

            <thead>
                <tr>
                    <th v-for="(heading, i) in headings" :key="i">
                        
                        {{ heading }}
                    </th>
                    <th v-if="routeLink">Access</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(tableRow, i) in filteredTable" :key="i">

                    <td v-for="(data, key, j) in tableRow" :key="j">{{ 
                        nullValue !== undefined && 
                        nullValue.hasOwnProperty(key) ? nullValue[key] : data }}</td>

                    <td v-if="routeLink"><a :href="route(routeLink, props.tableData[i]['id'])">{{ viewLinkText ? viewLinkText : "View" }}</a></td>

                    <td v-if="emitSingle"><a @click.prevent="singleToParent(props.tableData[i])" href="#">{{ viewLinkText ? viewLinkText : "View" }}</a></td>

                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
    th, td {
        border: 1px solid gray;
        padding: 8px;
        text-align: left;
    }
    a:link,  a:visited {
        color: blue;
    }
     a:hover {
        color: lightblue
    }
    a:active {
        color: green;
    }
</style>
