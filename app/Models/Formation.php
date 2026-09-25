<?php
namespace App\Models;

use App\Models\Concerns\Traduisible;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory, Traduisible;

    public const CHAMPS_TRADUISIBLES = ['titre', 'duree', 'description', 'conditions_acces', 'modalites_selection', 'debouches'];

    // Niveau de recrutement => [libellé admin, rang]. Le rang décide des diplômes demandés au candidat.
    public const NIVEAUX = [
        'bac' => ['Après le bac (1re année : Licence 3 ans, DUT, BTS…)', 0],
        'bac2' => ['Bac+2 (ex. Licence professionnelle en 1 an, 3e année)', 2],
        'bac3' => ['Bac+3 (Master, cycle ingénieur sur titre…)', 3],
        'bac5' => ['Bac+5 (Doctorat, Master spécialisé…)', 5],
    ];

    /** Rang du niveau d'accès : 0 = après le bac, 2 = Bac+2, 3 = Bac+3, 5 = Bac+5. */
    public function rangAcces(): int
    {
        return self::NIVEAUX[$this->niveau_acces ?? 'bac'][1] ?? 0;
    }

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

