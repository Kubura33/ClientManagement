<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = [
        'firma_id',
        'kompanija',
        'ime_prezime',
        'telefon',
        'email'
    ];

    protected function casts(): array
    {
        return [
            'ime_prezime' => 'encrypted',
            'telefon' => 'encrypted',
            'telefon_2' => 'encrypted'
        ];
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
