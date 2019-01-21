<?php

use Illuminate\Database\Seeder;
use App\Entities\User;

class UsersTableSeeder extends Seeder
{
    /**
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        array_map(function () use ($faker) {
            $active = $faker->boolean;
            $user = new User();
            $user->setName($faker->name);
            $user->setEmail($faker->unique()->safeEmail);
            $user->setPassword('$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'); // secret
            $user->setRole(User::ROLE_USER)
                ->setStatus( $active ? User::STATUS_CONFIRM : User::STATUS_NO_CONFIRM)
                ->setRememberToken(str_random(10));
            $active ? null : $user->generateVerifyCode();
            EntityManager::persist($user);
        }, range(1, 10));
        EntityManager::flush();
        EntityManager::clear();
    }
}
