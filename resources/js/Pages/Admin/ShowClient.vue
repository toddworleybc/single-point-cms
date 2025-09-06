<script setup>
import createMessageControl from '@/Utilities/MessageControl';
import MessageBanner from '@/Components/MessageBanner.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref, reactive, computed, unref } from 'vue';
import Btn from '@/Components/Btn.vue';
import TableCreate from '@/Components/TableCreate.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';


const MessageControl = createMessageControl();
const page = usePage();

const client = page.props.client;
const sentMessages = computed(() => page.props.adminMessages);
const clientMessages = computed(() => page.props.clientMessages);


const showMessageModal = ref(false);
const showSingleMessageModal = ref(false);
const viewSentMessages = ref(false);



const newMessages = computed(() => clientMessages.value.filter(msg => msg.is_read === 'Not Read'));
const oldMessages = computed(() => clientMessages.value.filter(msg => msg.is_read === 'Read'));


const singleMessage = reactive({
    subject: "",
    message: "",
    user_id: null,
    id: null,
    is_read: null,
    replied_to: null
});

const repliedToMessage = reactive({
    subject: "",
    message: "",
    user_id: null,
    id: null,
    is_read: null,
    replied_to: null
});


const messageForm = useForm({
    message:  "",
    subject: "",
    client_id: client.id,
    replied_to: null
});


function messageFormSubmit(e) {
 
        messageForm.post(route('admin.submit.message'), {
            onStart: () => {
                showMessageModal.value = false;
                MessageControl.message = "Sending Message...";
                MessageControl.type = 'warning';
                MessageControl.pulse = true;
            },
            onSuccess: (status) => {
                MessageControl.message = "Message sent successfully.";
                MessageControl.type = 'success';

                if(messageForm.replied_to) {
                    markReadForm.is_read = "Read";
                    markReadForm.id = messageForm.replied_to;
                    markReadForm.post( route('admin.mark.as.read') );
                }

                viewSentMessages.value = true;
                

            },
            onError: (e) => {
                MessageControl.message = e.message;
                MessageControl.type = 'error';
            },
            onFinish: () => {
                MessageControl.pulse = false;
                messageForm.reset();
                showMessageModal.value = false;
            },
            preserveState: true
        });

}// messageFormSubmit

const markReadForm = useForm({
    is_read: "Not Read",
    id: null
});

function markAsReadFormSubmit(e) {

    markReadForm.is_read = e.target.checked ? "Read" : "Not Read";

    markReadForm.id = singleMessage.id;

    markReadForm.post( route('admin.mark.as.read'), { preserveState: false } );

}

function singleMessageShow(message) {
    singleMessage.subject = message.subject;
    singleMessage.message = message.message;
    singleMessage.id = message.id;
    singleMessage.is_read = message.is_read;
    singleMessage.user_id = message.user_id;
    singleMessage.replied_to = message.replied_to;
    showSingleMessageModal.value = true;

    if(message.replied_to) replied_to_message(message.replied_to);
}


function replied_to_message(id) {
    const messagesArr = viewSentMessages.value ? clientMessages.value : sentMessages.value;
    let repliedMessage = null;


    messagesArr.find(msg => { 
        if(msg.id === Number(id)) repliedMessage = msg;
    });

    
    if(repliedMessage) {
        repliedToMessage.subject = repliedMessage.subject;
        repliedToMessage.message = repliedMessage.message;
        repliedToMessage.id = repliedMessage.id;
        repliedToMessage.is_read = repliedMessage.is_read;
        repliedToMessage.user_id = repliedMessage.user_id;
        repliedToMessage.replied_to = repliedMessage.id;
    }

    

}// replied_to_message


function replyToMessage() {
    messageForm.replied_to = singleMessage.id;
    showSingleMessageModal.value = false;
    showMessageModal.value = true;
}// replyToMessage


function closeMessageModal() {
    messageForm.replied_to = null;
    messageForm.subject = "";
    messageForm.message = "";
    showMessageModal.value = false;
}

