<?php


namespace App\Traits;
use App\Models\User;


trait AuthTrait
{
    protected function generateToken(User $user)
    {
        $tokenResult = $user->createToken('API Token', ['*'], now()->addDays(1)); // Expires in 1 day

        return $tokenResult;

    }
}
