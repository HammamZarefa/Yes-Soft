<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class ServiceScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('service_translations', 'services.id', '=', 'service_translations.service_id')
            ->where('service_translations.local', '=', Config::get('app.locale'))
            ->select([
                'services.id',
                'services.is_active',
                'services.slug',
                'services.icon',

                'service_translations.title',
                'service_translations.quote',
                'service_translations.desc',
                'service_translations.local'
            ]);
    }
}
