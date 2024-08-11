<script setup>

import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
        default: false
    },
    trzista: {
        type: Array,
    },
    existingFuncs: {
        type: Array,
    }
})

const form = useForm({
    ime_paketa: "",
    cena: "",
    broj_besplatnih_instalacija_godisnje: "",
    trzista: [],
    funkcionalnosti: [],
    customFunkcionalnosti: [],
})
const toggleMarket = (trziste) => {
    const findIndex = form.trzista.findIndex(t => t.id === trziste.id)

    if (findIndex !== -1) {
        form.trzista.splice(findIndex, 1)
    } else {
        form.trzista.push(trziste)
    }
}
const marketChecked = (trziste) => {
    return form.trzista.some(t => t.id == trziste.id)
}

//CUSTOM FUNCS
const customFunc = ref("")

const addCustomFunc = () => {
    const newFunc = {
        funkcionalnost: customFunc.value
    }
    if (form.customFunkcionalnosti.some(f => f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()))
        alert("Ova funkcionalnost je vec dodata kao custom funkcionalnost")
    else if (props.existingFuncs.some(f => f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase())) {
        alert("Ova funkcionalnost vec postoji u sistemu")
    } else {
        form.customFunkcionalnosti.push(newFunc)
        customFunc.value = ""
    }
}
const toggleFunctionality = (func) => {
    const findIndex = form.funkcionalnosti.findIndex(f => f.id === func.id)
    if (findIndex !== -1) {
        form.funkcionalnosti.splice(findIndex, 1)
    } else {
        form.funkcionalnosti.push(func)
    }
}
const removeCustomFunc = (cf) => {
    const findIndex = form.customFunkcionalnosti.findIndex(f => f.funkcionalnost == cf.funkcionalnost)
    if (findIndex !== -1) {
        form.customFunkcionalnosti.splice(findIndex, 1)
    }
}
const emit = defineEmits(
    ['closeModal']
)
const submit = () => {
    form.post(route('paket.store'), {
        onSuccess: () => {
            form.reset();
            emit('closeModal')
        }
    })

}
</script>

<template>

    <div v-if="show" class="modal fade show d-block" tabindex="-1" role="dialog">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Dodavanje paketa</h5>
                        <button type="button" class="btn-close" @click="$emit('closeModal')"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submit">
                            {{ form.errors }}

                            <div class="">
                                <InputLabel value="Naziv paketa"/>
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.ime_paketa"

                                />


                            </div>
                            <div class="mt-2 flex flex-col gap-1">
                                <InputLabel value="Izaberite trziste/a"/>
                                <div class="flex items-center mt-2 gap-2" v-for="trziste in trzista" :key="trziste.id">
                                    <label :for="trziste.ime_trzista" class="text-lg font-semibold">{{
                                            trziste.ime_trzista
                                        }}</label>
                                    <input
                                        :id="trziste.ime_trzista"
                                        @change="toggleMarket(trziste)"
                                        :checked="marketChecked(trziste)"
                                        class="form-check-input border-2 border-black" type="checkbox">

                                </div>
                            </div>
                            <div class=" mt-3">
                                <InputLabel value="Cena"/>

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.cena"

                                />


                            </div>
                            <div class="mt-3">
                                <InputLabel value="Broj besplatnih instalacija godisnje"/>

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.broj_besplatnih_instalacija_godisnje"

                                />


                            </div>
                            <div>
                                <div class="flex flex-col gap-2 mt-4">
                                    <label for="">Dodaj custom funkcionalnost (uneti pa pritisnuti enter)</label>
                                    <TextInput
                                        v-model="customFunc"
                                        @keydown.enter.prevent="addCustomFunc"
                                        type="text"
                                        class="mt-1 block w-full"
                                    />
                                </div>
                            </div>
                            <div class="mt-4">
                                <InputLabel value="Izaberite funkcionalnosti"/>
                                <div class="bg-white shadow rounded p-4 overflow-y-auto max-h-[200px]">
                                    <div class="flex items-center justify-between gap-4" v-for="func in existingFuncs"
                                         :key="func.id">
                                        <div class="flex flex-row-reverse gap-2">
                                            <label class="form-check-label" :for="func.funkcionalnost">
                                                {{ func.funkcionalnost }}
                                            </label>
                                            <input
                                                :checked="form.funkcionalnosti.includes(func)"
                                                @change="toggleFunctionality(func)"
                                                class="form-check-input border-2 border-black" type="checkbox" value=""
                                                :id="func.funkcionalnost">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span>Custom funkcionalnosti koje ste dodali: </span>
                                    <div class="mt-1 bg-white shadow rounded p-4 overflow-y-auto max-h-[200px]">
                                        <div class="flex justify-between items-center"
                                             v-if="form.customFunkcionalnosti.length>0"
                                             v-for="cf in form.customFunkcionalnosti">
                                            <span> {{ cf.funkcionalnost }}</span>
                                            <span class="text-2xl text-red-600 font-semibold cursor-pointer"
                                                  @click="removeCustomFunc(cf)"> - </span>
                                        </div>
                                        <span v-else>Trenutno nema dodatih custom funkcionalnosti</span>

                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="$emit('closeModal')">Close
                                </button>
                                <button type="submit" class="btn btn-primary"
                                >
                                    Dodaj paket
                                </button>
                            </div>
                        </form>
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
