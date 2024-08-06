<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function companies() : HasMany
    {
        return $this->hasMany(Company::class, 'trziste_id');
    }

    public function packages() : BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'market_package', 'trziste_id', 'paket_id');
    }

}
