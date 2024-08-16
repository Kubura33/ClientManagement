<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from "vue";
import {v4 as uuidv4} from 'uuid';

const functionalities = ref([]);
const chosenPackage = ref(null);
const currentPackage = ref(null);
const funcToAdd = ref("")
const props = defineProps({
    packages: Array,
    market: Object,
    existingFuncs: Array,
})


const form = useForm({
    paket_id: null,
    funkcionalnosti: [],
    trziste: props.market,
    customFuncs : [],
});

const setData = () => {
    if (chosenPackage.value) {
        currentPackage.value = props.packages.find(pkg => pkg.ime === chosenPackage.value)
        if (currentPackage.value) {
            form.paket_id = currentPackage.value.id
            form.ime_paketa = currentPackage.value.ime
            form.cena_paketa = currentPackage.value.cena
            form.broj_big = currentPackage.value.broj_besplatnih_instalacija_godisnje
            currentPackage.value.functionalities.forEach(f => functionalities.value.push({
                funkcionalnost: f.funkcionalnost,
                id: f.id
            }))
            form.funkcionalnosti = [...functionalities.value];
            functionalities.value = [];
        }
    }
}


const doesPackageContainFunc = (func) => {
    return form.funkcionalnosti.some(f => f.funkcionalnost == func.funkcionalnost)
}
const toggleFunctionality = (func) => {
    const funcIndex = form.funkcionalnosti.findIndex(f => f.funkcionalnost === func.funkcionalnost);
    if (funcIndex !== -1) {
        form.funkcionalnosti.splice(funcIndex, 1);
    } else {
        form.funkcionalnosti.push(func);
    }

};

const customFunc = ref("")
const addCustomFunc = () => {
    const newFunc = {
        funkcionalnost: customFunc.value
    }
    if(form.funkcionalnosti.some (f => f.funkcionalnost.toLowerCase() == newFunc.funkcionalnost.toLowerCase()) || form.customFuncs.some( cf => cf.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase())){
        alert("Ova funkcionalnost vec postoji");
    }else{
        form.customFuncs.push(newFunc)
    }
}

const removeCustomFunc = (func) => {
    const findIndex = form.customFuncs.findIndex( f => f.funkcionalnost === func.funkcionalnost)
    if(findIndex !== -1){
        form.customFuncs.splice(findIndex, 1)
    }
}

// PACKAGE MODAL


const submit = () => {
    form.post(route('paket-funkcionalnost.store'),{
        onSuccess: () => {
            form.reset()
            chosenPackage.value = null
        }
    })

};
</script>
<template>
    <h4>Izmena za pakete na trzistu: {{market.ime_trzista}}</h4>
    <div class="w-full flex items-center justify-center mb-10">
        <form class="w-full flex flex-col items-center justify-center mt-10" @submit.prevent="submit">
            <div class="lg:w-2/5 md:w-3/5">
                <div>
                    <InputLabel for="" value="Izaberite paket da biste menjali polja"/>
                    <div class="flex items-center gap-2">
                        <select v-model="chosenPackage"

                                @change="setData"
                                class="form-select mt-2" aria-label="Default select example">
                            <option v-for="pkg in packages"> {{ pkg.ime }}</option>
                        </select>
                    </div>

                </div>

                <div id="funkcionalnosti" class="w-full">
                    <div class="mt-3">
                        <span>Funkcionalnosti koje vec postoje u sistemu:</span>
                    </div>
                    <div class="bg-white shadow rounded p-3 mt-2">

                        <div class="mt-1 overflow-y-auto max-h-[300px]">
                            <div class="flex items-center justify-between gap-4 " v-for="func in existingFuncs"
                                 :key="uuidv4()">
                                <div class="flex flex-row-reverse gap-2">
                                    <label class="form-check-label" :for="func.funkcionalnost">
                                        {{ func.funkcionalnost }}
                                    </label>
                                    <input
                                        @change="toggleFunctionality(func)"
                                        :checked="doesPackageContainFunc(func)"
                                        :disabled="!chosenPackage"
                                        class="form-check-input border-2 border-black" type="checkbox" value=""
                                        :id="func.funkcionalnost">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="mt-4">
                    <InputLabel for="customFunc" value="Nova funkcionalnost"/>

                    <TextInput
                        id="customFunc"
                        type="text"
                        class="mt-1 block w-full text-capitalize"
                        v-model="customFunc"
                        @keydown.enter.prevent="addCustomFunc"
                    />

                </div>
                <div id="custom_funkcionalnosti" class="w-full" v-if="form.customFuncs.length > 0">
                    <div class="mt-3">
                        <span>Dodate funkcionalnosti:</span>
                    </div>
                    <div class="bg-white shadow rounded p-3 mt-2" >

                        <div class="mt-1 overflow-y-auto max-h-[300px]">
                            <div class="flex items-center justify-between gap-4 " v-for="func in form.customFuncs"
                                 :key="uuidv4()">
                                <div class="flex flex-row">
                                    <label class="" :for="func.funkcionalnost">
                                        {{ func.funkcionalnost }}
                                    </label>
                                </div>
                                <span class="cursor-pointer text-red-600 font-bold" @click="removeCustomFunc(func)">-</span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


            <div class="mt-4">
                <button class="btn btn-primary">Snimi paket</button>
            </div>

        </form>
    </div>


</template>
