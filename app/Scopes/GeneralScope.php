<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class GeneralScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('general_translations', 'generals.id', '=', 'general_translations.general_id')
            ->where('general_translations.local', '=', Config::get('app.locale'))
            ->select([
                'generals.id',
                'generals.is_active',
                'generals.favicon',
                'generals.logo',
                'generals.phone',
                'generals.email',
                'generals.twitter',
                'generals.facebook',
                'generals.instagram',
                'generals.linkedin',
                'generals.gmaps',

                'general_translations.title',
                'general_translations.address1',
                'general_translations.address2',
                'general_translations.footer',
                'general_translations.tawkto',
                'general_translations.disqus',
                'general_translations.gverification',
                'general_translations.sharethis',
                'general_translations.keyword',
                'general_translations.meta_desc',
                'general_translations.local'
            ]);
    }
}
