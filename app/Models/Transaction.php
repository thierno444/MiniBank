<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'emettteur_id',
        'receveur_id',
        'distributeur_id',
        'agent_id',
        'type',
        'mountant',
        'frais',
        'annule',
        'statut',
        'expires_at',
    ];

    // Relations
    public function emetteur()
    {
        return $this->belongsTo(User::class, 'emettteur_id');
    }


    public function receveur()
    {
        return $this->belongsTo(User::class, 'receveur_id');
    }
    

    public function distributeur()
    {
        return $this->belongsTo(User::class, 'distributeur_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
