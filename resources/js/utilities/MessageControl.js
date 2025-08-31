import { reactive } from "vue";


const MessageControl = reactive({
    message: "",
    type: "",
    pulse: false
});

export default MessageControl;