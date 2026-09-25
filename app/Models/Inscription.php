<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['candidate_info'];
    protected $casts = ['statut_at' => 'datetime'];

    // statut => [libellé, couleur du badge]
    public const STATUTS = [
        'en_attente' => ['En attente', '#6c757d'],
        'en_cours' => ["En cours d'étude", '#1a73e8'],
        'incomplet' => ['Dossier incomplet', '#7c3aed'],
        'liste_attente' => ["Liste d'attente", '#f0ad4e'],
        'acceptee' => ['Acceptée', '#2e7d32'],
        'refusee' => ['Refusée', '#c62828'],
    ];

    protected static function booted()
    {
        static::created(function (Inscription $inscription) {
            if (!$inscription->reference) {
                $inscription->reference = 'PI' . now()->format('Y') . '-' . str_pad($inscription->id, 5, '0', STR_PAD_LEFT);
                $inscription->saveQuietly();
            }
            // Première ligne de l'historique : le dépôt du dossier
            $inscription->historique()->create(['statut' => 'en_attente', 'created_at' => $inscription->created_at]);
            if (($inscription->statut ?? 'en_attente') !== 'en_attente') {
                $inscription->historique()->create(['statut' => $inscription->statut, 'motif' => $inscription->motif, 'created_at' => $inscription->statut_at ?? now()]);
            }
        });
    }

    /**
     * Change le statut et l'inscrit dans l'historique. Renvoie la ligne d'historique créée.
     */
    public function changerStatut(string $statut, ?string $motif, ?User $par = null): InscriptionHistorique
    {
        $this->update(['statut' => $statut, 'motif' => $motif, 'statut_at' => now()]);

        return $this->historique()->create([
            'statut' => $statut,
            'motif' => $motif,
            'user_id' => $par?->id,
            'created_at' => now(),
        ]);
    }

    public function historique()
    {
        return $this->hasMany(InscriptionHistorique::class)->orderBy('created_at')->orderBy('id');
    }

    public function getStatutLabelAttribute()
    {
        return self::STATUTS[$this->statut][0] ?? $this->statut;
    }

    // Pastille de statut lisible d'un coup d'œil : [fond, texte] (ex. « En attente » en ambre)
    public const PASTILLES = [
        'en_attente' => ['#FEF3C7', '#92400E'],
        'en_cours' => ['#DBEAFE', '#1E40AF'],
        'incomplet' => ['#EDE9FE', '#5B21B6'],
        'liste_attente' => ['#FFEDD5', '#9A3412'],
        'acceptee' => ['#DCFCE7', '#166534'],
        'refusee' => ['#FEE2E2', '#991B1B'],
    ];

    /** Style CSS en ligne de la pastille de statut. */
    public function getStatutPastilleAttribute(): string
    {
        [$fond, $texte] = self::PASTILLES[$this->statut] ?? ['#F1F5F9', '#334155'];

        return "background: $fond; color: $texte;";
    }

    public function getStatutColorAttribute()
    {
        return self::STATUTS[$this->statut][1] ?? '#6c757d';
    }

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'candidat_id');
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }
   


public function getCandidateInfoAttribute()
{
    if ($this->candidate) {
        return [
            'name' => trim($this->candidate->nom . ' ' . $this->candidate->prenom),
            'email' => $this->candidate->email,
            'photo' => $this->candidate->photo
        ];
    }
    
    return [
        'name' => $this->candidate_email ?? 'N/A',
        'email' => $this->candidate_email ?? 'N/A',
        'photo' => null
    ];
}
}
