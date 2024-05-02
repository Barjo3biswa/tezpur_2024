<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * class responsible for session filter
 */

class ActiveSessionScope implements Scope
{
/**
 * Apply the scope to a given Eloquent query builder.
 *
 * @param  \Illuminate\Database\Eloquent\Builder  $builder
 * @param  \Illuminate\Database\Eloquent\Model  $model
 * @return void
 */
    public function apply(Builder $builder, Model $model)
    {
        $active_session = getActiveSession();
        // if not login then use session filter for user login, password reset
        // if student gurd is present then for application
        if(!auth()->check() || auth("student")->check()){
            $builder->where('session_id', $active_session->id);
        }
    }
}
