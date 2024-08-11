<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'trziste_id',
        'klijent_id',
        'ime',
        'PIB',
        'MB',
        'trziste'
    ];

    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class, 'klijent_id');
    }

    public function connection(): HasOne
    {
        return $this->hasOne(Connection::class, 'firma_id');
    }

    public function contacts() : HasMany
    {
        return $this->hasMany(Contact::class, 'firma_id');
    }

    public function contract() : HasOne
    {
        return $this->hasOne(Contract::class, 'firma_id');
    }

    public function market() : BelongsTo
    {
        return $this->belongsTo(Market::class, 'trziste_id');
    }


    //Functions

    public function updateCompany(array $data)
    {
        // Update attributes only if they exist in the provided data
        if (array_key_exists('ime_firme', $data)) {
            $this->ime = $data['ime_firme'];
        }
        if (array_key_exists('PIB', $data)) {
            $this->PIB = $data['PIB'];
        }
        if (array_key_exists('MB', $data)) {
            $this->MB = $data['MB'];
        }
        $this->save();
    }
}
