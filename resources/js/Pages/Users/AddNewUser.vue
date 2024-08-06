<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    username: '',
    email: '',
    role: null,
});
const props = defineProps({
    roles: Array,
})
const submit = () => {
    form.post(route('dodaj-korisnika.store'))
};
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="username" value="Username" />

                    <TextInput
                        id="username"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.username"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.username" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel value="Tip korisnika" />

                    <select class="form-select mt-2" v-model="form.role" aria-label="Default select example">
                        <option :value="role.id" class="text-capitalize" v-for="role in roles" :key="role.id"> {{role.role}}</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.role" />
                </div>



                <div class="flex items-center justify-end mt-4">


                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Snimi
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>


</template>
