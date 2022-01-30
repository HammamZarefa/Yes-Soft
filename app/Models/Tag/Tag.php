<?php

namespace App\Models\Tag;

use App\Scopes\TagScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table='tags';
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
        static::addGlobalScope(new TagScope);
    }
    public function TagTranslation()
    {
        return $this->hasMany(TagTranslation::class);
    }

    public function Posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
