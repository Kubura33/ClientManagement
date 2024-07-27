<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
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

                        required
                        autofocus
                        autocomplete="name"
                    />


                </div>

                <div id="firma" class="flex flex-row gap-4">
                    <div class="mt-4 flex-grow-1 max-w-[45%]">
                        <InputLabel for="email" value="Ime firme"/>

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"

                            required
                            autocomplete="username"
                        />


                    </div>
                    <div class="mt-4">
                        <InputLabel for="email" value="PIB"/>

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"

                            required
                            autocomplete="username"
                        />


                    </div>
                    <div class="mt-4">
                        <InputLabel for="email" value="MB"/>

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"

                            required
                            autocomplete="username"
                        />


                    </div>

                    <div class="mt-12">
                        <PrimaryButton class="w-32"> Pretrazi</PrimaryButton>
                    </div>

                </div>


            </form>
            <div class="bg-white shadow mt-6 w-2/4  p-4 flex flex-col gap-4">
                <div class="flex">
                    <div class="w-1/2 ">
                        Ime firme
                    </div>
                    <div class="w-1/2">
                        Trziste
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/2 ">
                        Ime firme
                    </div>
                    <div class="w-1/2">
                        Trziste
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/2 ">
                        Ime firme
                    </div>
                    <div class="w-1/2">
                        Trziste
                    </div>
                </div>
            </div>
        </div>

</template>

<style scoped>

</style>
