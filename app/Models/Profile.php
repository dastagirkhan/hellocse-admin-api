<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'image',
        'statut',
    ];

    // Accessor for image attribute
    public function getImageAttribute($value)
    {
        return '/storage/' . $value;
    }

    // If a profile belongs to an administrator
    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }
}
