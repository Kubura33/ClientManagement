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
        'klijent_id',
        'ime',
        'PIB',
        'MB',
        'trziste'
    ];

    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class);
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
}
