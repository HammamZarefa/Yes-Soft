<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class UserScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('user_translations', 'users.id', '=', 'user_translations.user_id')
            ->where('user_translations.local', '=', Config::get('app.locale'))
            ->select([
                'users.id','users.is_active','users.email','users.email_verified_at','users.password',
                'user_translations.name','user_translations.local'
            ]);
    }
}
