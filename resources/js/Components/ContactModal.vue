<script setup>


import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import {reactive, ref, watch} from "vue";
import CustomeLabel from "@/Components/CustomeLabel.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    isEdit: {
        type: Boolean
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
    phone2: "",
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

    if (props.isEdit) {
        emit('finishEdit', newContact)
    } else {
        emit('addToContacts', newContact)

    }
};

defineExpose({
    clear
})
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
        <!-- Modal Container -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="text-sm font-semibold text-gray-700">Dodavanje kontakta</h2>
                <button @click="$emit('closeModal')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700">Ime i prezime</label>
                    <input type="text" v-model="newContact.ime_prezime" class="mt-1 block w-full py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"/>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Email</label>
                    <input type="email" v-model="newContact.email" class="mt-1 block w-full p-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"/>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Email 2</label>
                    <input type="email" v-model="newContact.email2" class="mt-1 block w-full p-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"/>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Telefon</label>
                    <input type="text" v-model="newContact.phone" class="mt-1 block w-full p-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"/>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Telefon 2</label>
                    <input type="text" v-model="newContact.phone2" class="mt-1 block w-full p-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"/>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 mt-6">
                <button @click="$emit('closeModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 text-xs rounded-md">
                    Close
                </button>
                <button @click="handleClick" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 text-xs rounded-md">
                    {{ isEdit ? 'Sacuvaj promene' : 'Dodaj kontakt' }}
                </button>
            </div>
        </div>
    </div>
</template>

