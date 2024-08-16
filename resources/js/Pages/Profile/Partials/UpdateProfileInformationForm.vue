<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import {computed} from "vue";

defineProps({
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    username: user.username,

});
const roleToString = computed(() => {
    if (user.role_id == 1) {
        return "Super Admin"
    } else if (user.role_id == 2){
        return "Admin"
    }else if(user.role_id == 3){
        return "B2B User"
    }else{
        return "Obican user"
    }
})

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Informacije profila</h2>

            <p class="mt-1 text-sm text-gray-600">
                Promena informacija profila
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="username" value="Username"/>

                <TextInput
                    id="username"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.username"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.username"/>
            </div>

            <div>
                <InputLabel for="" value="Tip korisnika"/>

                <span class="">{{roleToString}}</span>

                <InputError class="mt-2" :message="form.errors.username"/>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
