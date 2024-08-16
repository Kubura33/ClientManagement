<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {onMounted, ref, inject} from "vue";
import {v4 as uuidv4} from 'uuid';
import InputError from "@/Components/InputError.vue";
import ContactModal from "@/Components/ContactModal.vue";
import AddFunctionalityModal from "@/Components/AddFunctionalityModal.vue";
import InstallationDateModal from "@/Components/InstallationDateModal.vue";

const props = defineProps({
    packages: Array,
    statuses: Array,
    fakturisanje: Array,
    existingFunctionalities: Array,
    market: Object,
})
const page = usePage()
const chosenFunctionalities = ref([])
const forma = useForm({
    trziste_id: props.market.id,
    klijent: "",
    ime_firme: "",
    PIB: "",
    MB: "",
    package: null,
    contacts: [],
    functionalities: chosenFunctionalities,
    customFunctionalities: [],
    connection: "",
    implementation_status: null,
    tip_implementacije: "",
    date: null,
    ugovor: "",
    aneks: "",
    tip_fakturisanja: null,
    iznos_fakture: null,
    godina_ugovora: null,
    broj_preostalih_instalacija: null,
    newDates: [],
})





//CONTACT MODAL
const showContactModal = ref(false);
const contactModal = ref(null)
const isEditContact = ref(false)
const closeContactModal = () => {
    showContactModal.value = false
    contactModal.value.clear()
}
const openContactModal = () => {
    isEditContact.value = false
    showContactModal.value = true
    contactModal.value.clear()
}
const addToContacts = (newContact) => {

    if (newContact.ime_prezime && (newContact.phone || newContact.email)) {
        const isEmailDuplicate = doesContactExist(newContact)

        if (isEmailDuplicate) {
            alert("Korisnik sa ovim email-om vec postoji");
        }else {
            forma.contacts.push(JSON.parse(JSON.stringify(newContact)));
            closeContactModal();
        }


    }

}
const doesContactExist = (newContact) => {
    const isEmailDuplicate = forma.contacts.some(contact => {
        // Ensure that we only compare if the emails are non-empty
        const contactEmailValid = contact.email && contact.email.trim() !== "";
        const contactEmail2Valid = contact.email2 && contact.email2.trim() !== "";
        const newEmailValid = newContact.email && newContact.email.trim() !== "";
        const newEmail2Valid = newContact.email2 && newContact.email2.trim() !== "";

        return (
            (contactEmailValid && newEmailValid && contact.email === newContact.email) ||
            (contactEmailValid && newEmail2Valid && contact.email === newContact.email2) ||
            (contactEmail2Valid && newEmailValid && contact.email2 === newContact.email) ||
            (contactEmail2Valid && newEmail2Valid && contact.email2 === newContact.email2)
        );
    });
    return isEmailDuplicate;
}
const contactToEdit = ref(null)
//cTe is contactToEdit passed from v-for
const editContact = (cTe) => {
    contactToEdit.value = cTe
    showContactModal.value = true
    isEditContact.value = true



}
const finishEdittingContact = (changedContact) => {
    const editedContact = { ...changedContact };
    const index = forma.contacts.findIndex(c => c.id === editedContact.id);
    if (index !== -1) {
        forma.contacts[index] = editedContact; // Direct assignment should be reactive in Vue 3
        closeContactModal();
    }
}
//FUNCTIONALITIES

const showFunctionalityModal = ref(false);
const funcModal = ref(null)
const setFuncs = () => {
    const selectedPackage = props.packages.find(pkg => pkg.id === forma.package)

    if (selectedPackage) {
        chosenFunctionalities.value = [...selectedPackage.functionalities]
        forma.broj_preostalih_instalacija = selectedPackage.broj_besplatnih_instalacija_godisnje
        forma.iznos_fakture = selectedPackage.cena

    }
}
const toggleFunctionality = (func) => {
    const funcIndex = chosenFunctionalities.value.findIndex(f => f.funkcionalnost === func.funkcionalnost);
    const customFuncIndex = forma.customFunctionalities.findIndex(f => f.funkcionalnost === func.funkcionalnost);
    if (funcIndex !== -1) {
        chosenFunctionalities.value.splice(funcIndex, 1);
    } else {
        chosenFunctionalities.value.push(func);
    }
    if(funcIndex !== -1){
        forma.customFunctionalities.splice(customFuncIndex, 1)
    }

};
const closeFunctionalityModal = () => {
    showFunctionalityModal.value = false
    if(funcModal.value){
        funcModal.value.reset()
    }
}
const openFunctionalityModal = () => {
    showFunctionalityModal.value = true
    if(funcModal.value){
        funcModal.value.reset()
    }
}
const saveFunc = (checkedFunctionalities, customFunc) => {
    if (Array.isArray(forma.customFunctionalities)) {
        if(customFunc.length > 0)
            forma.customFunctionalities = customFunc;
    } else {
        console.log('Expected forma.customFunctionalities to be an array');
    }
    forma.functionalities = checkedFunctionalities;
    if (chosenFunctionalities.value) {
        chosenFunctionalities.value = chosenFunctionalities.value.concat(checkedFunctionalities);
    }
    closeFunctionalityModal();
}
const setTitle = inject('setTitle')
onMounted(() => {
    setTitle(" - Novi klijent")

})

// DATUMI BESPLATNIH INSTALACIJA



const showInstallationDateModal = ref(false)
const InstallationDateModalRef = ref(null)
const openInstallationDateModal = () => {
    showInstallationDateModal.value = true
}
const closeInstallationDateModal = () => {
    showInstallationDateModal.value = false
    if(forma.newDates.length < 1){
        InstallationDateModalRef.value.clear()
    }
}
const addDates = (dates) => {
    forma.newDates = dates
    closeInstallationDateModal()
}

