<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class PortfolioScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('portfolio_translations', 'portfolios.id', '=', 'portfolio_translations.portfolio_id')
            ->where('portfolio_translations.local', '=', Config::get('app.locale'))
            ->select([
                'portfolios.id',
                'portfolios.pcategory_id',
                'portfolios.is_active',
                'portfolios.slug',
                'portfolios.cover',
                'portfolios.mobileImage',
                'portfolios.link',
                'portfolios.date',

                'portfolio_translations.name',
                'portfolio_translations.client',
                'portfolio_translations.desc',
                'portfolio_translations.local'
            ]);
    }
}
