<?php

namespace App\Http\Controllers\Api\Auth;

use App\Entities\User;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    private $userRepository;

    /**
     * RegisterController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:App\Entities\User'],
            'password' => ['required', 'string', 'min:6'],
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     */
    public function register(Request $request)
    {
        $data = $request->all();
        $this->validator($request->all())->validate();

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(Hash::make($data['password']));
        $user->setRole(User::ROLE_USER)->setStatus(User::STATUS_NO_CONFIRM)->generateVerifyCode();
        $this->userRepository->save($user);

        event(new Registered($user));

        return response()->json(['response' => new UserResource($user)], Response::HTTP_CREATED);
    }
}
