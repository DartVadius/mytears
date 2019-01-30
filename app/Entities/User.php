<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 07.01.19
 * Time: 12:37
 */

namespace App\Entities;

use Doctrine\ORM\Mapping AS ORM;
use DateTime;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
//use Illuminate\Validation\Rule;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use LaravelDoctrine\ORM\Notifications\Notifiable;


/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repositories\User\UserRepository")
 */
class User implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Timestamps, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    const STATUS_CONFIRM = '1';
    const STATUS_NO_CONFIRM = '2';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'verify_code', 'refresh_token'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string",nullable=false)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string",nullable=false)
     */
    protected $role;

    /**
     * @var string
     * @ORM\Column(type="string",nullable=false)
     */
    protected $status;

//    /**
//     * @ORM\Column(name="api_token", type="string", nullable=true, unique=true)
//     */
//    protected $apiToken;
//
//    /**
//     * @ORM\Column(name="api_token_expired", type="datetime", nullable=true)
//     * @var DateTime
//     */
//    protected $authTokenExpired;
//
//    /**
//     * @ORM\Column(name="refresh_token", type="string", nullable=true, unique=true)
//     */
//    protected $refreshToken;

    /**
     * @ORM\Column(name="verify_code", type="string", nullable=true, unique=true)
     */
    protected $verifyCode;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAuthToken() {
        return $this->authToken;
    }

    /**
     * @return mixed
     */
    public function getRefreshToken() {
        return $this->refreshToken;
    }

    /**
     * @return DateTime
     */
    public function getExpired() {
        return $this->authTokenExpired;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getKey()
    {
        return $this->getId();
    }

    /**
     * @param $role
     * @return $this
     * @throws \Exception
     */
    public function setRole($role)
    {
        $roles = [self::ROLE_USER, self::ROLE_ADMIN];
        if (in_array($role, $roles)) {
            $this->role = $role;
            return $this;
        } else {
            throw new \InvalidArgumentException('Wrong value');
        }
//        if (Rule::in([self::ROLE_USER, self::ROLE_ADMIN])) {
//            $this->role = $role;
//            return $this;
//        } else {
//            throw new \Exception('Wrong value');
//        }
    }

    /**
     * @param $status
     * @return $this
     * @throws \Exception
     */
    public function setStatus($status)
    {
        $statuses = [self::STATUS_CONFIRM, self::STATUS_NO_CONFIRM]; // самописный вариант
        if (in_array($status, $statuses)) {
            $this->status = $status;
            return $this;
        } else {
            throw new \InvalidArgumentException('Wrong value');
        }
//        if (Rule::in([self::STATUS_CONFIRM, self::STATUS_NO_CONFIRM])) { // вариант с использованием классов ларавел
//            $this->status = $status;
//            return $this;
//        } else {
//            throw new \Exception('Wrong value');
//        }
    }

    /**
     * @return $this
     */
    public function generateVerifyCode()
    {
        $this->verifyCode = Str::uuid();
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

}