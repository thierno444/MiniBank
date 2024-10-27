<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $fillable = ['distributeur_id', 'montant_bonus'];

    // Relation avec le distributeur
    public function distributeur()
    {
        return $this->belongsTo(User::class, 'distributeur_id');
    }
}
