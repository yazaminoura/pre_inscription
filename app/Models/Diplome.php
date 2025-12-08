<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diplome extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'candidat_id');
    }
}

