<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class PostScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('post_translations', 'posts.id', '=', 'post_translations.post_id')
            ->where('post_translations.local', '=', Config::get('app.locale'))
            // ->leftjoin('categories', 'posts.category_id', '=', 'categories.id')
            // ->leftjoin('category_translations', 'categories.id' , '=' , 'category_translations.category_id')
            // ->where('category_translations.local', '=' ,  Config::get('app.locale'))
            ->select([
                'posts.id','posts.category_id','posts.author_id','posts.is_active',
                'posts.slug','posts.cover','posts.views','posts.status',
                'post_translations.title','post_translations.body',
                'post_translations.keyword','post_translations.meta_desc',
                'post_translations.local'
            ]);
    }
}
