<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class PageScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('page_translations', 'pages.id', '=', 'page_translations.page_id')
            ->where('page_translations.local', '=', Config::get('app.locale'))
            ->select([
                'pages.id',
                'pages.is_active',
                'pages.slug',
                
                'page_translations.title',
                'page_translations.text',
                'page_translations.local'
            ]);
    }
}
