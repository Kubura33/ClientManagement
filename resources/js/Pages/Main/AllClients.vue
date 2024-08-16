<script setup>

import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router, useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import {onMounted, ref, inject} from "vue";

const props = defineProps({
        companies: Array,
    }
)

const form = useForm({
    klijent: "",
    ime_firme: "",
    PIB: "",
    MB: "",


})
const title = ref("");
const goToContract = (companyId) => {
    router.get(route('contract.show', {id: companyId}))
}
const submit = () => form.get(route('filter', {filter: props.filter}))
const setTitle = inject('setTitle')
onMounted(() => {
    if(props.filter == 1){
        title.value = " - U procesu"
    }else if(props.filter == 2){
        title.value = " - Ceka se neka funkcionalnost"
    }else if(props.filter == 3){
        title.value = " - Ne javljalju se"
    }
    setTitle(title.value)

})
</script>

<template>

    <div class="p-4">
        <form @submit.prevent="submit">
            <div class="w-1/4 ">
                <InputLabel for="name" value="Ime firme - grupacije/klijenta"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.klijent"
                />

                <InputError :message="form.errors.klijent" v-if="form.errors.klijent"/>

            </div>

            <div id="firma" class="flex flex-row gap-4">
                <div class="mt-4 flex-grow-1 max-w-[45%]">
                    <InputLabel for="ime_firme" value="Ime firme"/>

                    <TextInput
                        id="ime_firme"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.ime_firme"
                    />

                    <InputError :message="form.errors.ime_firme" v-if="form.errors.ime_firme"/>

                </div>
                <div class="mt-4">
                    <InputLabel for="PIB" value="PIB"/>

                    <TextInput
                        id="PIB"
                        type="text"
                        v-model="form.PIB"
                        class="mt-1 block w-full"
                        maxlength="9"
                    />

                    <InputError :message="form.errors.PIB" v-if="form.errors.PIB"/>

                </div>
                <div class="mt-4">
                    <InputLabel for="MB" value="MB"/>

                    <TextInput
                        id="MB"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.MB"
                        maxlength="8"
                    />
                    <InputError :message="form.errors.MB" v-if="form.errors.MB"/>


                </div>

                <div class="mt-12">
                    <PrimaryButton class="w-32"> Pretrazi</PrimaryButton>
                </div>

            </div>


        </form>

        <div class="bg-white shadow mt-6 w-2/4  p-4 flex flex-col gap-4">
            <table class="table table-hover" v-if="companies.length>0">
                <thead>
                <tr>
                    <th scope="col">Ime firme</th>
                    <th scope="col">PIB</th>
                    <th scope="col">MB</th>
                    <th scope="col">Status</th>
                    <th scope="col">Trziste</th>

                </tr>
                </thead>
                <tbody>
                <tr @dblclick="goToContract(company.id)" v-for="company in companies" class="cursor-pointer">
                    <th scope="row"> {{company.ime }} </th>
                    <td> {{company.PIB}} </td>
                    <td>{{company.MB}}</td>
                    <td>{{ company.status }}</td>
                    <td>{{company.market.ime_trzista}}</td>
                </tr>
                </tbody>
            </table>
            <div v-else class="text-center font-bold text-lg">
                Trenutno ne postoje firme na ovom trzistu
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
