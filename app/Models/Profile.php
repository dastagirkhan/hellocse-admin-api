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

    protected $casts = [
        'statut' => 'string', // Change this based on your needs
    ];

    const STATUS_ACTIVE = 'actif';
    const STATUS_INACTIVE = 'inactif';
    const STATUS_PENDING = 'en attente';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_PENDING,
    ];

    // Mutators for name attributes
    public function setNomAttribute($value)
    {
        $this->attributes['nom'] = ucfirst($value);
    }

    public function setPrenomAttribute($value)
    {
        $this->attributes['prenom'] = ucfirst($value);
    }

    // Scope for active profiles
    public function scopeActive($query)
    {
        return $query->where('statut', 'actif');
    }

    // If a profile belongs to an administrator
    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }
}
