<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from "vue";

const functionalities = ref([]);

const props = defineProps({
    packages: Array,
    chosenMarket: Object
})


const form = useForm({
    ime_paketa: "",
    cena: "",
    broj_big: "",
    trziste: props.chosenMarket,
});








const submit = () => {
    console.log("TEST")
    form.post(route('paket.store'))
}
</script>

<template>
    <h4>Kreira se paket za trziste: {{chosenMarket.ime_trzista}}</h4>
    <div class="w-full flex items-center justify-center mb-10">

        <form class="w-full flex flex-col items-center justify-center mt-10" id="packageform" @submit.prevent="submit">
            <div class="lg:w-2/5 md:w-3/5">

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
                    <InputLabel for="cena" value="Cena paketa"/>

                    <TextInput
                        id="cena"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.cena"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.cena"/>
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
            </div>


            <div class="mt-4">
                <button class="btn btn-primary" type="submit" form="packageform">Snimi paket</button>
            </div>

        </form>
    </div>


</template>
