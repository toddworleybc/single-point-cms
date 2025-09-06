<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import TableCreate from '@/Components/TableCreate.vue';


let clients = usePage().props.users;


</script>

<template>
    <Head title="Clients" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Clients
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                       <TableCreate v-if="clients && clients.length" :tableData="clients" routeLink="admin.show.client" viewLinkText="View Client" :tableFilters="['name', 'email']" />
                         <p v-else class="font-bold text-red-600">No Clients Registered</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