function closeSingleMessageModal() {
    singleMessage.subject = "";
    singleMessage.message = "";
    singleMessage.id = null;
    singleMessage.is_read = null;
    singleMessage.user_id = null;
    

    repliedToMessage.subject = "";
    repliedToMessage.message = "";
    repliedToMessage.user_id = null;
    repliedToMessage.id = null;
    repliedToMessage.is_read = null;
    repliedToMessage.replied_to = null;

    showSingleMessageModal.value = false;

}


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

         <MessageBanner :control="MessageControl"/>

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
                                            <TableCreate v-if="newMessages && newMessages.length" :tableData="newMessages" :emitSingle="true" @single-data="singleMessageShow"
                                            tdClass="bg-green-50" />
                                            <p v-else class="font-bold text-red-600">No New Messages</p>
                                        </div>

                                        <div class="border-b-2 border-gray-300 pb-4 mb-4">
                                            <h3 class="mb-4">- Old Messages -</h3>
                                            <TableCreate v-if="oldMessages && oldMessages.length" :tableData="oldMessages" :emitSingle="true" @single-data="singleMessageShow"
                                            tdClass="bg-gray-100" />
                                            <p v-else class="font-bold text-red-600">No Old Messages</p>
                                        </div>
                                    </div>

                                    <div v-else class="border-b-2 border-gray-300 pb-4 my-4">
                                        <h3 class="mb-4">- Sent Messages -</h3>

                                        <TableCreate v-if="sentMessages && sentMessages.length" :tableData="sentMessages" :emitSingle="true"
                                        @single-data="singleMessageShow"
                                        tdClass="bg-yellow-50" />


                                        <p v-else class="font-bold text-red-600">No Sent Messages</p>

                                    </div>

                               </section>

                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- // Create Message Modal  -->
         <Modal class="px-4" :show="showMessageModal" @close="closeMessageModal">
            <h2 class="border-b border-gray-300 py-4 mb-4 text-center text-2xl">Message {{ client.name }}</h2>
            <div v-if="messageForm.replied_to" class="flex justify-end items-center space-x-2 px-4">
               <p v-if="messageForm.replied_to">Replying to {{ clientMessages.find(message => message.id === messageForm.replied_to)['subject'] }}</p>
            </div>
            <div class="py-4 px-6">
                <form @submit.prevent="messageFormSubmit">
                    <div class="mb-4">
                        <InputLabel class="mb-2" for="message-subject">Subject</InputLabel>
                        <TextInput id="message-subject" class="w-full" type="text" v-model="messageForm.subject" />
                    </div>
                    <div>
                        <InputLabel class="mb-2" for="message">Message</InputLabel>
                        <textarea id="message" v-model="messageForm.message" class="w-full" rows="10"></textarea>
                    </div>
                    <div class="flex justify-between">
                        <Btn type="danger" @click.prevent="closeMessageModal">Close</Btn>
                        <Btn class="text-right">Send Message</Btn>
                    </div>
                </form>
            </div>
         </Modal>

         <!-- // Show Message Modal  -->
         <Modal class="px-4" :show="showSingleMessageModal" @close="closeSingleMessageModal">
            <div class="flex justify-between items-center px-4 border-b border-gray-300 py-4 mb-4">
                <h2 class="text-2xl">{{ viewSentMessages ? 'My' : client.name+"\'s" }} Message</h2>
                <Btn type="danger" @click.prevent="closeSingleMessageModal">Close</Btn>
            </div>
            
            <div v-if="singleMessage.user_id === client.id" class="flex justify-end items-center space-x-2 px-4">
                <input id="mark-as-read" type="checkbox" @change="markAsReadFormSubmit" :checked="singleMessage.is_read === 'Read'" />
                <label class="cursor-pointer" for="mark-as-read">Mark as Read <small>(Relying also marks as read)</small></label>
            </div>
            
            <div class="py-4 px-6">
                    <div class="mb-4">
                        <InputLabel class="mb-2" for="message-subject">subject</InputLabel>
                        <TextInput id="message-subject" class="w-full" type="text" v-model="singleMessage.subject" disabled />
                    </div>
                    <div>
                        <InputLabel class="mb-2" for="message">Message</InputLabel>
                        <textarea id="message" v-model="singleMessage.message" class="w-full" rows="10" disabled></textarea>
                    </div>
                        
                    <div v-if="singleMessage.user_id === client.id">
                        <Btn @click.prevent="replyToMessage" class="text-right">Reply</Btn>
                    </div>
                         
            </div>

            <!-- replyed message  -->
             <div v-if="viewSentMessages && singleMessage.replied_to">

             
                <div class="border-t-4 border-gray-600 pt-4">
                    <h2 class="border-b border-gray-300 py-4 mb-4 text-center text-2xl">Replyed to Message</h2>
                </div>
            
                
                <div  class="py-4 px-6">
                        <div class="mb-4">
                            <InputLabel class="mb-2" for="message-subject">Subject</InputLabel>
                            <TextInput id="message-subject" class="w-full" type="text" v-model="repliedToMessage.subject" disabled />
                        </div>
                        <div>
                            <InputLabel class="mb-2" for="message">Message</InputLabel>
                            <textarea id="message" v-model="repliedToMessage.message" class="w-full" rows="10" disabled></textarea>
                        </div>
                </div>
            </div>
         </Modal>
    </AuthenticatedLayout>
</template>
