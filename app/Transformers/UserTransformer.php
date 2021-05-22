<?php

declare(strict_types=1);

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer
 * @package App\Transformers
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * Transform for User
     * @param User $User
     * @return array
     */
    public function transform(User $User): array
    {
        return [
            'id' => $User->id,
            'name' => $User->name,
            'email' => $User->email,
        ];
    }
}
