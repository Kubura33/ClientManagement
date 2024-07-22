<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(Company::class);
    }

    public function status():BelongsTo
    {
        return $this->belongsTo(ImplementationStatus::class);
    }

    public function package() : BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
    protected function casts(): array
    {
        return [
            'funkcionalnosti' => 'array'
        ];
    }
}
