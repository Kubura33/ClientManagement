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

    public function functionalities() : HasMany
    {
        return $this->hasMany(Functionalities::class, 'trziste_id');
    }

    public function packages() : HasMany
    {
        return $this->hasMany(Package::class, 'trziste_id');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_market', 'market_id', 'user_id');
    }
}
