<?php

namespace App\Repositories\User;

use App\Interfaces\UserInterface;
use App\Repositories\BaseRepository;


class UserRepository extends BaseRepository implements UserInterface
{
    public function findByEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findByToken($token)
    {
        return $this->findOneBy(['rememberToken' => $token]);
    }
}
