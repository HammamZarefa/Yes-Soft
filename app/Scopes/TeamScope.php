<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class TeamScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('team_translation', 'teams.id', '=', 'team_translation.team_id')
            ->where('team_translation.local', '=', Config::get('app.locale'))
            ->select([
                'teams.id','teams.is_active','teams.photo'
                ,'teams.facebook','teams.twitter','teams.instagram',
                'teams.linkedin','team_translation.name',
                'team_translation.position','team_translation.qoute',
                'team_translation.local']);
    }
}