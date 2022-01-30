<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class TagScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('tag_translations', 'tags.id', '=', 'tag_translations.tag_id')
            ->where('tag_translations.local', '=', Config::get('app.locale'))
            ->select([
                'tags.id','tags.is_active','tags.slug',
                'tag_translations.name','tag_translations.keyword',
                'tag_translations.meta_desc','tag_translations.local'
            ]);
    }
}
