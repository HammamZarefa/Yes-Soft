<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class LinkScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('link_translations', 'links.id', '=', 'link_translations.link_id')
            ->where('link_translations.local', '=', Config::get('app.locale'))
            ->select([
                'links.id',
                'links.is_active',
                'links.link',
                
                'link_translations.name',
                'link_translations.local'
            ]);
    }
}
