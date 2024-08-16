<?php

namespace App\Models;

use App\Observers\ContractObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function invoicing() : BelongsTo
    {
        return $this->belongsTo(Invoicing::class);
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class, 'firma_id');
    }

    public function status():BelongsTo
    {
        return $this->belongsTo(ImplementationStatus::class, 'status_id');
    }

    public function package() : BelongsTo
    {
        return $this->belongsTo(Package::class, 'paket_id');
    }

    public function implementation() : HasMany
    {
        return $this->hasMany(Implementation::class, 'ugovor_id');
    }

    public function functionalities(): BelongsToMany
    {
        return $this->belongsToMany(Functionalities::class, 'contract_functionality', 'ugovor_id', 'funkcionalnost_id')
            ->withPivot('uradjeno');
    }

}
