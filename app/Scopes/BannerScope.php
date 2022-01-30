<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class BannerScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('banner_translation', 'banners.id', '=', 'banner_translation.banner_id')
            ->where('banner_translation.local', '=', Config::get('app.locale'))
            ->select([
                'banners.id','banners.is_active','banners.cover','banners.link',
                'banner_translation.title','banner_translation.desc','banner_translation.local']);
    }
}