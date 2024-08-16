<script setup>

import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import {ref} from "vue";

const props = defineProps({
    show: {
        required: true,
        type: Boolean
    },
    currentDates: Array,
    broj_instalacija: Number,
    canEdit: Boolean
})

const existingDates = props.currentDates ? [...props.currentDates] : []
const newDate = ref(null)
const newDates = ref([]);
const counter = ref(0);
const formatDate = (date) => {
    const dateObj = new Date(date);
    const day = String(dateObj.getDate()).padStart(2, '0');
    const month = String(dateObj.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    const year = dateObj.getFullYear();
    return `${day}.${month}.${year}`; // Format as DD.MM.YYYY
}
const addNewDate = () => {
    const dateObj = new Date(newDate.value);
    const currentYear = new Date().getFullYear();
    const formattedDate = formatDate(newDate.value);

    // Check if the year is greater than the current year
    if (dateObj.getFullYear() > currentYear) {
        alert("Godina ne sme biti veća od trenutne godine");
        return;
    }

    const findIndexInNewDates = newDates.value.findIndex(date => date === formattedDate);
    const findIndexInExistingDates = existingDates.findIndex(date => date === formattedDate);

    if (findIndexInNewDates !== -1 || findIndexInExistingDates !== -1) {
        alert("Ovaj datum je već unet");
    } else {
        if(counter.value == props.broj_instalacija){
            alert("Klijent nema vise besplatnih instalacija")
        }else{
            newDates.value.push(formattedDate);
            newDate.value = null;
            counter.value++;
        }

    }
}
const removeDate = (dateToRemove) => {
    const findIndexInNewDates = newDates.value.findIndex(date => date === dateToRemove);

    if(findIndexInNewDates !== -1){
        newDates.value.splice(findIndexInNewDates, 1)
        counter.value --;
    }
}
const clear = () => {
    newDates.value = []
    newDate.value = null
    counter.value = 0
}
defineExpose({
    clear
})
</script>

<template>
    <div v-if="show" class="modal fade show d-block" tabindex="-1" role="dialog">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Datumi instalacija</h5>
                        <button type="button" class="btn-close" @click="$emit('closeModal')"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="" v-show="!canEdit">
                                <InputLabel value="Datum instalacije"/>
                                <TextInput
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="newDate"
                                    @keydown.enter.prevent="addNewDate"

                                />


                            </div>
                            <div class="mt-4" v-if="currentDates && currentDates.length > 0">
                                <span>Trenutni datumi instalacije</span>
                                <div class="max-w-md mx-auto mt-1">
                                    <ul class="list-disc list-inside">
                                        <li class="text-gray-700 py-1" v-for="date in currentDates">{{date}}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-4" v-if="newDates.length > 0">
                                <span>Dodati datumi instalacije</span>
                                <div class="max-w-md mx-auto mt-1">
                                    <ul class="list-disc list-inside">
                                        <li class="text-gray-700 py-1 " v-for="date in newDates"><span>{{date}}</span>
                                        <span class="ml-4 text-red-600 text-xl font-bold cursor-pointer" @click="removeDate(date)">-</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="$emit('closeModal')">Close
                                </button>
                                <button
                                    v-show="!canEdit"
                                    @click="$emit('saveDates', newDates)" class="btn btn-primary"
                                >
                                    Snimi
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

.modal-body {
    max-height: 80vh;
    overflow-y: auto;
    background-color: beige;
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
