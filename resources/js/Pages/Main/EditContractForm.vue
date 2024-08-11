<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {computed, inject, onMounted, ref} from "vue";
import InputError from "@/Components/InputError.vue";
import ContactModal from "@/Components/ContactModal.vue";
import AddFunctionalityModal from "@/Components/AddFunctionalityModal.vue";

const props = defineProps({
    packages: Array,
    statuses: Array,
    fakturisanje: Array,
    contract: Object,
    existingFunctionalities: Array,
    otherContracts: Array,
})
const page = usePage()
const chosenFunctionalities = ref(props.contract ? [...props.contract.functionalities] : [])
const forma = useForm({
    klijent: props.contract?.company?.client ? props.contract.company.client.ime : "",
    ime_firme: props.contract?.company ? props.contract.company.ime : "",
    PIB: props.contract?.company ? props.contract.company.PIB : "",
    MB: props.contract?.company ? props.contract.company.MB : "",
    package: props.contract ? props.contract.paket_id : null,
    contacts: props.contract?.company ? props.contract.company.contacts : null,
    functionalities: chosenFunctionalities.value,
    customFunctionalities: [],
    connection: props.contract?.company ? props.contract.company.connection.konekcija : "null",
    implementation_status: props.contract ? props.contract.status_id : null,
    tip_implementacije: props.contract ? props.contract.status_implementiranja : null,
    date: props.contract ? props.contract.datum_implementacije : null,
    ugovor: props.contract ? props.contract.broj_ugovora : "",
    godina_ugovora: props.contract ? props.contract.godina_ugovora : "",
    aneks: props.contract ? props.contract.broj_aneksa : "",
    tip_fakturisanja: props.contract ? props.contract.fakturisanje_id : null,
    iznos_fakture: props.contract ? props.contract.iznos_fakture : null
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
        if(forma.contacts.some(c => (c.email === newContact.email || c.email2 === newContact.email || c.email2 === newContact.email2))){
            alert("Korisnik sa ovim e-mailom vec postoji")
        }else{
            forma.contacts.push(JSON.parse(JSON.stringify(newContact)));
            closeContactModal();
        }


    }

}

const contactToEdit = ref(null)
//cTe is contactToEdit passed from v-for
const editContact = (cTe) => {
    contactToEdit.value = cTe
    showContactModal.value = true
    isEditContact.value = true

}
const finishEdittingContact = (changedContact) => {
    console.log(changedContact);
}
//FUNCTIONALITIES

