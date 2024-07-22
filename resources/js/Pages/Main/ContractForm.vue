<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            Unos nove firme
        </template>
        <div class="p-4">
            <form @submit.prevent="submit">
                <div class="w-3/4 mx-auto">
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
                    <div class="mt-4 flex-grow-1">
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
                    <div class="mt-4">
                        <InputLabel for="email" value="Paket"/>

                        <select
                            class="form-select h-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                            aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>


                    </div>

                </div>

                <div id="text-boxes" class="flex flex-row gap-10 mt-6">
                    <div>
                        <InputLabel for="email" value="Kontakt osoba"/>

                        <div class="w-80 bg-white shadow p-4 rounded overflow-y-auto max-h-[320px]">
                            <div class="flex flex-col border-b-2 ">
                                <span>Kontakt 1</span>
                                <span>Ime i prezime</span>
                                <span>Telefon</span>
                                <span>Email</span>
                            </div>
                            <div class="flex flex-col border-b-2">
                                <span>Kontakt 1</span>
                                <span>Ime i prezime</span>
                                <span>Telefon</span>
                                <span>Email</span>
                            </div>
                            <div class="flex flex-col border-b-2">
                                <span>Kontakt 1</span>
                                <span>Ime i prezime</span>
                                <span>Telefon</span>
                                <span>Email</span>
                            </div>
                            <div class="flex flex-col">
                                <span>Kontakt 1</span>
                                <span>Ime i prezime</span>
                                <span>Telefon</span>
                                <span>Email</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <InputLabel for="email" value="Funkcionalnosti"/>
                        <div class="bg-white shadow rounded  pr-12 pl-5 py-6 min-h-[320px] min-w-[400px] flex flex-col gap-1">
                            <div class="form-check flex gap-2">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>


                        </div>
                    </div>

                    <div id="kontakt">
                        <InputLabel for="email" value="Nacin pristupa"/>
                        <div data-mdb-input-init class="form-outline min-w-[300px]">
                            <textarea class="form-control" id="textAreaExample1" rows="13"></textarea>
                        </div>
                    </div>
                </div>
                <div id="status_datum" class="flex flex-row gap-4">
                    <div class="mt-4">
                        <InputLabel for="email" value="Status implementacije"/>

                        <select
                            class="form-select h-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                            aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>


                    </div>
                    <div class="mt-4">
                        <InputLabel for="email" value="Datum"/>

                        <TextInput
                            id="email"
                            type="date"
                            class="mt-1 block w-full"

                            required
                            autocomplete="username"
                        />


                    </div>
                </div>
                <div id="ugovor_aneks" class="flex flex-row gap-4">
                    <div class="mt-4">
                        <InputLabel for="password" value="Ugovor"/>

                        <TextInput
                            id="password"
                            type="text"
                            class="mt-1 block w-full"

                            required
                            autocomplete="new-password"
                        />


                    </div>

                    <div class="mt-4">
                        <InputLabel for="password_confirmation" value="Aneks"/>

                        <TextInput
                            id="aneks"
                            type="text"
                            class="mt-1 block w-full"

                            required
                            autocomplete="new-password"
                        />


                    </div>
                    <div class="mt-4">
                        <InputLabel for="email" value="Tip fakturisanja"/>

                        <select
                            class="form-select h-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                            aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>


                    </div>
                    <div class="mt-4">
                        <InputLabel for="iznos_fakture" value="Iznos fakture"/>

                        <TextInput
                            id="iznos_fakture"
                            type="text"
                            class="mt-1 block w-full"

                            required
                            autocomplete="new-password"
                        />


                    </div>

                </div>
                <div class="mt-10">
                    <PrimaryButton>Zavrsi popunjavanje</PrimaryButton>
                </div>


            </form>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
