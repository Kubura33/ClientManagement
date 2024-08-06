<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    class ContractRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         */
        public function authorize(): bool
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
         */
        public function rules(): array
        {

            return [
                'klijent' => ['string', 'max:255', 'nullable'],
                'ime_firme' => ['required', 'string', 'max:255'],
                'PIB' => ['string', 'max:9', 'required', Rule::unique('contracts')->ignore($this->contract->company->id)],
                'MB' => ['string', 'max:8', 'required', Rule::unique('contracts')->ignore($this->contract->company->id)],
                'package' => ['required'],
                'functionalities' => ['required', 'array'],
                'connection' => ['required', 'max:1000'],
                'contacts' => ['required', 'array'],
                'implementation_status' => ['required'],
                'date' => ['required_if:implementation_status, 3', 'date'],
                'tip_implementacije' => ['required_if:implementation_status, 3'],
                'ugovor' => ['required', 'string'],
                'aneks' => ['string'],
                'tip_fakturisanja' => ['required'],
                'iznos_fakture' => ['required']
            ];
        }

        public function messages()
        {
            return [
                'klijent.string' => 'Ime klijenta mora biti u string formatu',
                'klijent.max' => 'Maksimalna duzina karaktera je 255',
                'ime_firme.required' => 'Ime firme je obavezno!',
                'PIB.required' => 'PIB firme je obavezan',
                'PIB.size' => 'Duzina PIB-a ne sme imati vise od 9 cifara',
                'PIB.unique' => 'Uneti PIB vec postoji u bazi',
                'MB.required' => 'Maticni broj firme je obavezan!',
                'MB.size' => 'Duzina maticnog broja ne moze imati vise od 8 cifara',
                'MB.unique' => 'Uneti maticni broj vec postoji u bazi',
                'package.required' => 'Odabir paketa je obavezan',
                'functionalities.required' => 'Odabir funkcionalnosti je obavezan',
                'connection.required' => 'Unesite nacin konekcije',
                'contacts.required' => 'Kontakt firme je obavezan',
                'implementation_status.required' => 'Status implementacije je obavezan',
                'date.required_if' => 'Datum je obavezan',
                'date.date' => 'Datum mora biti u odgovarajucem formatu',
                'tip_implementacije.required_if' => 'Odaberite tip implementacije (Potpuno/Ceka se neka funkcionalnost)',
                'ugovor.required' => 'Unesite broj ugovora',
                'tip_fakturisanja' => 'Ovo polje je obavezno',
                'iznos_fakture' => 'Iznos fakture je obavezan!'
            ];
        }
    }
