<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function diplomes()
    {
        return $this->hasMany(Diplome::class, 'candidat_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function attestations()
    {
        return $this->hasMany(Attestation::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }
    
    public function formations()
    {
        return $this->hasManyThrough(Formation::class, Inscription::class, 'candidat_id', 'id', 'id', 'formation_id');
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
}
