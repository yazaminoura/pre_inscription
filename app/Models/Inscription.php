<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['candidate_info'];
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
