<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscriptionHistorique extends Model
{
    protected $table = 'inscription_historique';

    public const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected $casts = ['notifie' => 'boolean'];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatutLabelAttribute()
    {
        return Inscription::STATUTS[$this->statut][0] ?? $this->statut;
    }

    public function getStatutColorAttribute()
    {
        return Inscription::STATUTS[$this->statut][1] ?? '#6c757d';
    }
}
