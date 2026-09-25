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
