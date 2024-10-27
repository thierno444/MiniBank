<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    // Spécifiez le nom de la table si différent du nom par défaut
    protected $table = 'comptes';

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'user_id',
        'solde',
    ];

    // Définir les relations avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    

    // Optionnel : Ajouter des mutateurs ou des accessors si nécessaire
    // Exemple d'accessor pour le solde formaté
    public function getFormattedSoldeAttribute()
    {
        return number_format($this->solde, 2, ',', ' ') . ' FCFA';
    }

    public function updateSolde($montant)
    {
        $this->solde += $montant; // Met à jour le solde
        $this->save(); // Enregistre les changements
    } 
}
