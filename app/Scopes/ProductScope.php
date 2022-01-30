<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class ProductScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('product_translations.local', '=', Config::get('app.locale'))
            ->select([
                'products.id',
                'products.is_active',
                'products.cover',
                'products.link',

                'product_translations.name',
                'product_translations.local'
            ]);
    }
}
