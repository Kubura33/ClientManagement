<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    public function contract() : HasMany
    {
        return $this->hasMany(Contract::class, 'paket_id');
    }

    public function functionalities(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Functionalities::class, 'functionalities_package');
    }
}
