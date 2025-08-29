<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';


const clients = usePage().props;


console.log(clients);









</script>

<template>
    <Head title="Dashboard" />

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
                       <Table 
                        :headings="['Name', 'Email', 'Created At']" 
                        :rows="['John Doe', 'jdoe@gmail.com', '06/06/2024']" />
                            
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
