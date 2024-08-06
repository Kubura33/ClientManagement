<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'fakturisanje_id',
        'firma_id',
        'status_id',
        'paket_id',
        'funkcionalnosti',
        'broj_aneksa',
        'broj_ugovora',
        'godina_ugovora',
        'datum_implementacije'

    ];

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
    protected function casts(): array
    {
        return [
            'funkcionalnosti' => 'array'
        ];
    }

}
