<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {computed, inject, onMounted, ref} from "vue";
import {v4 as uuidv4} from 'uuid';
import InputError from "@/Components/InputError.vue";
import ContactModal from "@/Components/ContactModal.vue";
import AddFunctionalityModal from "@/Components/AddFunctionalityModal.vue";
import InstallationDateModal from "@/Components/InstallationDateModal.vue";

const props = defineProps({
    packages: Array,
    statuses: Array,
    fakturisanje: Array,
    contract: Object,
    existingFunctionalities: Array,
    otherContracts: Array,
    market: Object,
    datumiBesplatnihInstalacija: Array,
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
    tip_implementacije: props.contract.status_implementiranja ? props.contract.status_implementiranja : null,
    date: props.contract ? props.contract.datum_implementacije : null,
    ugovor: props.contract ? props.contract.broj_ugovora : "",
    godina_ugovora: props.contract ? props.contract.godina_ugovora : "",
    aneks: props.contract ? props.contract.broj_aneksa : "",
    tip_fakturisanja: props.contract ? props.contract.fakturisanje_id : null,
    iznos_fakture: props.contract ? props.contract.iznos_fakture : null,
    broj_preostalih_instalacija: props.contract ? props.contract.broj_preostalih_instalacija : null,
    broj_besplatnih_instalacija_godisnje: props.contract ? props.contract.broj_besplatnih_instalacija_godisnje : null,
    datumi_besplatnih_instalacija: props.datumiBesplatnihInstalacija ? props.datumiBesplatnihInstalacija : [],
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
    if(canEdit("kontakti")){
        return
    }
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
const isChecked = (func) => {
    return chosenFunctionalities.value.some(cf => cf.funkcionalnost.toLowerCase() === func.funkcionalnost.toLowerCase())
}
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
        2: ['ime_firme', 'paket', 'kontakti', 'funkcionalnosti', 'custom_funkcionalnosti', 'konekcija', 'status_implementacije', 'ugovor', 'aneks', 'tip_fakturisanja', 'iznos_fakture', 'datum_instalacije'],
        3: ['kontakti', 'konekcija', 'custom_funkcionalnosti', 'datum_instalacije'],
        4: []
    }
    const userHasMarket = hasMarket.value; // Access computed value
    if (page.props.auth.user.role_id === 1) {
        return false; // Role 1 has full access
    } else {
        return !userHasMarket || !perms[page.props.auth.user.role_id]?.includes(field);
    }

}

//Datumi besplatnih instalacija


const showInstallationDateModal = ref(false)
const InstallationDateModalRef = ref(null)
const openInstallationDateModal = () => {
    showInstallationDateModal.value = true
}
const closeInstallationDateModal = () => {
    showInstallationDateModal.value = false
    if (forma.newDates.length < 1) {
        InstallationDateModalRef.value.clear()
    }
}
const addDates = (dates) => {
    forma.newDates = dates
    closeInstallationDateModal()
}
const submit = () => {
    forma.functionalities = chosenFunctionalities.value
    forma.post(route('contract.update', {contract: props.contract.id}), {

        onSuccess: () => {
            router.reload({
                only: ['contract']
            })
        },
        preserveState: false

    })
}
//Other companies

const setOtherCompanies = inject('setOtherCompanies')
const hasMarket = computed(() => {
    if (page.props.auth.user.role_id === 1) {
        return true; // Admin role always has access
    }
    return page.props.user.markets.some( m => m.id == props.market.id);
});

onMounted(() => {
    setOtherCompanies(props.otherContracts)
})
</script>

<template>
    <InstallationDateModal
        :show="showInstallationDateModal"
        :current-dates="forma.datumi_besplatnih_instalacija"
        :broj_instalacija="forma.broj_preostalih_instalacija"
        @closeModal="closeInstallationDateModal"
        @saveDates="addDates"
        :can-edit="canEdit('datum_instalacije')"
        ref="InstallationDateModalRef"
    ></InstallationDateModal>
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
        :contact-to-edit="contactToEdit"
        :is-edit="isEditContact"
        @finishEdit="finishEdittingContact"
        ref="contactModal"></ContactModal>
    <div class="pl-6" >
        <form @submit.prevent="submit" :class="{'disabled-form' : !hasMarket}">
            <div class="w-full ">
                <InputLabel for="name" value="Ime firme - grupacije/klijenta"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-2/4"
                    v-model="forma.klijent"
                    :disabled="canEdit('klijent')"
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
                        :disabled="canEdit('ime_firme')"
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
                        :disabled="canEdit('PIB')"
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
                        :disabled="canEdit('MB')"
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
                        :disabled="canEdit('paket')"
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
                        <label for="potpuno">Potpuno</label>
                        <input
                            id="potpuno"
                            :disabled="canEdit('status_implementacije')"
                            v-model="forma.tip_implementacije" type="radio" value="Potpuno">
                    </div>
                    <div class="flex gap-2 items-center">
                        <label for="cekanje">Čeka se neka funkcionalnost</label>
                        <input type="radio"
                               id="cekanje"
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
            <div id="instalacije" class="flex flex-row gap-4">
                <div class="mt-4">
                    <InputLabel for="broj_besplatnih_instalacija_godisnje" value="Broj besplatnih instalacija godisnje"/>

                    <TextInput
                        id="broj_besplatnih_instalacija_godisnje"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.broj_besplatnih_instalacija_godisnje"

                    />
                    <InputError :message="forma.errors.ugovor" v-if="forma.errors.broj_besplatnih_instalacija_godisnje"/>

                </div>
                <div class="mt-4">
                    <InputLabel for="bpig" value="Broj PREOSTALIH besplatnih instalacija godisnje"/>

                    <TextInput
                        id="bpig"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="forma.broj_preostalih_instalacija"
                        disabled
                    />
                    <InputError :message="forma.errors.ugovor" v-if="forma.errors.broj_preostalih_instalacija"/>

                </div>
                <div class="mt-5 flex justify-center items-center">
                    <button class="btn btn-secondary" @click.prevent="openInstallationDateModal">Lista datuma besplatnih
                        instalacija
                    </button>
                </div>
            </div>
            <div class="mt-10 finish-button" v-show="$page.props.auth.user.role_id != 4">
                <PrimaryButton>Zavrsi popunjavanje</PrimaryButton>
            </div>


        </form>
    </div>

</template>

<style scoped>



.disabled-form .finish-button {
    display: none;
}

</style>
