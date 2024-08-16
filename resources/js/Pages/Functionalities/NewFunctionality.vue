<script setup>


import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {v4 as uuidv4} from 'uuid';

const props = defineProps({
    market: Object,
    existingFuncs: Array
})

const newFunc = ref("");
const form = useForm({
    functionalities: []
})
const addNewFunc = () => {
    if (props.existingFuncs.some(eF => eF.funkcionalnost === newFunc.value)) {
        alert("Ova funkcionalnost vec postoji");
    }
    else if(form.functionalities.some(f => f.funkcionalnost === newFunc.value)){
        alert("Vec ste dodali ovu funkcionalnost");
    }
    else {
        form.functionalities.push({ funkcionalnost: newFunc.value });
        newFunc.value = "";
    }
}
const removeFunc = (func) => {
    const findIndex = form.functionalities.findIndex(f => f.funkcionalnost === func.funkcionalnost)

    if(findIndex !== -1){
        form.functionalities.splice(findIndex, 1)
    }
}
const submit = () => {
    form.post(route('nova-funkcionalnost.store'));
}
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="func" value="Unesite nove funkcionalnosti"/>

                    <TextInput
                        id="func"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="newFunc"
                        @keydown.enter.prevent="addNewFunc"
                    />

                </div>
                <div class="mt-4" v-if="form.functionalities.length > 0">
                    <h5> Dodate funkcionalnosti</h5>
                    <div class="bg-white shadow rounded  mt-2 w-full">

                        <div class="mt-1 max-h-[300px] overflow-y-auto">
                            <ul class="list-none pt-2 px-3">
                                <li v-for="func in form.functionalities" :key="uuidv4()" class="flex justify-between">
                                    <span>{{ func.funkcionalnost }}</span>
                                    <span class="text-red-500 font-bold text-3xl cursor-pointer"
                                          @click="removeFunc(func)"
                                    >-</span>
                                </li>

                            </ul>

                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h5> Postojece funkcionalnosti</h5>
                    <div class="bg-white shadow rounded p-3 mt-2 w-full">

                        <div class="mt-1 max-h-[300px] overflow-y-auto">
                            <ul class="list-disc">
                                <li v-for="func in existingFuncs"> {{ func.funkcionalnost }}</li>
                            </ul>

                        </div>
                    </div>
                </div>


                <div class="flex items-center justify-end mt-4">


                    <button class="ms-4 btn btn-primary" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                        Snimi
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
