<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import {computed, reactive, ref} from "vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    packages: Array,
    statuses: Array,
    fakturisanje: Array,
    contract: Object
})
console.log(props.contract)
const contact = reactive({
    id: 0,
    ime_prezime: "",
    email1: "",
    email2: "",
    telefon1: "",
    telefon2: ""
});
const customFunc = ref("");
const doesntExist = ref(1)
const forma = useForm({
    klijent: props.contract?.company?.client ? props.contract.company.client.ime : "",
    ime_firme: props.contract?.company ? props.contract.company.ime : "",
    PIB: props.contract?.company ? props.contract.company.PIB : "",
    MB: props.contract?.company ? props.contract.company.MB : "",
    package: props.contract ? props.contract.paket_id : null,
    contacts: props.contract?.company ? props.contract.company.contacts : null,
    functionalities: props.contract ? props.contract.funkcionalnosti : null,
    connection: props.contract?.company ? props.contract.company.connection.konekcija : "null",
    implementation_status: props.contract ? props.contract.status_id : null,
    tip_implementacije: props.contract ? props.contract.status_implementiranja : null,
    date: props.contract ? props.contract.datum_implementacije : null,
    ugovor: props.contract ? props.contract.broj_ugovora : "",
    aneks: props.contract ? props.contract.broj_aneksa : "",
    tip_fakturisanja: props.contract ? props.contract.fakturisanje_id : null,
    iznos_fakture: props.contract ? props.contract.iznos_fakture : null
})

const chosenFunctionalities = ref(props.contract ? props.contract.funkcionalnosti : [])
const addToContacts = () => {
    if (contact.ime_prezime && contact.telefon1 && contact.email1) {
        contact.id++;
        forma.contacts.push(JSON.parse(JSON.stringify(contact)));
        contact.ime_prezime = ""
        contact.telefon1 = ""
        contact.telefon2 = ""
        contact.email1 = ""
        contact.email2 = ""
    }

}
const setFuncs = () => {
    const selectedPackage = props.packages.find(pkg => pkg.id === forma.package)

    if (selectedPackage) {
        selectedPackage.functionalities.forEach(f => chosenFunctionalities.value.push(f))
        // chosenFunctionalities.value.push(selectedPackage.functionalities)
        forma.functionalities = [...chosenFunctionalities.value];

    }
}
const toggleFunctionality = (func) => {
    const funcIndex = forma.functionalities.findIndex(f => f.id === func.id);
    if (funcIndex !== -1) {
        forma.functionalities.splice(funcIndex, 1);
    } else {
        forma.functionalities.push(func);
    }

};
const addCustomFunc = () => {

    const newFunc = {
        funkcionalnost: customFunc.value,

    }
    forma.functionalities.forEach(f => {
        if (f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()) {

            doesntExist.value = 0
        } else {
            doesntExist.value = 1
        }
    })
    if (doesntExist) {
        forma.functionalities.push(newFunc)
        chosenFunctionalities.value.push(newFunc)
    }

}

const isFuncChecked = (func) => computed(() => {
    return props.contract?.funkcionalnosti.some(f => f.funkcionalnost === func.funkcionalnost);
})
const submit = () => forma.post(route('contract.update', {contract: props.contract.id}))
</script>

