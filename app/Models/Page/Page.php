<?php

namespace App\Models\Page;

use App\Scopes\PageScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table='pages';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    protected $fillable = [
        'slug',
        'is_active'
    ];
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new PageScope);
    }
    public function PageTranslation()
    {
        return $this->hasMany(PageTranslation::class);
    }
}
