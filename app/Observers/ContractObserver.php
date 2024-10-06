<?php

    namespace App\Observers;

    use App\Models\Contract;
    use App\Models\Implementation;
    use Carbon\Carbon;
    use DateTime;
    use Illuminate\Support\Facades\DB;

    class ContractObserver
    {

        /**
         * Handle the Contract "created" event.
         */
        public function created(Contract $contract): void
        {
            $this->setBrojInstalacija($contract);
        }

        /**
         * Handle the Contract "updated" event.
         */
        public function updated(Contract $contract): void
        {
            $this->setBrojInstalacija($contract);
//                if($contract->status_id == 3){
//                        Implementation::create([
//                            'ugovor_id' => $contract->id,
//                            'zaduzen_za_implementaciju' => auth()->user()->username,
//                            'implementirao' => $contract->status_implementiranja,
//                        ]);
//                }else{
//                    Implementation::create([
//                        'ugovor_id' => $contract->id,
//                        'zaduzen_za_implementaciju' => auth()->user()->username,
//                        'implementirao' => $contract->status->naziv,
//                    ]);
//                }

        }

        /**
         * Handle the Contract "deleted" event.
         */
        public function deleted(Contract $contract): void
        {
            //
        }

        /**
         * Handle the Contract "restored" event.
         */
        public function restored(Contract $contract): void
        {
            //
        }

        /**
         * Handle the Contract "force deleted" event.
         */
        public function forceDeleted(Contract $contract): void
        {
            //
        }

        /**
         * @param Contract $contract
         * @return void
         */
        public function setBrojInstalacija(Contract $contract): void
        {
            $currentYear = Carbon::now()->year;
            $dates = DB::table('installation_log')
                ->where("ugovor_id", $contract->id)
                ->get(['datum_instalacije']);
            $count = 0;
            foreach ($dates as $item) {
                $date = DateTime::createFromFormat('d.m.Y', $item->datum_instalacije);

                if ($date && $date->format('Y') == $currentYear) {

                    $count += 1;
                }
            }
            $contract->broj_preostalih_instalacija = $contract->broj_besplatnih_instalacija_godisnje - $count;
            $contract->saveQuietly();
        }
    }
