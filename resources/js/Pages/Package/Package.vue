<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from "vue";
import PackageModal from "@/Components/PackageModal.vue";

const functionalities = ref([]);
const chosenPackage = ref(null);
const currentPackage = ref(null);
const funcToAdd = ref("")
const props = defineProps({
    packages: Array,
    markets: Array,
    existingFuncs: Array,
})


const form = useForm({
    ime_paketa: "",
    cena_paketa: "",
    broj_big: "",
    funkcionalnosti: [],
    customFunkcionalnosti: [],
    trzista: []
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
            form.trzista = [...currentPackage.value.market];
            functionalities.value = [];
        }
    }
}
const addCustomFunc = () => {
    const newFunc = {
        funkcionalnost: funcToAdd.value.trim(),
    };
    const exists = form.funkcionalnosti?.some(f =>
        f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()
    ) || form.customFunkcionalnosti.some(f => f.funkcionalnosti.toLowerCase() === newFunc.funkcionalnost.toLowerCase());

    if (!exists) {
        form.customFunkcionalnosti.push(newFunc);
        functionalities.value.push(newFunc);
        funcToAdd.value = ""; // Clear the input field
    } else {
        alert("Ova funkcionalnost vec postoji");
    }
};
const addMarket = (trziste) => {
    const findMarket = form.trzista.findIndex(m => m.id === trziste.id)

    if (findMarket !== -1) {
        form.trzista.splice(findMarket, 1)
    } else {
        form.trzista.push(trziste)
    }
}

const isInForm = (trziste) => {
    return form.trzista.some(t => t.id === trziste.id);
};

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


// PACKAGE MODAL

const showPackageModal = ref(false)
const newPackage = () => {
    showPackageModal.value = true
}
const submit = () => {
    form.patch(route('paket.update', {package: currentPackage.value.id}), {
        onSuccess: () => {
            form.reset()
        }
    })

};
</script>

<template>
    <PackageModal
        :show="showPackageModal"
        :trzista="markets"
        :existing-funcs="existingFuncs"
        @closeModal="showPackageModal=false"
    ></PackageModal>
    <div class="w-full flex items-center justify-center mb-10">

        <form class="w-full flex flex-col items-center justify-center mt-10" @submit.prevent="submit">
            <div class="lg:w-2/5 md:w-3/5">
                <div>
                    <InputLabel for="" value="Izaberite paket ili kliknite plus za novi"/>
                    <div class="flex items-center gap-2">
                        <select v-model="chosenPackage"

                                @change="setData"
                                class="form-select mt-2" aria-label="Default select example">
                            <option v-for="pkg in packages"> {{ pkg.ime }}</option>
                        </select>
                        <span @click="newPackage"
                              class="text-5xl text-green-600 cursor-pointer">&#43;
                        </span>
                    </div>

                </div>
                <div class="mt-4">
                    <InputLabel for="ime_paketa" value="Naziv paketa"/>

                    <TextInput
                        id="ime_paketa"
                        type="text"
                        class="mt-1 block w-full text-capitalize"
                        v-model="form.ime_paketa"
                    />

                    <InputError class="mt-2" :message="form.errors.ime_paketa"/>
                </div>
                <div class="mt-4">
                    <InputLabel value="Izaberite trziste/a"/>
                    <div class="flex gap-4">

                        <div class="flex items-center mt-2 gap-2" v-for="trziste in markets" :key="trziste.id">
                            <label :for="trziste.ime_trzista" class="text-lg font-semibold">{{
                                    trziste.ime_trzista
                                }}</label>
                            <input
                                :id="trziste.ime_trzista"
                                @change="addMarket(trziste)"
                                :checked="isInForm(trziste)"
                                class="form-check-input border-2 border-black" id="Srbija" type="checkbox">

                        </div>

                    </div>
                </div>

                <div class="mt-4">
                    <InputLabel for="cena" value="Cena paketa"/>

                    <TextInput
                        id="cena"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.cena_paketa"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.cena_paketa"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="br" value="Broj besplatnih instalacija godisnje"/>

                    <TextInput
                        id="br"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.broj_big"
                        min="0"
                    />

                    <InputError class="mt-2" :message="form.errors.broj_big"/>
                </div>
                <div class="mt-1 flex flex-col  ">

                    <div class="flex flex-col gap-2 mt-4">
                        <label for="">Dodaj custom funkcionalnost (uneti pa pritisnuti enter)</label>
                        <TextInput
                            v-model="funcToAdd"
                            @keydown.enter.prevent="addCustomFunc"
                            type="text"
                            class="mt-1 block w-full"
                        />
                    </div>
                    <div class="mt-2">
                        <span>Custom funkcionalnosti koje ste dodali: </span>
                        <div class="bg-white shadow rounded p-2 mt-2">
                            <div class="flex justify-between items-center"
                                 v-if="form.customFunkcionalnosti.length>0"
                                 v-for="cf in form.customFunkcionalnosti">
                                <span> {{ cf.funkcionalnost }}</span>
                                <span class="text-2xl text-red-600 font-semibold cursor-pointer"
                                      @click="removeCustomFunc(cf)"> - </span>
                            </div>
                            <span v-else>Trenutno nisu dodate custom funkcionalnosti</span>
                        </div>

                    </div>
                    <hr>
                </div>
                <div id="funkcionalnosti" class="w-full">
                    <div class="mt-3">
                        <span>Funkcionalnosti koje vec postoje u sistemu:</span>
                    </div>
                    <div class="bg-white shadow rounded p-3 mt-2">

                        <div class="mt-1 overflow-y-auto max-h-[300px]">
                            <div class="flex items-center justify-between gap-4 " v-for="func in existingFuncs"
                                 :key="func.id">
                                <div class="flex flex-row-reverse gap-2">
                                    <label class="form-check-label" :for="func.funkcionalnost">
                                        {{ func.funkcionalnost }}
                                    </label>
                                    <input
                                        @change="toggleFunctionality(func)"
                                        :checked="doesPackageContainFunc(func)"

                                        class="form-check-input border-2 border-black" type="checkbox" value=""
                                        :id="func.funkcionalnost">
                                </div>
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
