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
import VueMultiselect from "vue-multiselect/src/Multiselect.vue";
import CustomeLabel from "@/Components/CustomeLabel.vue";
import CustomInput from "@/Components/CustomInput.vue";

const props = defineProps({
    packages: Array,
    statuses: Array,
    fakturisanje: Array,
    contract: Object,
    existingFunctionalities: Array,
    otherContracts: Array,
    market: Object,
    datumiBesplatnihInstalacija: Array,
    workers: Array,
})
const page = usePage()
const chosenFunctionalities = ref(props.contract ? [...props.contract.functionalities] : [])
const forma = useForm({
    worker: props.contract?.zaduzen_za_implementaciju ? props.contract.zaduzen_za_implementaciju : "",
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
    document.body.style.overflow = 'auto'
}
const openContactModal = () => {
    isEditContact.value = false
    showContactModal.value = true
    contactModal.value.clear()
    document.body.style.overflow = 'hidden'
}
const addToContacts = (newContact) => {

    if (newContact.ime_prezime && (newContact.phone || newContact.email)) {
        const isEmailDuplicate = doesContactExist(newContact)

        if (isEmailDuplicate) {
            alert("Korisnik sa ovim email-om vec postoji");
        } else {
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
    if (canEdit("kontakti")) {
        return
    }
    contactToEdit.value = cTe
    showContactModal.value = true
    isEditContact.value = true


}
const finishEdittingContact = (changedContact) => {
    const editedContact = {...changedContact};
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
        chosenFunctionalities.value = props.contract ? [...props.contract.functionalities] : []
        selectedPackage.functionalities.forEach(functionality => {
            const exists = chosenFunctionalities.value.some(chosen => chosen.funkcionalnost === functionality.funkcionalnost);
            if(!exists){
                chosenFunctionalities.value.push({
                    funkcionalnost: functionality.funkcionalnost,
                    pivot: {
                        izabrano: true,
                        uradjeno: false
                    },
                    id: functionality.id,
                    trziste_id: functionality.trziste_id
                })
            }

        });
        forma.broj_preostalih_instalacija = selectedPackage.broj_besplatnih_instalacija_godisnje
        forma.iznos_fakture = selectedPackage.cena

    }
}
const notChosenFuncs = computed(() => {
    return props.existingFunctionalities.filter(existingFunc =>
        !chosenFunctionalities.value.some(chosenFunc => chosenFunc.funkcionalnost === existingFunc.funkcionalnost)
    );
});

const closeFunctionalityModal = () => {
    showFunctionalityModal.value = false
    if (funcModal.value) {
        funcModal.value.reset()
    }
    document.body.style.overflow = 'auto'
}
const openFunctionalityModal = () => {
    if (!forma.package) {
        alert("Izaberite paket prvo!");
    } else {
        showFunctionalityModal.value = true
        if (funcModal.value) {
            funcModal.value.reset()
        }
        document.body.style.overflow = 'hidden'
    }

}
const addFunctionality = (func) => {
    const index = chosenFunctionalities.value.findIndex(f => f.funkcionalnost === func.funkcionalnost);
    if (index === -1) {
        // Move functionality from existing to added
        chosenFunctionalities.value.push({
            funkcionalnost: func.funkcionalnost,
            selected: true,
            isDone: false,
            id: func.id,
            trziste_id: func.trziste_id,
        });
    }
}
const removeFunc = (func) => {
    const index = chosenFunctionalities.value.findIndex(f => f.funkcionalnost === func.funkcionalnost);
    if (index !== -1) {
        chosenFunctionalities.value.splice(index, 1); // Remove from added functionalities
    }
}
//CUSTOM FUNCTIONALITIES
const addCustomFunc = (customFunc) => {
    if (customFunc) {
        const newFunc = {
            funkcionalnost: customFunc.trim(),
            pivot: {
                uradjeno: false
            },
            selected: true,
            type: "custom"
        };
        const exists = forma.customFunctionalities.some(f =>
            f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()
        ) || props.existingFunctionalities.some(f =>
            f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()
        ) || chosenFunctionalities.value.some(f =>
            f.funkcionalnost.toLowerCase() === newFunc.funkcionalnost.toLowerCase()
        );
        if (!exists) {
            forma.customFunctionalities.push(newFunc);
        }
    }
}

const removeCustomFunc = (customFunc) => {
    const index = forma.customFunctionalities.findIndex(f => f.funkcionalnost === customFunc.funkcionalnost);
    if (index !== -1) {
        forma.customFunctionalities.splice(index, 1); // Remove from added functionalities
    }
}

//SORTING FUNCS
const sortedFuncs = computed(() => {
    // Combine chosen functionalities and custom functionalities
    const allFunctionalities = [
        ...chosenFunctionalities.value,
        ...forma.customFunctionalities
    ];

    return allFunctionalities.slice().sort((a, b) => {
        // First, sort by the "selected" status (true before false)
        if (a.pivot.izabrano !== b.pivot.izabrano) {
            return a.pivot.izabrano ? -1 : 1; // selected comes first
        }
        // If "selected" is the same, sort alphabetically by "funkcionalnost"
        return a.funkcionalnost.localeCompare(b.funkcionalnost);
    });
});

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
    document.body.style.overflow = 'hidden'
}
const closeInstallationDateModal = () => {
    showInstallationDateModal.value = false
    if (forma.newDates.length < 1) {
        InstallationDateModalRef.value.clear()
    }
    document.body.style.overflow = 'auto'
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
    return page.props.user.markets.some(m => m.id == props.market.id);
});
const customFun = computed(() => forma.customFunctionalities)

