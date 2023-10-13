<?php

namespace dfdiag\Belajar\PHP\MVC\Service;

use dfdiag\Belajar\PHP\MVC\Config\Database;
use dfdiag\Belajar\PHP\MVC\Domain\User;
use dfdiag\Belajar\PHP\MVC\Model\UserLoginRequest;
use dfdiag\Belajar\PHP\MVC\Model\UserLoginResponse;
use dfdiag\Belajar\PHP\MVC\Model\UserRegisterRequest;
use dfdiag\Belajar\PHP\MVC\Model\UserRegisterResponse;
use dfdiag\Belajar\PHP\MVC\Repository\UserRepository;
use dfdiag\Belajar\PHP\MVC\Exception\ValidationException;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(UserRegisterRequest $request):UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);

        try{
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if( $user !=null)
            {
                throw new ValidationException("User id sudah digunakan");
            }
            $user = new User();
            $user->id = $request->id;
            $user->nama = $request->nama;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->user = $user;
            Database::commitTransaction();
            return $response;
        }catch (\Exception $exception){
            Database::rollbackTransaction();
            throw $exception;
        }
    }
    public function validateUserRegistrationRequest(UserRegisterRequest $request)
    {
        if($request->id == null || $request->nama == null || $request->password == null ||
        trim($request->id) == "" || trim($request->nama) =="" || trim($request->password) =="")
        {
            throw new ValidationException("id,nama,password tidak boleh kosong");
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);
        $user = $this->userRepository->findById($request->id);
        if($user == null)
        {
            throw new ValidationException("id atau password salah");
        }
        if(password_verify($request->password, $user->password))
        {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        }else{
            throw new ValidationException("id atau password salah");
        }
    }
    private function  validateUserLoginRequest(UserLoginRequest $request)
    {
        if($request->id == null || $request->password == null ||
            trim($request->id) == "" || trim($request->password) =="")
        {
            throw new ValidationException("id atau password tidak boleh kosong");
        }
    }
}