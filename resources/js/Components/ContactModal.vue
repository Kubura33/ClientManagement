<script setup>


import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import {reactive, ref, watch} from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    isEdit: {
        type:Boolean
    },
    contactToEdit: {
        type: Object
    },

})
const newContact = reactive({
    id: "",
    ime_prezime: "",
    email: "",
    email2: "",
    phone: "",
    phone2:  "",
})
const clear = () => {
    newContact.id = "";
    newContact.ime_prezime = "";
    newContact.email = "";
    newContact.email2 = "";
    newContact.phone = "";
    newContact.phone2 = "";
};
watch(() => props.contactToEdit, (newVal) => {
    if (newVal) {
        // Create a copy of newVal
        const copiedContact = {
            id: newVal.id || "",
            ime_prezime: newVal.ime_prezime || "",
            email: newVal.email || "",
            email2: newVal.email2 || "",
            phone: newVal.phone || "",
            phone2: newVal.phone2 || ""
        };

        // Assign the copiedContact to newContact
        Object.assign(newContact, copiedContact);
    }
}, {immediate: true})
const emit = defineEmits(['addToContacts', 'finishEdit'])
const handleClick = () => {

    if(props.isEdit){
        emit('finishEdit', newContact)
    }else{
        emit('addToContacts', newContact)

    }
};

defineExpose({
    clear
})
</script>

<template>
    <div v-if="show" class="relative modal fade show d-block" tabindex="-1" role="dialog">
        <div class="fixed w-screen inset-0 bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Dodavanje kontakta</h5>
                        <button type="button" class="btn-close" @click="$emit('closeModal')"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="">
                                <InputLabel value="Ime i prezime"/>
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="newContact.ime_prezime"

                                />


                            </div>
                            <div class=" mt-3">
                                <InputLabel value="Email"/>

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="newContact.email"

                                />


                            </div>
                            <div class="mt-3">
                                <InputLabel value="Email 2"/>

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="newContact.email2"

                                />


                            </div>
                            <div class="mt-3">
                                <InputLabel value="Telefon"/>

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="newContact.phone"

                                />


                            </div>
                            <div class="mt-3">
                                <InputLabel value="Telefon 2"/>

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="newContact.phone2"

                                />


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="$emit('closeModal')">Close
                                </button>
                                <button type="submit" class="btn btn-primary"
                                        @click="handleClick">
                                    {{ isEdit ? 'Sacuvaj promene' : 'Dodaj kontakt'}}
                                </button>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal {
    animation: slide-down 0.3s ease-out;
}

@keyframes slide-down {
    from {
        transform: translateY(-100%);
    }
    to {
        transform: translateY(0);
    }
}
</style>
