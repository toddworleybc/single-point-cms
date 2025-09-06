<script setup>
import { computed } from 'vue';
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
    },
    changeAcessHeading: {
        type: String,
        default: 'Access'
    },
    tdClass: {
        type: String,
        default: ''
    },
    thClass: {
        type: String,
        default: ''
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
    <div class="overflow-x-auto">

        <table class="table-auto border-collapse border border-gray-400 w-full">

            <thead>
                <tr>
                    <th :class="thClass" v-for="(heading, i) in headings" :key="i">
                        
                        {{ heading }}
                    </th>
                    <th :class="thClass" v-if="routeLink || emitSingle">{{ changeAcessHeading }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(tableRow, i) in filteredTable" :key="i">

                    <td :class="tdClass" v-for="(data, key, j) in tableRow" :key="j">{{ 
                        nullValue !== undefined && 
                        nullValue.hasOwnProperty(key) ? nullValue[key] : data }}</td>

                    <td :class="tdClass" v-if="routeLink"><a :href="route(routeLink, props.tableData[i]['id'])">{{ viewLinkText ? viewLinkText : "View" }}</a></td>

                    <td :class="tdClass" v-if="emitSingle"><a @click.prevent="singleToParent(props.tableData[i])" href="#">{{ viewLinkText ? viewLinkText : "View" }}</a></td>

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
    a:link, a {
        @apply text-blue-800 hover:text-blue-400 active:text-green-700 visited:text-blue-700
    }
   
</style>
