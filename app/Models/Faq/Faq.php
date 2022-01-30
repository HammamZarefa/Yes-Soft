<?php

namespace App\Models\Faq;

use App\Scopes\FaqScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table='faqs';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    protected $fillable = [
        'is_active'
    ];
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new FaqScope);
    }
    public function FaqTranslation()
    {
        return $this->hasMany(FaqTranslation::class);
    }
}
