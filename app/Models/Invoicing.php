<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoicing extends Model
{
    use HasFactory;

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'fakturisanje_id');
    }
}
