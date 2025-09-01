<script setup>
import MessageControl from '@/Utilities/MessageControl';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref, reactive } from 'vue';
import Btn from '@/Components/Btn.vue';
import TableCreate from '@/Components/TableCreate.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const client = usePage().props.client;
const sentMessages = usePage().props.adminMessages;
const clientMessages = usePage().props.clientMessages;


const showMessageModal = ref(false);
const showSingleMessageModal = ref(false);
const viewSentMessages = ref(false);

const singleMessage = reactive({
    title: "",
    message: "",
    id: null,
    is_read: null
});

const newMessages = clientMessages.filter(msg => msg.is_read === 'Not Read');
const oldMessages = clientMessages.filter(msg => msg.is_read === 'Read');


const messageForm = useForm({
    message:  "",
    title: "",
    client_id: client.id,
    replied_to: null
});


function messageFormSubmit(e) {
 
        messageForm.post(route('lawyer.submit.message'), {
            onStart: () => {
                showMessageModal.value = false;
                MessageControl.message = "Sending Message...";
                MessageControl.type = 'warning';
            },
            onSuccess: (status) => {
                MessageControl.message = "Message sent successfully.";
                MessageControl.type = 'success';
            },
            onError: (e) => {
                MessageControl.message = e.message;
                MessageControl.type = 'error';
            },
            preserveState: false
        });

}// messageFormSubmit

const markReadForm = useForm({
    is_read: "Not Read",
    id: null
});

function markAsReadFormSubmit(e) {

    markReadForm.is_read = e.target.checked ? "Read" : "Not Read";

    markReadForm.id = singleMessage.id;

    markReadForm.post( route('lawyer.mark.as.read'), { preserveState: false } );

}

function singleMessageShow(message) {
    singleMessage.title = message.title;
    singleMessage.message = message.message;
    singleMessage.id = message.id;
    singleMessage.is_read = message.is_read;
    showSingleMessageModal.value = true;
}


function replyToMessage() {
    messageForm.replied_to = singleMessage.id;
    showSingleMessageModal.value = false;
    showMessageModal.value = true;
}// replyToMessage


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

</script>

<template>
    <Head :title="'Client - ' + client.name " />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
               Client: {{ client.name }}
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
                                        <Btn id="clientMessaging" type="success" @click.prevent="toggleSections">Messaging</Btn>
                                    </div>
                                    <div>
                                        <Btn id="docFiles" @click.prevent="toggleSections">Documents</Btn>
                                         <Btn id="docPhotos" @click.prevent="toggleSections">Photos</Btn>
                                    </div>
                                    
                                    
                                </section>

                                <section id="client-photos" style="display:none">
                                    Photos
                                </section>

                               <section id="client-files" style="display:none">
                                    Files
                               </section>

                               <section id="client-messaging">
                                  
                                   <Btn @click.prevent="showMessageModal = true">+ Create Message</Btn>
                                    <div class="flex justify-between items-center">
                                        <h2 v-if="!viewSentMessages" class="mt-6 text-lg"><strong>Messages from {{ client.name }}</strong></h2>
                                        <h2 v-else class="mt-6 text-lg"><strong>Your Messages</strong></h2>
                                        <div class="space-x-2">
                                            <input id="sent-messages" type="checkbox" v-model="viewSentMessages">
                                            <label for="sent-messages" class="cursor-pointer">View Sent Messages</label>
                                        </div>
                                        
                                    </div>
                                    <div v-if="!viewSentMessages">

                                        <div class="border-b-2 border-gray-300 pb-4 my-4">
                                            <h3 class="mb-4">- New Messages -</h3>
                                            <TableCreate v-if="newMessages && newMessages.length" :tableData="newMessages" :emitSingle="true" @single-data="singleMessageShow" />
                                        </div>

                                        <div class="border-b-2 border-gray-300 pb-4 mb-4">
                                            <h3 class="mb-4">- Old Messages -</h3>
                                            <TableCreate v-if="oldMessages && oldMessages.length" :tableData="oldMessages" :emitSingle="true" @single-data="singleMessageShow" />
                                        </div>
                                    </div>

                                    <div v-else class="border-b-2 border-gray-300 pb-4 my-4">
                                        <h3 class="mb-4">- Sent Messages -</h3>

                                        <TableCreate v-if="sentMessages" :tableData="sentMessages" />


                                        <p v-else class="font-bold text-red-600">No Sent Messages</p>

                                    </div>

                               </section>

                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- // Create Message Modal  -->
         <Modal class="px-4" :show="showMessageModal" @close="showMessageModal = false">
            <h2 class="border-b border-gray-300 py-4 mb-4 text-center text-2xl">Message {{ client.name }}</h2>
            <div class="py-4 px-6">
                <form @submit.prevent="messageFormSubmit">
                    <div class="mb-4">
                        <InputLabel class="mb-2" for="message-title">Title</InputLabel>
                        <TextInput id="message-title" class="w-full" type="text" v-model="messageForm.title" />
                    </div>
                    <div>
                        <InputLabel class="mb-2" for="message">Message</InputLabel>
                        <textarea id="message" v-model="messageForm.message" class="w-full" rows="10"></textarea>
                    </div>
                    <div class="flex justify-between">
                        <Btn type="danger" @click.prevent="showMessageModal = false">Close</Btn>
                        <div>
                            <Btn class="text-right">Send Message</Btn>
                        </div>
                    </div>
                </form>
            </div>
         </Modal>

         <!-- // Show Message Modal  -->
         <Modal class="px-4" :show="showSingleMessageModal" @close="showSingleMessageModal = false">
            <h2 class="border-b border-gray-300 py-4 mb-4 text-center text-2xl">{{ client.name }}'s Message</h2>
            <div class="flex justify-end items-center space-x-2 px-4">
                <input id="mark-as-read" type="checkbox" @change="markAsReadFormSubmit" :checked="singleMessage.is_read === 'Read'" />
                <label class="cursor-pointer" for="mark-as-read">Mark as Read</label>
            </div>
            <div class="py-4 px-6">
                    <div class="mb-4">
                        <InputLabel class="mb-2" for="message-title">Title</InputLabel>
                        <TextInput id="message-title" class="w-full" type="text" v-model="singleMessage.title" />
                    </div>
                    <div>
                        <InputLabel class="mb-2" for="message">Message</InputLabel>
                        <textarea id="message" v-model="singleMessage.message" class="w-full" rows="10"></textarea>
                    </div>
                    <div class="flex justify-between">
                        <Btn type="danger" @click.prevent="showSingleMessageModal = false">Close</Btn>
                        <div>
                            <Btn class="text-right">Reply</Btn>
                        </div>
                        

                    </div>
              
            </div>
         </Modal>
    </AuthenticatedLayout>
</template>
