<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class PartnerScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('partner_translations', 'partners.id', '=', 'partner_translations.partner_id')
            ->where('partner_translations.local', '=', Config::get('app.locale'))
            ->select([
                'partners.id',
                'partners.is_active',
                'partners.cover',
                'partners.link',

                'partner_translations.name',
                'partner_translations.local'
            ]);
    }
}