const showFunctionalityModal = ref(false);
const funcModal = ref(null)
const setFuncs = () => {
    const selectedPackage = props.packages.find(pkg => pkg.id === forma.package);
    chosenFunctionalities.value = [...props.contract.functionalities];
    if (selectedPackage) {
        selectedPackage.functionalities.forEach(f => {
            if (!props.contract.functionalities.some(cF => cF.funkcionalnost === f.funkcionalnost)) {
                chosenFunctionalities.value.push({
                    funkcionalnost: f.funkcionalnost,
                    pivot: {
                        uradjeno: false
                    }
                });
            }
        });
    }
};
const toggleFunctionality = (func) => {
    const funcIndex = chosenFunctionalities.value.findIndex(f => f.funkcionalnost === func.funkcionalnost);
    const customFuncIndex = forma.customFunctionalities.findIndex(f => f.funkcionalnost === func.funkcionalnost);
    if (funcIndex !== -1) {
        chosenFunctionalities.value.splice(funcIndex, 1);
    } else {
        chosenFunctionalities.value.push(func);
    }
    if (funcIndex !== -1) {
        forma.customFunctionalities.splice(customFuncIndex, 1)
    }
    forma.functionalities = [...chosenFunctionalities.value]
};
const closeFunctionalityModal = () => {
    showFunctionalityModal.value = false
    if (funcModal.value) {
        funcModal.value.reset()
    }
}
const isChecked =() => computed((func) => {
    return chosenFunctionalities.value.some(cf => cf.funkcionalnost.toLowerCase() === func.funkcionalnost.toLowerCase())
})
const openFunctionalityModal = () => {
    showFunctionalityModal.value = true
    if (funcModal.value) {
        funcModal.value.reset()
    }
}
const saveFunc = (checkedFunctionalities, customFunc) => {
    if (Array.isArray(forma.customFunctionalities)) {
        if (customFunc.length > 0)
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
const canEdit = (field) => {
    const perms = {
        1: ['klijent', 'ime_firme', 'PIB', 'MB', 'paket', 'kontakti', 'funkcionalnosti', 'custom_funkcionalnosti', 'konekcija', 'status_implementacije', 'datum_implementacije', 'godina_ugovora', 'ugovor', 'aneks', 'tip_fakturisanja', 'iznos_fakture'],
        2: ['ime_firme', 'paket', 'kontakti', 'funkcionalnosti', 'custom_funkcionalnosti', 'konekcija', 'status_implementacije', 'ugovor', 'aneks', 'tip_fakturisanja', 'iznos_fakture'],
        3: ['kontakti', 'konekcija', 'custom_funkcionalnosti'],
        4: []
    }
    return !perms[page.props.auth.user.role_id]?.includes(field)
}
const submit = () => {
    forma.functionalities = chosenFunctionalities.value
    forma.post(route('contract.update', {contract: props.contract.id}))
}
//Other companies

const setOtherCompanies = inject('setOtherCompanies')

onMounted(() => {
    setOtherCompanies(props.otherContracts)
})
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
    <ContactModal
        @addToContacts="addToContacts"
        :show="showContactModal"
        @closeModal="closeContactModal"
        :is-edit="isEditContact"
        :contact-to-edit="contactToEdit"
        @finishEdit="finishEdittingContact"
                  ref="contactModal"></ContactModal>
    <div class="pl-6">
        <form @submit.prevent="submit" class="">
            <div class="w-full ">
                <InputLabel for="name" value="Ime firme - grupacije/klijenta"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-2/4"
                    v-model="forma.klijent"
                    :disabled = "canEdit('klijent')"
                    :class="{'cursor-not-allowed' : canEdit('klijent')}"

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
                        :disabled = "canEdit('ime_firme')"
                        :class="{'cursor-not-allowed' : canEdit('ime_firme')}"
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
                        :disabled = "canEdit('PIB')"
                        :class="{'cursor-not-allowed' : canEdit('PIB')}"

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
                        :disabled = "canEdit('MB')"
                        :class="{'cursor-not-allowed' : canEdit('MB')}"

                    />
                    <InputError :message="forma.errors.MB" v-if="forma.errors.MB"/>

                </div>
                <div class="mt-4">
                    <InputLabel for="paket" value="Paket"/>

                    <select
                        id="paket"
                        class="form-select h-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                        aria-label="Default select example"
                        :disabled = "canEdit('paket')"
                        :class="{'cursor-not-allowed' : canEdit('paket')}"
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
                        <span class="text-3xl font-bold text-green-600 cursor-pointer"
                              v-show="!canEdit('kontakti')"
                              @click="openContactModal">&#43;</span>
                    </div>

                    <div class="bg-white shadow rounded max-w-[330px] min-h-[320px] max-h-[320px] overflow-y-auto ">
                        <div class="contact-item mb-1 flex flex-col hover:bg-gray-100 cursor-pointer p-4"
                             @dblclick="editContact(contact)"
                             v-for="contact in forma.contacts"
                             v-if="forma.contacts.length>0">
                            <span class="text-xl font-semibold mb-2">{{ contact.ime_prezime ?? contact.name }}</span>
                            <span class="text-sm text-gray-600" v-if="contact.email">{{ contact.email }}</span>
                            <span class="text-sm text-gray-600" v-if="contact.email2">{{ contact.email2 }}</span>
                            <span class="text-sm text-gray-600" v-if="contact.phone">{{ contact.phone }}</span>
                            <span class="text-sm text-gray-600" v-if="contact.phone2">{{ contact.phone2 }}</span>

                        </div>
                        <div v-else>
                            Trenutno nema dodatih kontakata, kliknite na plus da ih dodate
                        </div>

                    </div>
                </div>

                <div>
                    <div class="flex justify-between ">
                        <label class="block text-gray-700 text-sm font-bold ">Funkcionalnosti</label>
                        <span class="text-3xl font-bold text-green-600 cursor-pointer"
                              v-show="!canEdit('funkcionalnosti')"
                              @click="openFunctionalityModal">&#43;</span>
                    </div>

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
                                    :checked="isChecked(func)"
                                    :disabled="canEdit('funkcionalnosti')"
                                    class="form-check-input border-2 border-black" type="checkbox" value=""
                                    :id="func.funkcionalnost">
                            </div>
                            <div>

                                <input class="form-check-input border-2 border-black" type="checkbox" value=""
                                       @change="func.pivot.uradjeno = !func.pivot.uradjeno"
                                       :checked="func.pivot.uradjeno"
                                       :disabled="canEdit('funkcionalnosti')"

                                >


                            </div>

                        </div>
                    </div>
                    <InputError :message="forma.errors.functionalities" v-if="forma.errors.functionalities"/>
                </div>

                <div id="kontakt">
                    <InputLabel for="email" value="Nacin pristupa"/>
                    <div data-mdb-input-init class="form-outline min-w-[300px]">
                        <textarea v-model="forma.connection"
                                  :disabled="canEdit('konekcija')"
                                  :class="{'cursor-not-allowed' : canEdit('klijent')}"
                                  class="form-control border-bg-300 shadow bg-white" id="textAreaExample1"
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
                        aria-label="Default select example"
                        :disabled="canEdit('status_implementacije')"
                        :class="{'cursor-not-allowed' : canEdit('status_implementacije')}"
                    >
                        <option :value="status.id" v-for="status in statuses" :key="status.id"> {{ status.naziv }}
                        </option>
                    </select>

                    <InputError :message="forma.errors.implementation_status"
                                v-if="forma.errors.implementation_status"/>
                </div>
                <div v-if="forma.implementation_status === 3" class="mt-10 flex flex-col gap-2">
                    <div class="flex gap-2 items-center">
                        <label for="">Potpuno</label>
                        <input
                            :disabled="canEdit('status_implementacije')"
                            v-model="forma.tip_implementacije" type="radio" value="Potpuno">
                    </div>
                    <div class="flex gap-2 items-center">
                        <label for="">Čeka se neka funkcionalnost</label>
                        <input type="radio"
                               :disabled="canEdit('status_implementacije')"
                               v-model="forma.tip_implementacije" value="Čeka se neka funkcionalnost">
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
                        :disabled="canEdit('datum_implementacije')"
                        :class="{'cursor-not-allowed' : canEdit('datum_implementacije')}"
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
                        :disabled="canEdit('godina_ugovora')"
                        :class="{'cursor-not-allowed' : canEdit('godina_ugovora')}"
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
                        required
                        :disabled="canEdit('ugovor')"
                        :class="{'cursor-not-allowed' : canEdit('ugovor')}"
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
                        :disabled="canEdit('aneks')"
                        :class="{'cursor-not-allowed' : canEdit('aneks')}"
                    />
                    <InputError :message="forma.errors.aneks" v-if="forma.errors.aneks"/>

                </div>
                <div class="mt-4">
                    <InputLabel for="email" value="Tip fakturisanja"/>

                    <select
                        v-model="forma.tip_fakturisanja"
                        class="form-select h-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                        aria-label="Default select example"
                        :disabled="canEdit('tip_fakturisanja')"
                        :class="{'cursor-not-allowed' : canEdit('tip_fakturisanja')}"
                    >
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
                        :disabled="canEdit('iznos_fakture')"
                        :class="{'cursor-not-allowed' : canEdit('iznos_fakture')}"
                        required
                    />

                    <InputError :message="forma.errors.iznos_fakture" v-if="forma.errors.iznos_fakture"/>
                </div>

            </div>
            <div class="mt-10" v-show="$page.props.auth.user.role_id != 4">
                <PrimaryButton>Zavrsi popunjavanje</PrimaryButton>
            </div>


        </form>
    </div>

</template>

<style scoped>

</style>
