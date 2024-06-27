<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Merchant extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email'];

    public function ingredients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ingredient::class, 'merchant_id');
    }
}
