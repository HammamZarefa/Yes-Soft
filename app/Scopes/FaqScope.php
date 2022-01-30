<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class FaqScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('faq_translations', 'faqs.id', '=', 'faq_translations.faq_id')
            ->where('faq_translations.local', '=', Config::get('app.locale'))
            ->select([
                'faqs.id','faqs.is_active',
                'faq_translations.question','faq_translations.answer',
                'faq_translations.local'
            ]);
    }
}