<template>

    <div class="p-4">
        <form @submit.prevent="submit">
            <div class="w-2/4 ">
                <InputLabel for="name" value="Ime firme - grupacije/klijenta"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="forma.klijent"


                />
                <InputError :message="forma.errors.klijent" v-if="forma.errors.klijent"/>

            </div>

            <div id="firma" class="grid grid-cols-1 gap-4 lg:grid-cols-5 xl:grid-cols-8">
                <div class="mt-4  lg:col-span-3">
                    <InputLabel for="ime_firme" value="Ime firme"/>

                    <TextInput
                        id="ime_firme"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.ime_firme"
                        required

                    />
                    <InputError :message="forma.errors.ime_firme" v-if="forma.errors.ime_firme"/>

                </div>
                <div class="mt-4 ">
                    <InputLabel for="PIB" value="PIB"/>

                    <TextInput
                        id="PIB"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.PIB"
                        maxlength="9"
                        required

                    />
                    <InputError :message="forma.errors.PIB" v-if="forma.errors.PIB"/>

                </div>
                <div class="mt-4 ">
                    <InputLabel for="MB" value="MB"/>

                    <TextInput
                        id="MB"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.MB"
                        maxlength="8"

                    />
                    <InputError :message="forma.errors.MB" v-if="forma.errors.MB"/>

                </div>
                <div class="mt-4">
                    <InputLabel for="paket" value="Paket"/>

                    <select
                        id="paket"
                        class="form-select h-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                        aria-label="Default select example"
                        v-model="forma.package"
                        @change="setFuncs"
                    >
                        <option
                            v-for="pkg in packages" :key="pkg.id"
                            :value="pkg.id"
                        >
                            {{ pkg.ime }}
                        </option>
                    </select>
                    <InputError :message="forma.errors.package" v-if="forma.errors.package"/>

                </div>

            </div>

            <div id="text-boxes" class="flex flex-row gap-10 mt-6">
                <div>
                    <InputLabel for="email" value="Funkcionalnosti"/>
                    <div

                        class="bg-white shadow rounded  pr-12 pl-5 py-6 min-h-[320px] min-w-[400px] flex flex-col gap-1 relative">
                        <div class="flex justify-between" v-if="forma.package">
                            <label for="">Naziv funkc.</label>
                            <label for="">Odradjeno</label>
                        </div>
                        <div v-else>
                            Izaberite paket za prikaz funkcionalnosti
                        </div>
                        <div class="flex items-center justify-between gap-4" v-for="func in chosenFunctionalities">
                            <div class="flex flex-row-reverse gap-2">
                                <label class="form-check-label" :for="func.funkcionalnost">
                                    {{ func.funkcionalnost }}
                                </label>
                                <input
                                    @change="toggleFunctionality(func)"
                                    :checked="isFuncChecked(func)"

                                    class="form-check-input border-2 border-black" type="checkbox" value=""
                                    :id="func.funkcionalnost">
                            </div>
                            <div>
                                <input class="form-check-input border-2 border-black" type="checkbox" value=""
                                       :checked="func.isDone"
                                       @change="func.isDone = !func.isDone"
                                >

                            </div>

                        </div>
                        <div class="flex gap-2 mt-4">
                            <label for="">Dodaj funkcionalnost</label>
                            <TextInput
                                v-model="customFunc"
                                @keydown.enter.prevent="addCustomFunc"
                                type="text"
                                class="mt-1 block w-full"
                            />
                        </div>
                        <InputError :message="'Ova funkcionalnost je vec dodata'" v-if="!doesntExist"/>
                    </div>
                    <InputError :message="forma.errors.functionalities" v-if="forma.errors.functionalities"/>
                </div>

                <div id="kontakt">
                    <InputLabel for="email" value="Nacin pristupa"/>
                    <div data-mdb-input-init class="form-outline min-w-[300px]">
                        <textarea v-model="forma.connection" class="form-control" id="textAreaExample1"
                                  rows="13"></textarea>
                    </div>
                    <InputError :message="forma.errors.connection" v-if="forma.errors.connection"/>
                </div>
            </div>
            <div id="kontakti" class="mt-5 flex gap-6">
                <div id="poljeZaUnosKontakta">
                    <InputLabel value="Kontakt osoba"/>

                    <div class="w-80 bg-white shadow p-6  rounded overflow-y-auto ">
                        <div class="flex flex-col border-b-2 ">
                            <div class="">
                                <InputLabel value="Ime i prezime"/>

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="contact.ime_prezime"

                                />


                            </div>
                            <div class="mt-1">
                                <InputLabel value="Email"/>

                                <TextInput
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="contact.email1"
                                />


                            </div>
                            <div class="mt-1">
                                <InputLabel value="Email 2"/>

                                <TextInput
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="contact.email2"
                                />


                            </div>
                            <div class="mt-1">

                                <InputLabel for="text" value="Telefon"/>

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="contact.telefon1"
                                />
                                <InputLabel for="text" value="Telefon 2"/>

                                <TextInput

                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="contact.telefon2"
                                />

                            </div>
                            <span class="mt-2 font-semibold cursor-pointer"
                                  @click="addToContacts"
                            >Dodaj u kontakt listu</span>
                        </div>
                        <InputError :message="forma.errors.contacts" v-if="forma.errors.contacts"/>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-3 gap-4 " v-if="forma.contacts">
                    <div class="flex flex-col gap-2 bg-white shadow p-4 rounded text-lg"
                         v-for="(addedContact, index) in forma.contacts" :key="index">
                        <span class="text-xl font-bold">Kontakt {{ index + 1 }}</span>
                        <span class="flex gap-1"> <span class="font-semibold">Ime i prezime: </span> <span
                            class="font-bold">{{ addedContact.ime_prezime }}</span> </span>
                        <span class="flex gap-1"> <span class="font-semibold">Telefon 1:</span> <span class="font-bold">{{
                                addedContact.telefon
                            }}</span>  </span>
                        <span v-if="addedContact.telefon2" class="flex gap-1"> <span
                            class="font-semibold">Telefon 2:</span> <span class="font-bold">{{
                                addedContact.telefon2
                            }}</span> </span>
                        <span class="flex gap-2"> <span class="font-semibold">Email </span>
                            <span class="font-bold">{{ addedContact.email }}</span>  </span>
                        <span v-if="addedContact.email2" class="flex gap-1"> <span
                            class="font-semibold">Email 2: </span> <span class="font-bold">{{
                                addedContact.email2
                            }}</span>  </span>
                    </div>
                </div>
            </div>
            <div id="status_datum" class="flex flex-row gap-4 items-center">
                <div class="mt-4">
                    <InputLabel for="email" value="Status implementacije"/>

                    <select
                        v-model="forma.implementation_status"
                        class="form-select h-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                        aria-label="Default select example">
                        <option :value="status.id" v-for="status in statuses" :key="status.id"> {{ status.naziv }}
                        </option>
                    </select>

                    <InputError :message="forma.errors.implementation_status"
                                v-if="forma.errors.implementation_status"/>
                </div>
                <div v-if="forma.implementation_status === 3" class="mt-10 flex flex-col gap-2">
                    <div class="flex gap-2 items-center">
                        <label for="">Potpuno</label>
                        <input v-model="forma.tip_implementacije" type="radio" value="Potpuno">
                    </div>
                    <div class="flex gap-2 items-center">
                        <label for="">Čeka se neka funkcionalnost</label>
                        <input type="radio" v-model="forma.tip_implementacije" value="Ceka se neka funkcionalnost">
                    </div>
                    <InputError :message="forma.errors.tip_implementacije" v-if="forma.errors.tip_implementacije"/>
                </div>
                <div class="mt-4">
                    <InputLabel for="datum" value="Datum"/>

                    <TextInput
                        id="datum"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="forma.date"
                        required
                    />
                    <InputError :message="forma.errors.date" v-if="forma.errors.date"/>

                </div>
            </div>
            <div id="ugovor_aneks" class="flex flex-row gap-4">
                <div class="mt-4">
                    <InputLabel for="ugovor" value="Ugovor"/>

                    <TextInput
                        id="ugovor"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.ugovor"
                        required
                        autocomplete="new-password"
                    />
                    <InputError :message="forma.errors.ugovor" v-if="forma.errors.ugovor"/>

                </div>

                <div class="mt-4">
                    <InputLabel for="aneks" value="Aneks"/>

                    <TextInput
                        id="aneks"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.aneks"
                        required
                        autocomplete="new-password"
                    />
                    <InputError :message="forma.errors.aneks" v-if="forma.errors.aneks"/>

                </div>
                <div class="mt-4">
                    <InputLabel for="email" value="Tip fakturisanja"/>

                    <select
                        v-model="forma.tip_fakturisanja"
                        class="form-select h-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                        aria-label="Default select example">
                        <option :value="faktura.id" v-for="faktura in fakturisanje" :key="faktura.id">
                            {{ faktura.naziv_fakturisanja }}
                        </option>

                    </select>

                    <InputError :message="forma.errors.tip_fakturisanja" v-if="forma.errors.tip_fakturisanja"/>
                </div>
                <div class="mt-4">
                    <InputLabel for="iznos_fakture" value="Iznos fakture"/>

                    <TextInput
                        id="iznos_fakture"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.iznos_fakture"
                        required
                    />

                    <InputError :message="forma.errors.iznos_fakture" v-if="forma.errors.iznos_fakture"/>
                </div>

            </div>
            <div class="mt-10">
                <PrimaryButton>Zavrsi popunjavanje</PrimaryButton>
            </div>


        </form>
    </div>

</template>

<style scoped>

</style>
