<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

/**
 * Class UsersTransformer
 * @package App\Transformers
 */
class UsersTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'        => (int) $user->id,
            'name'      => ucfirst($user->name),
            'email'     => $user->email
        ];
    }
}