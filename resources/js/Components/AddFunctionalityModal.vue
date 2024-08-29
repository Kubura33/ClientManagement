<script setup>

import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {computed, onMounted, ref} from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    existingFunctionalities: {
        type: Array,
        required: true
    },
    chosenFunctionalities: {
        type: Array,
    },
    formCustomFuncs: {
        type: Array,
    }
})
const customFunc = ref("");
const customFuncArray = ref([...props.formCustomFuncs]);
const checkedFunctionalities = ref([])
const addCustomFunc = () => {
    if(customFunc.value){
        const newFunc = {
            funkcionalnost: customFunc.value.trim(),
            pivot: {
                uradjeno: false
            }
        };
        const exists = customFuncArray.value.some(f =>
            f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()
        ) || props.existingFunctionalities.some(f =>
            f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()
            || props.chosenFunctionalities.some(f => f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase())
        );
        if (!exists) {
            customFuncArray.value.push(newFunc);
            checkedFunctionalities.value.push(newFunc)
            customFunc.value = ""; // Clear the input field
        } else {
            alert("Ova funkcionalnost vec postoji");
        }
    }
    else{
        alert("Unesite naziv funkcionalnosti!");
    }

};
const toggleFunctionality = (func) => {

    const findIndex = checkedFunctionalities.value.findIndex(f => f.funkcionalnost == func.funkcionalnost)
    if(findIndex !== -1){
        checkedFunctionalities.value.splice(findIndex, 1)
    }else{
        checkedFunctionalities.value.push(func)
    }
}

const reset = () => {
    customFunc.value = ""
    checkedFunctionalities.value = [];
    customFuncArray.value = [...props.formCustomFuncs]
}
const combinedFunc = computed(() => {
    const notChosenFuncs = props.existingFunctionalities.filter(f =>
        !props.chosenFunctionalities.some(cf =>
            f.funkcionalnost === cf.funkcionalnost
        )
    );
    return notChosenFuncs;
});

defineExpose({
    reset
});

</script>

<template>
    <div v-if="show" class="absolute top-0 modal fade show d-block " tabindex="-1" role="dialog">
        <div class="fixed inset-0 w-screen bg-black bg-opacity-50 backdrop-blur-sm ">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Dodavanje funkcionalnosti</h5>
                        <button type="button" class="btn-close" @click="$emit('closeModal')"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center justify-between gap-4 px-3" v-for="func in combinedFunc">
                            <div class="flex flex-row-reverse gap-2">
                                <label class="form-check-label" :for="func.funkcionalnost">
                                    {{ func.funkcionalnost }}
                                </label>
                                <input
                                    @change="toggleFunctionality(func)"
                                    class="form-check-input border-2 border-black" type="checkbox" value=""
                                    :id="func.funkcionalnost">
                            </div>

                        </div>
                        <div>
                            <div class="flex gap-2 mt-4">
                                <label for="">Dodaj funkcionalnost</label>
                                <TextInput
                                    v-model="customFunc"
                                    @keydown.enter.prevent="addCustomFunc"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                            </div>
                        </div>
                        <div class="flex flex-col mt-4">
                            <span>Custom funkcionalnosti koje ste dodali: </span>
                            <ul>
                                <li v-for="cF in customFuncArray" :key="cF.funkcionalnost" class="font-semibold text-md">
                                    {{ cF.funkcionalnost }}
                                </li>
                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="$emit('closeModal')">Close
                            </button>
                            <button type="submit" class="btn btn-primary"
                                    @click="$emit('saveFunctionalities', checkedFunctionalities, [...customFuncArray])">Save changes
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
.modal-content{
    max-height: 80vh;
    overflow-y: auto ;
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
