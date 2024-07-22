<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['ime'];

    public function companies() : HasMany
    {
        return $this->hasMany(Company::class, 'klijent_id');
    }
}
