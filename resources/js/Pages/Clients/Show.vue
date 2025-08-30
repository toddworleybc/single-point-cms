<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';

const client = usePage().props.client;

const form = useForm({
    message: '',
});





// Function to toggle sections
function toggleSections(e) {
    const btnClicked = e.target.id;
    const photoSection = document.getElementById('client-photos');
    const fileSection = document.getElementById('client-files');
    const messagingSection = document.getElementById('client-messaging');

   if(btnClicked === 'docPhotos') {
        photoSection.style.display = 'block';
        fileSection.style.display = 'none';
        messagingSection.style.display = 'none';
   } else if (btnClicked === 'docFiles') {
        photoSection.style.display = 'none';
        fileSection.style.display = 'block';
        messagingSection.style.display = 'none';
   } else if (btnClicked === 'clientMessaging') {
        messagingSection.style.display = 'block';
        photoSection.style.display = 'none';
        fileSection.style.display = 'none';
   }


}// Add event listeners to buttons


function toggleMessages(e) {
    const btnClicked = e.target.id;
    console.log(btnClicked);
}


</script>

<template>
    <Head :title="client.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                {{ client.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">

                            <div id="documents" class="w-full border border-gray-300 p-4 m-2">
                                <section class="flex justify-between mb-4 border-bottom border-gray-300 border-b-2 pb-4">
                                    <div>
                                        <button id="clientMessaging" class="messageTabBtn" @click="toggleSections">Messaging</button>
                                    </div>
                                    <div>
                                        <button id="docFiles" class="docTabBtn" @click="toggleSections">Documents</button>
                                         <button id="docPhotos" class="docTabBtn" @click="toggleSections">Photos</button>
                                    </div>
                                    
                                    
                                </section>

                                <section id="client-photos" style="display:none">
                                    Photos
                                </section>

                               <section id="client-files" style="display:none">
                                    Files
                               </section>

                               <section id="client-messaging">
                                  
                                   <button class="docTabBtn">+ Create Message</button>
                                   <h2 class="mt-6 text-lg"><strong>Messages Received from {{ client.name }}</strong></h2>
                                   <div class="border-b-2 border-gray-300 pb-4 my-4">
                                        <h3>- New Messages -</h3>

                                        </div>
                                        <div class="border-b-2 border-gray-300 pb-4 mb-4">
                                            <h3>- Old Messages -</h3>

                                    </div>
                                

                               </section>

                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
    .docTabBtn {
        @apply bg-blue-500 text-white font-bold py-2 px-4 rounded m-1;
    }
    .docTabBtn:hover {
        @apply bg-blue-700;
    }
    .docTabBtn:active {
        @apply bg-gray-800;
    }
    .messageTabBtn {
        @apply bg-green-700 text-white font-bold py-2 px-4 rounded m-1;
    }
    .messageTabBtn:hover {
        @apply bg-green-800;
    }
    .messageTabBtn:active {
        @apply bg-gray-800;
    }
</style>