onMounted(() => {
    setOtherCompanies(props.otherContracts)
})

//Setting a worker
const selectedWorker = computed({
    get() {
        return props.workers.find(worker => worker.id === forma.worker) || null;
    },
    set(value) {
        forma.worker = value ? value.id : null;
    }
});
</script>

<template>
    <Transition name="fade">
        <AddFunctionalityModal
            @addFunc="addFunctionality"
            @remove-func="removeFunc"
            @add-custom-func="addCustomFunc"
            @remove-custom-func="removeCustomFunc"
            :show="showFunctionalityModal"
            @closeModal="closeFunctionalityModal"
            :form-custom-funcs="customFun"
            :not-chosen-functionalities="notChosenFuncs"
            ref="funcModal"
        ></AddFunctionalityModal>
    </Transition>


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
    <div class="min-h-screen pt-0 -mt-24 p-6  flex">
        <div class="container max-w-screen-lg mx-auto bg-[#f7f2dc] p-8 rounded-lg  transform scale-75">
            <!-- Form Title -->
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Evidencija Klijenta</h2>

            <!-- Form Grid -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Label & Select -->
                <div class="md:w-2/5">
                    <CustomeLabel>Zaduzen za implementaciju</CustomeLabel>
                    <VueMultiselect :options="workers"
                                    track-by="id"
                                    label="username"
                                    v-model="selectedWorker"
                                    placeholder="Izaberite jednog operatera"

                    />
                    <InputError :message="forma.errors.worker" v-if="forma.errors.worker"/>

                </div>

                <!-- Label & Input -->
                <div class="md:w-3/5">
                    <CustomeLabel for="grupacija">Grupacija firme</CustomeLabel>
                    <CustomInput
                        :disabled="canEdit('klijent')"
                        :class="{'cursor-not-allowed' : canEdit('klijent')}"
                        v-model="forma.klijent"
                        id="grupacija"/>
                    <InputError :message="forma.errors.klijent" v-if="forma.errors.klijent"/>

                </div>

                <!-- Three Labels & Inputs -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <CustomeLabel for="naziv_firme">Naziv firme</CustomeLabel>
                        <CustomInput
                            :disabled="canEdit('ime_firme')"
                            :class="{'cursor-not-allowed' : canEdit('ime_firme')}"
                            v-model="forma.ime_firme"
                            id="naziv_firme"/>
                        <InputError :message="forma.errors.ime_firme" v-if="forma.errors.ime_firme"/>

                    </div>
                    <div>
                        <CustomeLabel for="PIB">PIB</CustomeLabel>
                        <CustomInput
                            :disabled="canEdit('PIB')"
                            :class="{'cursor-not-allowed' : canEdit('PIB')}"
                            maxlength="9"
                            v-model="forma.PIB"
                            id="PIB"/>
                        <InputError :message="forma.errors.PIB" v-if="forma.errors.PIB"/>

                    </div>
                    <div>
                        <CustomeLabel for="mb">Maticni broj</CustomeLabel>
                        <CustomInput
                            :disabled="canEdit('MB')"
                            :class="{'cursor-not-allowed' : canEdit('MB')}"
                            maxlength="8"
                            v-model="forma.MB"
                            id="mb"/>
                        <InputError :message="forma.errors.MB" v-if="forma.errors.MB"/>

                    </div>
                    <div>
                        <CustomeLabel for="paket">Izaberite paket</CustomeLabel>

                        <select id="paket"
                                :disabled="canEdit('paket')"
                                :class="{'cursor-not-allowed' : canEdit('paket')}"
                                v-model="forma.package"
                                @change="setFuncs"
                                class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option
                                v-for="pkg in packages" :key="pkg.id" :value="pkg.id"
                            >{{ pkg.ime }}
                            </option>
                        </select>
                        <InputError :message="forma.errors.package" v-if="forma.errors.package"/>

                    </div>
                </div>

                <!-- Select & Functionality Div -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="relative bg-gray-50 p-4 rounded-md border border-gray-300 shadow-sm">
                        <button
                            v-show="!canEdit('funkcionalnosti')"
                            @click.prevent="openFunctionalityModal"
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 focus:outline-none text-3xl">
                            <i class="bi bi-plus"></i>
                        </button>
                        <CustomeLabel for="grupacija">Funkcionalnosti</CustomeLabel>
                        <div class="text-gray-600 h-52 overflow-y-auto px-2">
                            <div class="flex justify-between" v-if="forma.package">
                                <label for="">Naziv funkc.</label>
                                <label for="">Odradjeno</label>
                            </div>
                            <div v-else>Izaberite paket za prikaz funkcionalnosti</div>
                            <div class="flex items-center justify-between gap-4"
                                 v-for="func in sortedFuncs"
                                 :key="uuidv4()"
                            >
                                <div class="flex flex-row-reverse gap-2">
                                    <label class="form-check-label"
                                           :class="{'text-gray-400': !func.pivot.izabrano, 'text-black': func.pivot.izabrano}"
                                           :for="func.funkcionalnost">
                                        {{ func.funkcionalnost }}
                                    </label>
                                    <input
                                        :true-value="1"
                                        :false-value="0"
                                        v-model="func.pivot.izabrano"
                                        :checked="func.pivot.izabrano"
                                        class="form-check-input border-2 border-black" type="checkbox" value=""
                                        :id="func.funkcionalnost">
                                </div>
                                <div>
                                    <input class="form-check-input border-2 border-black" type="checkbox" value=""
                                           :true-value="1"
                                           :false-value="0"
                                           :disabled="!func.pivot.izabrano"
                                           v-model="func.pivot.uradjeno"
                                    >
                                </div>

                            </div>
                        </div>
                        <InputError :message="forma.errors.functionalities" v-if="forma.errors.functionalities"/>

                    </div>
                    <div class="relative bg-gray-50 p-4 rounded-md border border-gray-300 shadow-sm">
                        <button
                            v-if="!canEdit('kontakti')"
                            @click.prevent="openContactModal"
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 focus:outline-none text-3xl">
                            <i class="bi bi-plus"></i>
                        </button>

                        <CustomeLabel for="grupacija">Kontakti</CustomeLabel>

                        <div class="align-self overflow-y-auto">
                            <template v-for="(contact, index) in forma.contacts" :key="index">
                                <div
                                    @dblclick="editContact(contact)"
                                    class="flex justify-between items-center py-2 border-b border-gray-300 cursor-pointer hover:bg-gray-100">
                                    <div class="text-sm font-medium text-gray-700">{{ contact.ime_prezime }}</div>
                                    <div class="text-sm text-gray-600">{{ contact.phone }}</div>
                                    <div class="text-sm text-gray-600">{{ contact.email }}</div>
                                    <div class="text-sm text-gray-600">{{ contact.phone2 }}</div>
                                    <div class="text-sm text-gray-600">{{ contact.email2 }}</div>
                                </div>
                            </template>
                            <InputError :message="forma.errors.contacts" v-if="forma.errors.contacts"/>
                            <div v-if="forma.contacts.length === 0" class="text-gray-600">Trenutno nema kontakata</div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-300 shadow-sm">
                        <label for="connectionMethod" class="block text-sm font-medium text-gray-700 mb-2">Nacin
                            konekcije</label>
                        <textarea id="connectionMethod" rows="8"
                                  :disabled="canEdit('konekcija')"
                                  :class="{'cursor-not-allowed' : canEdit('konekcija')}"
                                  v-model="forma.connection"
                                  class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Kredencijali za konekciju"></textarea>
                        <InputError :message="forma.errors.connection" v-if="forma.errors.connection"/>
                    </div>
                </div>

                <!-- Three Labels & Inputs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Implementation Status Select -->
                    <div>
                        <CustomeLabel for="implementation_status">Status implementacije</CustomeLabel>
                        <select id="selectOption" v-model="forma.implementation_status"
                                :disabled="canEdit('status_implementacije')"
                                :class="{'cursor-not-allowed' : canEdit('status_implementacije')}"
                                class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option :value="status.id" v-for="status in statuses" :key="status.id">{{
                                    status.naziv
                                }}
                            </option>
                        </select>
                        <InputError :message="forma.errors.implementation_status"
                                    v-if="forma.errors.implementation_status"/>

                    </div>
                    <!-- Show ONLY if implementation status is 3 -->
                    <transition name="fade">
                        <div v-if="forma.implementation_status === 3"
                             class="col-span-3 md:col-span-1 flex flex-col space-y-2">
                            <div class="flex gap-2 items-center">
                                <input
                                    :disabled="canEdit('status_implementacije')"
                                    :class="{'cursor-not-allowed' : canEdit('status_implementacije')}"
                                    v-model="forma.tip_implementacije" type="radio" value="Potpuno" id="potpuno">
                                <label for="potpuno" class="text-gray-700">Potpuno</label>
                            </div>
                            <div class="flex gap-2 items-center">
                                <input v-model="forma.tip_implementacije" type="radio"
                                       :disabled="canEdit('status_implementacije')"
                                       :class="{'cursor-not-allowed' : canEdit('status_implementacije')}"
                                       value="Čeka se neka funkcionalnost"
                                       id="fja">
                                <label for="fja" class="text-gray-700">Čeka se neka funkcionalnost</label>
                            </div>
                            <!-- Date Picker -->
                            <div>
                                <label for="datum" class="block text-sm font-medium text-gray-700 mb-2">Datum
                                    implementacije</label>
                                <input
                                    :disabled="canEdit('datum_implementacije')"
                                    :class="{'cursor-not-allowed' : canEdit('datum_implementacije')}"
                                    type="date" id="datum" v-model="forma.datum"
                                    class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <!-- Error Handling -->
                            <InputError :message="forma.errors.tip_implementacije"
                                        v-if="forma.errors.tip_implementacije"/>
                        </div>
                    </transition>


                    <!-- Billing Method Select -->
                    <div class="flex items-center gap-4 ">
                        <div class="flex-grow-1">
                            <CustomeLabel for="nacin_fakturisanja">Nacin fakturisanja</CustomeLabel>
                            <select id="nacin_fakturisanja"
                                    :disabled="canEdit('tip_fakturisanja')"
                                    :class="{'cursor-not-allowed' : canEdit('tip_fakturisanja')}"
                                    v-model="forma.tip_fakturisanja"
                                    class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option v-for="tip in fakturisanje" :value="tip.id">{{ tip.naziv_fakturisanja }}</option>
                            </select>
                            <InputError :message="forma.errors.tip_fakturisanja" v-if="forma.errors.tip_fakturisanja"/>
                        </div>
                        <div>
                            <CustomeLabel for="nacin_fakturisanja">Pocetak fakturisanja</CustomeLabel>

                            <input

                                type="date" id="datum" v-model="forma.datum"
                                class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                    </div>
                </div>

                <!-- Three Labels & Inputs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <CustomeLabel for="godina_ugovora">Godina ugovora</CustomeLabel>
                        <CustomInput
                            :disabled="canEdit('godina_ugovora')"
                            :class="{'cursor-not-allowed' : canEdit('godina_ugovora')}"
                            v-model="forma.godina_ugovora"
                            id="godina_ugovora"/>
                        <InputError :message="forma.errors.godina_ugovora" v-if="forma.errors.godina_ugovora"/>

                    </div>
                    <div>
                        <CustomeLabel for="aneks">Aneks</CustomeLabel>
                        <CustomInput
                            :disabled="canEdit('aneks')"
                            :class="{'cursor-not-allowed' : canEdit('aneks')}"
                            v-model="forma.aneks"
                            id="aneks"/>
                        <InputError :message="forma.errors.aneks" v-if="forma.errors.aneks"/>

                    </div>
                    <div>
                        <CustomeLabel for="ugovor">Ugovor</CustomeLabel>
                        <CustomInput
                            :disabled="canEdit('ugovor')"
                            :class="{'cursor-not-allowed' : canEdit('ugovor')}"
                            v-model="forma.ugovor"
                            id="ugovor"/>
                        <InputError :message="forma.errors.ugovor" v-if="forma.errors.ugovor"/>

                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <div>
                        <CustomeLabel for="godina_ugovora">Iznos fakture</CustomeLabel>
                        <CustomInput
                            :disabled="canEdit('iznos_fakture')"
                            :class="{'cursor-not-allowed' : canEdit('iznos_fakture')}"
                            v-model="forma.iznos_fakture"
                            id="iznos_fakture"/>
                        <InputError :message="forma.errors.iznos_fakture" v-if="forma.errors.iznos_fakture"/>

                    </div>
                    <div>
                        <CustomeLabel for="bpig">Broj besplatnih instalacija godisnje</CustomeLabel>
                        <CustomInput
                            v-model="forma.broj_preostalih_instalacija"
                            id="bpig"/>
                        <InputError :message="forma.errors.broj_preostalih_instalacija"
                                    v-if="forma.errors.broj_preostalih_instalacija"/>

                    </div>
                    <div>
                        <div class=" ">
                            <button class="btn btn-secondary mt-4" @click.prevent="openInstallationDateModal"
                                    :disabled="!forma.broj_preostalih_instalacija">Pogledaj listu datuma besplatnih
                                instalacija
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <PrimaryButton>Snimi</PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>


.disabled-form .finish-button {
    display: none;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.7s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

</style>
