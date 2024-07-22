<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImplementationStatus extends Model
{
    use HasFactory;

    public function contract() : HasMany
    {
        return $this->hasMany(Contract::class, 'status_id');
    }
}
