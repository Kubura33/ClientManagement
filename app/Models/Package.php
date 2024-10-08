<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function contract() : HasMany
    {
        return $this->hasMany(Contract::class, 'paket_id');
    }

    public function functionalities(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Functionalities::class, 'functionality_package', 'package_id', 'functionality_id');
    }

    public function market() : BelongsTo
    {
        return $this->belongsTo(Market::class, 'trziste_id');
    }
}
