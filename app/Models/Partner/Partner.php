<?php

namespace App\Models\Partner;

use App\Scopes\PartnerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table='partners';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    protected $fillable = [
        'cover',
        'link',
        'is_active'
    ];
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new PartnerScope);
    }
    public function PartnerTranslation()
    {
        return $this->hasMany(PartnerTranslation::class);
    }
}
