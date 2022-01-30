<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class TestimonialScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('testimonial_translations', 'testimonials.id', '=', 'testimonial_translations.testimonial_id')
            ->where('testimonial_translations.local', '=', Config::get('app.locale'))
            ->select([
                'testimonials.id',
                'testimonials.is_active',
                'testimonials.photo',
                'testimonials.status',

                'testimonial_translations.name',
                'testimonial_translations.profession',
                'testimonial_translations.desc',
                'testimonial_translations.local'
            ]);
    }
}
