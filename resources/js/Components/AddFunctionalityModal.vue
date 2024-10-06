<script setup>
import {computed, ref} from "vue";
import CustomInput from "@/Components/CustomInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {v4 as uuidv4} from 'uuid';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    formCustomFuncs: {
        type: Array
    },
    notChosenFunctionalities: {
        type: Array
    }
});

const customFunc = ref("");
const customFuncArray = ref([...props.formCustomFuncs]);
const addedFuncs = ref([]);
const emits = defineEmits(['addFunc', 'addCustomFunc', 'removeFunc', 'removeCustomFunc'])
const addCustomFunc = () => {
    emits('addCustomFunc', customFunc.value)
    if (customFunc.value) {
        const newFunc = {
            funkcionalnost: customFunc.value.trim(),
            pivot: {
                uradjeno: false
            },
            selected: true
        };
        const exists = customFuncArray.value.some(f =>
            f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()
        ) || props.notChosenFunctionalities.some(f =>
            f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()
        ) || addedFuncs.value.some(f =>
            f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()
        );
        if (!exists) {
            customFuncArray.value.push(newFunc);
            customFunc.value = ""; // Clear the input field
        } else {
            alert("Ova funkcionalnost vec postoji");
        }
    } else {
        alert("Unesite naziv funkcionalnosti!");
    }
};

const addFunc = (func) => {
    emits('addFunc', func);
    const index = addedFuncs.value.findIndex(f => f.funkcionalnost === func.funkcionalnost);
    if (index === -1) {
        addedFuncs.value.push({
            funkcionalnost: func.funkcionalnost,
            selected: true,
            isDone: false,
            id: func.id,
            trziste_id: func.trziste_id,
        });
    }

};

const removeFunc = (func) => {
    emits('removeFunc', func)
    const index = addedFuncs.value.findIndex(f => f.funkcionalnost === func.funkcionalnost);
    if (index !== -1) {
        addedFuncs.value.splice(index, 1); // Remove from added functionalities
    }
};
const removeCustomFunc = (cFunc) => {
    emits('removeCustomFunc', cFunc)
    const index = customFuncArray.value.findIndex(f => f.funkcionalnost === cFunc.funkcionalnost);
    if (index !== -1) {
        customFuncArray.value.splice(index, 1); // Remove from added functionalities
    }
}

const reset = () => {
    customFunc.value = "";
    customFuncArray.value = [...props.formCustomFuncs];
};

defineExpose({
    reset
});
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-gray-800 bg-opacity-60 flex justify-center items-start z-50">
        <!-- Modal Container -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-screen-lg h-[70vh] relative mt-20">
            <!-- Modal Header (Fixed) -->
            <div class="flex justify-between items-center border-b pb-3 px-10 pt-6 absolute top-0 left-0 right-0 bg-white z-10">
                <h2 class="text-lg font-semibold text-gray-700">Funkcionalnosti</h2>
                <button class="text-gray-500 hover:text-gray-700" @click="$emit('closeModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="flex gap-10 overflow-y-auto px-10 pt-20 pb-20 absolute top-[20px] bottom-[70px] w-full">
                <!-- Existing Functionalities -->
                <div class="w-1/2 space-y-4">
                    <!-- Add Custom Functionality -->
                    <div>
                        <h3 class="text-base font-semibold text-blue-600">Dodavanje custom funkcionalnosti</h3>
                        <div class="flex gap-2 text-sm">
                            <CustomInput
                                v-model="customFunc"
                                @keyup.enter.prevent="addCustomFunc"
                                class="text-sm p-2 border border-gray-300 rounded-md"
                                placeholder="Unesite naziv funkcionalnosti"
                            />
                            <PrimaryButton
                                @click.prevent="addCustomFunc"
                                class="text-sm px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Dodaj
                            </PrimaryButton>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-700">Postojeće funkcionalnosti na trzistu</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center gap-2"
                                 v-for="func in notChosenFunctionalities" :key="func.id">
                                <label class="text-black">{{ func.funkcionalnost }}</label>
                                <button
                                    @click.prevent="addFunc(func)"
                                    class="text-green-500 hover:text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Added Functionalities -->
                <div class="w-1/2 space-y-4">
                    <h3 class="text-base font-semibold text-gray-700">Dodate funkcionalnosti</h3>
                    <div v-if="addedFuncs.length || customFuncArray.length" class="space-y-2 text-sm">
                        <!-- Existing added functionalities -->
                        <div class="flex justify-between items-center"
                             v-for="func in addedFuncs" :key="uuidv4()">
                            <span>{{ func.funkcionalnost }}</span>
                            <button @click="removeFunc(func)" class="text-red-500 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <!-- Custom added functionalities -->
                        <div class="flex justify-between items-center font-semibold"
                             v-for="func in customFuncArray" :key="uuidv4()">
                            <span class="text-blue-500">{{ func.funkcionalnost }}</span>
                            <button @click="removeCustomFunc(func)" class="text-red-500 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (Fixed) -->
            <div class="flex justify-end space-x-3 px-10 py-3 absolute bottom-0 left-0 right-0 bg-white z-10 border-t">
                <button @click.prevent="$emit('closeModal')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 text-sm rounded-md">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>


