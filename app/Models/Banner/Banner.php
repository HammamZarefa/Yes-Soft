<?php

namespace App\Models\Banner;

use App\Scopes\BannerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table='banners';
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
        static::addGlobalScope(new BannerScope);
    }
    public function BannerTranslation()
    {
        return $this->hasMany(BannerTranslation::class);
    }
}
