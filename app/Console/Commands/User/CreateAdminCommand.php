<?php

namespace App\Console\Commands\User;

use App\Entities\User;
use App\Repositories\User\UserRepository;
use Illuminate\Console\Command;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add user with admin rights';

    protected $repo;

    /**
     * CreateAdminCommand constructor.
     * @param UserRepository $repo
     */
    public function __construct(UserRepository $repo)
    {
        parent::__construct();
        $this->repo = $repo;
    }

    /**
     * @return bool
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     */
    public function handle()
    {
        $user = new User();
        $user->setName('Admin');
        $user->setEmail($this->argument('email'));
        $user->setPassword(\Hash::make($this->argument('password')));
        $user->setRole(User::ROLE_ADMIN)
            ->setStatus(User::STATUS_CONFIRM)
            ->setRememberToken(str_random(10));
        $find = $this->repo->findByEmail($this->argument('email'));
        if ($find) {
            $this->error('Email already exist');
            return false;
        }
        $this->repo->persist($user);
        $this->repo->flush();
        $this->repo->clear();
        $this->info('Success ');
        return true;
    }
}
