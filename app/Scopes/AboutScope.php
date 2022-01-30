<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class AboutScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('about_translations', 'abouts.id', '=', 'about_translations.about_id')
            ->where('about_translations.local', '=', Config::get('app.locale'))
            ->select([
                'abouts.id',
                'abouts.is_active',

                'about_translations.title',
                'about_translations.subject',
                'about_translations.desc',
                'about_translations.local'
            ]);
    }
}
