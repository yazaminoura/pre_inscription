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

    // Niveaux possibles pour la voie alternative (diplôme plus bas compensé par de l'expérience)
    public const NIVEAUX_ALTERNATIFS = ['bac' => 'Bac', 'bac2' => 'Bac+2', 'bac3' => 'Bac+3'];

    /** Rang du niveau d'accès : 0 = après le bac, 2 = Bac+2, 3 = Bac+3, 5 = Bac+5. */
    public function rangAcces(): int
    {
        return self::NIVEAUX[$this->niveau_acces ?? 'bac'][1] ?? 0;
    }

    /** Rang de la voie alternative, ou null s'il n'y en a pas. */
    public function rangAlternatif(): ?int
    {
        return $this->alternatif_niveau && $this->alternatif_experience ? self::NIVEAUX[$this->alternatif_niveau][1] : null;
    }

    /**
     * Le candidat est-il recevable ? Son plus haut diplôme atteint le niveau d'accès,
     * ou la voie alternative (diplôme plus bas + années d'expérience).
     * Le formulaire ne recueille que jusqu'au Bac+3 : pour un accès Bac+5, le Bac+3 est le maximum vérifiable.
     */
    public function estRecevable(int $plusHautDiplome, ?int $anneesExperience): bool
    {
        if ($plusHautDiplome >= min($this->rangAcces(), 3)) {
            return true;
        }
        $alternatif = $this->rangAlternatif();

        return $alternatif !== null && $plusHautDiplome >= $alternatif && (int) $anneesExperience >= $this->alternatif_experience;
    }

    /** Condition d'accès lisible, dans la langue courante. Ex. : « Bac+3 minimum, ou Bac+2 avec 3 ans d'expérience ». */
    public function conditionAcces(): string
    {
        $niveau = ['bac' => __('Baccalauréat'), 'bac2' => 'Bac+2', 'bac3' => 'Bac+3', 'bac5' => 'Bac+5'];
        $texte = $this->rangAcces() === 0
            ? __('Baccalauréat')
            : __(':niveau minimum', ['niveau' => $niveau[$this->niveau_acces]]);
        if ($this->rangAlternatif() !== null) {
            $texte .= ', ' . trans_choice('ou :niveau avec :n an d\'expérience|ou :niveau avec :n ans d\'expérience', $this->alternatif_experience, [
                'niveau' => $niveau[$this->alternatif_niveau], 'n' => $this->alternatif_experience,
            ]);
        }

        return $texte;
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

