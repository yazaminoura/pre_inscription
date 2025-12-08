<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Administrateur extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [
        'id',
    ];

    public function formations()
    {
        return $this->hasMany(Formation::class, 'administrateur_id');
    }
}
