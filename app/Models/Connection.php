<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Connection extends Model
{
    use HasFactory;
    protected $fillable = [
        'firma_id',
        'konekcija'
    ];

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
