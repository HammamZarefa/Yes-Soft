<?php

namespace App\Models\General;

use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table='generals';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    protected $fillable = [
        'favicon',
        'logo',
        'phone',
        'email',
        'twitter',
        'facebook',
        'instagram',
        'linkedin',
        'gmaps',
        'is_active'
    ];
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new GeneralScope);
    }
    public function GeneralTranslation()
    {
        return $this->hasMany(GeneralTranslation::class);
    }
}