const submit = () => {
    forma.functionalities = chosenFunctionalities.value
    forma.post(route('novi-klijent'))
}

</script>

<template>
    <AddFunctionalityModal :show="showFunctionalityModal"
                           @closeModal="closeFunctionalityModal"
                           :existingFunctionalities="existingFunctionalities"
                           :chosen-functionalities="chosenFunctionalities"
                           :form-custom-funcs="forma.customFunctionalities"
                           @saveFunctionalities="saveFunc"
                           ref="funcModal"
    ></AddFunctionalityModal>
    <InstallationDateModal
        :show="showInstallationDateModal"
        :broj_instalacija="forma.broj_preostalih_instalacija"
        @closeModal="closeInstallationDateModal"
        @saveDates="addDates"
        ref="InstallationDateModalRef"
    ></InstallationDateModal>
    <ContactModal
        @addToContacts="addToContacts"
        :show="showContactModal"
        @closeModal="closeContactModal"
        :contact-to-edit="contactToEdit"
        :is-edit="isEditContact"
        @finishEdit="finishEdittingContact"
        ref="contactModal"></ContactModal>
    <div class="ml-[100px]">
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
                <div class="contacts">
                    <div class="flex justify-between ">
                        <label class="block text-gray-700 text-sm font-bold ">Kontakti</label>
                        <span class="text-3xl font-bold text-green-600 cursor-pointer" @click="openContactModal">&#43;</span>
                    </div>

                    <div
                        class="bg-white shadow rounded w-[300px] min-h-[320px] max-h-[320px]  overflow-y-auto p-4">
                        <div class="contact-item mb-4 flex flex-col hover:bg-gray-200 p-1 cursor-pointer"
                             @dblclick="editContact(contact)"
                             v-for="contact in forma.contacts"
                             v-if="forma.contacts.length>0">
                            <span class="text-xl font-semibold mb-2">{{contact.ime_prezime}}</span>
                            <span class="text-sm text-gray-600" v-if="contact.email">{{contact.email}}</span>
                            <span class="text-sm text-gray-600" v-if="contact.email2">{{contact.email2}}</span>
                            <span class="text-sm text-gray-600" v-if="contact.phone">{{contact.phone}}</span>
                            <span class="text-sm text-gray-600" v-if="contact.phone2">{{contact.phone2}}</span>

                        </div>
                        <div v-else>
                            Trenutno nema dodatih kontakata, kliknite na plus da ih dodate
                        </div>

                    </div>
                    <InputError :message="forma.errors.contacts" />
                </div>

                <div>
                    <div class="flex justify-between ">
                        <label class="block text-gray-700 text-sm font-bold ">Funkcionalnosti</label>
                        <span class="text-3xl font-bold text-green-600 cursor-pointer" @click="openFunctionalityModal">&#43;</span>
                    </div>

                    <div
                        class="bg-white shadow rounded  pr-12 pl-5 py-6 h-[320px] overflow-y-auto min-w-[400px] flex flex-col gap-1 relative">
                        <div class="flex justify-between" v-if="forma.package">
                            <label for="">Naziv funkc.</label>
                            <label for="">Odradjeno</label>
                        </div>
                        <div v-else>
                            Izaberite paket za prikaz funkcionalnosti
                        </div>
                        <div class="flex items-center justify-between gap-4"
                             v-for="func in chosenFunctionalities"
                             :key="uuidv4()"
                        >
                            <div class="flex flex-row-reverse gap-2">
                                <label class="form-check-label" :for="func.funkcionalnost">
                                    {{ func.funkcionalnost }}
                                </label>
                                <input
                                    @change="toggleFunctionality(func)"
                                    :checked="chosenFunctionalities.includes(func)"

                                    class="form-check-input border-2 border-black" type="checkbox" value=""
                                    :id="func.funkcionalnost">
                            </div>
                            <div>
                                <input class="form-check-input border-2 border-black" type="checkbox" value=""
                                       @change="func.isDone = !func.isDone"
                                       :checked="func.isDone"
                                >
                                {{func.isDone}}

                            </div>

                        </div>
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
                        <input type="radio" v-model="forma.tip_implementacije" value="Čeka se neka funkcionalnost">
                    </div>
                    <InputError :message="forma.errors.tip_implementacije" v-if="forma.errors.tip_implementacije"/>
                </div>
                <div class="mt-4">
                    <InputLabel for="datum" value="Datum implementacije"/>

                    <TextInput
                        id="datum"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="forma.date"
                        :required="forma.implementation_status == 3"
                    />
                    <InputError :message="forma.errors.date" v-if="forma.errors.date"/>

                </div>

                <div class="mt-4">
                    <InputLabel for="godina" value="Godina ugovora"/>

                    <TextInput
                        id="godina"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.godina_ugovora"
                        required
                    />
                    <InputError :message="forma.errors.godina_ugovora" v-if="forma.errors.godina_ugovora"/>

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
            <div id="instalacije" class="flex flex-row gap-4">
                <div class="mt-4">
                    <InputLabel for="bpig" value="Broj besplatnih instalacija godisnje"/>

                    <TextInput
                        id="bpig"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.broj_preostalih_instalacija"
                    />
                    <InputError :message="forma.errors.ugovor" v-if="forma.errors.broj_preostalih_instalacija"/>

                </div>

                <div class="mt-5 flex justify-center items-center">
                    <button class="btn btn-secondary" @click.prevent="openInstallationDateModal" :disabled="!forma.broj_preostalih_instalacija">Pogledaj listu datuma besplatnih instalacija</button>
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
