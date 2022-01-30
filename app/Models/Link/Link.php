<?php

namespace App\Models\Link;

use App\Scopes\LinkScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table='links';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    protected $fillable = [
        'link',
        'is_active'
    ];
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new LinkScope);
    }
    public function LinkTranslation()
    {
        return $this->hasMany(LinkTranslation::class);
    }
}
