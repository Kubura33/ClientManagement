<script setup>

import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import {Link, useForm} from "@inertiajs/vue3";

const form = useForm({
    trziste: ""
})
const submit = () => form.post(route('trziste.store'))
</script>

<template>
    <div class="flex items-center">
        <form @submit.prevent="submit" class="">
            <div>
                <InputLabel for="trziste" value="Naziv trzista" />

                <TextInput
                    id="trziste"
                    type="text"
                    class="mt-1 block w-full text-capitalize"
                    v-model="form.trziste"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.trziste" />
            </div>
            <p>Nakon kreiranja trzista bicete redirektovani na stranicu za kreiranje paketa</p>



            <div class="flex items-center justify-end mt-4">


                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Dodaj trziste
                </PrimaryButton>
            </div>
        </form>
    </div>

</template>

<style scoped>

</style>
