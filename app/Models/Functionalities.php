<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Functionalities extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function package(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'functionality_package', 'functionality_id', 'package_id');
    }

    public function contract(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class, 'contract_functionality', 'funkcionalnost_id', 'ugovor_id');
    }

    public function market() : BelongsTo
    {
        return $this->belongsTo(Market::class, 'trziste_id');
    }
}
