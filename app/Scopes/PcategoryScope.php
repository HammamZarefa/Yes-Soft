<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class PcategoryScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('pcategory_translations', 'pcategories.id', '=', 'pcategory_translations.pcategory_id')
            ->where('pcategory_translations.local', '=', Config::get('app.locale'))
            ->select([
                'pcategories.id',
                'pcategories.is_active',

                'pcategory_translations.name',
                'pcategory_translations.local'
            ]);
    }
}
