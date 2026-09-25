<?php
namespace App\Models;

use App\Models\Concerns\Traduisible;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory, Traduisible;

    public const CHAMPS_TRADUISIBLES = ['titre', 'duree', 'description', 'conditions_acces', 'modalites_selection', 'debouches'];

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }
}

