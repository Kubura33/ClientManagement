<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import {computed, ref} from "vue";
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
    ime_paketa: "",
    cena_paketa: "",
    broj_big: "",
    funkcionalnosti: [],
    trziste: props.market
});

const setData = () => {
    if (chosenPackage.value) {
        currentPackage.value = props.packages.find(pkg => pkg.ime === chosenPackage.value)
        if (currentPackage.value) {
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

//SORTING FUNCS
const sortedFuncs = computed(() => {
    return props.existingFuncs.slice().sort((a, b) => {
        if (doesPackageContainFunc(a) === doesPackageContainFunc(b)) {
            return a.funkcionalnost.localeCompare(b.funkcionalnost);
        }
        return doesPackageContainFunc(b) - doesPackageContainFunc(a); // Checked items come first
    });
});

// PACKAGE MODAL


const submit = () => {
    form.patch(route('paket.update', {package: currentPackage.value.id}), {
        onSuccess: () => {
            form.reset()
            currentPackage.value = null
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
                <div class="mt-4">
                    <InputLabel for="ime_paketa" value="Naziv paketa"/>

                    <TextInput
                        id="ime_paketa"
                        type="text"
                        class="mt-1 block w-full text-capitalize"
                        v-model="form.ime_paketa" :disabled="!currentPackage"
                    />

                    <InputError class="mt-2" :message="form.errors.ime_paketa"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="cena" value="Cena paketa"/>

                    <TextInput
                        id="cena"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.cena_paketa"
                        required :disabled="!currentPackage"
                    />

                    <InputError class="mt-2" :message="form.errors.cena_paketa"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="br" value="Broj besplatnih instalacija godisnje"/>

                    <TextInput
                        id="br"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.broj_big" :disabled="!currentPackage"
                        min="0"
                    />

                    <InputError class="mt-2" :message="form.errors.broj_big"/>
                </div>
                <div id="funkcionalnosti" class="w-full" v-if="existingFuncs.length > 0">
                    <div class="mt-3">
                        <span>Funkcionalnosti koje vec postoje u sistemu:</span>
                    </div>
                    <div class="bg-white shadow rounded p-3 mt-2">

                        <div class="mt-1 overflow-y-auto max-h-[300px]">
                            <div class="flex items-center justify-between gap-4 " v-for="func in sortedFuncs"
                                 :key="uuidv4()">
                                <div class="flex flex-row-reverse gap-2">
                                    <label class="form-check-label" :for="func.funkcionalnost">
                                        {{ func.funkcionalnost }}
                                    </label>
                                    <input
                                        @change="toggleFunctionality(func)"
                                        :checked="doesPackageContainFunc(func)" :disabled="!chosenPackage"

                                        class="form-check-input border-2 border-black" type="checkbox" value=""
                                        :id="func.funkcionalnost">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div v-else class="bg-white shadow rounded p-3 mt-4">
                    Trenutno nema funkcionalnosti na ovom trzistu
                </div>
            </div>


            <div class="mt-4">
                <button class="btn btn-primary">Snimi paket</button>
            </div>

        </form>
    </div>


</template>
