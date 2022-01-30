<?php

namespace App\Models\About;

use App\Scopes\AboutScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;


class About extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table='abouts';
    protected $hidden = [
        'created_at', 'updated_at','id'
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
        static::addGlobalScope(new AboutScope);
    }
    public function scopeAbout(Builder $builder)
    {
        return $builder->join('about_translations', 'abouts.id', '=', 'about_translations.about_id')
            ->where('about_translations.local', '=', Config::get('app.locale'))
            ->select([
                'abouts.id',

                'about_translations.title',
                'about_translations.subject',
                'about_translations.local'
            ])->get();
    }
    
    public function AboutTranslation()
    {
        return $this->hasMany(AboutTranslation::class);
    }
}
