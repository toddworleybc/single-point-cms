import {reactive} from 'vue';

function createMessageControl() {
    const state = reactive({
        message: "",
        type: "",
        pulse: false,
    });
    return state;
}

export default createMessageControl;