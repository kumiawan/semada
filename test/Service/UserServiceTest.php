<?php

namespace dfdiag\Belajar\PHP\MVC\Service;

use dfdiag\Belajar\PHP\MVC\Config\Database;
use dfdiag\Belajar\PHP\MVC\Domain\User;
use dfdiag\Belajar\PHP\MVC\Model\UserLoginRequest;
use dfdiag\Belajar\PHP\MVC\Model\UserRegisterRequest;
use dfdiag\Belajar\PHP\MVC\Repository\UserRepository;
use dfdiag\Belajar\PHP\MVC\Exception\ValidationException;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp():void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);

        $this->userRepository->deleteAll();
    }

    public function testRegisterSuccess()
    {
        $request = new UserRegisterRequest();
        $request->id = "Kr";
        $request->nama = "Kurniawan";
        $request->password = "acikiwiraselole";

        $response = $this->userService->register($request);

        self::assertEquals($request->id, $response->user->id);
        self::assertEquals($request->nama, $response->user->nama);
        self::assertNotEquals($request->password, $response->user->password);

        self::assertTrue(password_verify($request->password, $response->user->password));
    }

    public function testRegisterFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "";
        $request->nama = "";
        $request->password = "";

        $this->userService->register($request);

    }

    public function testRegisterDuplicate()
    {
        $user = new User();
        $user->id = "Kr";
        $user->nama = "Kurniawan";
        $user->password = "acikiwiraselole";

        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "Kr";
        $request->nama = "Kurniawan";
        $request->password = "acikiwiraselole";

        $this->userService->register($request);

    }

    public function testLoginNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->id = "Kr";
        $request->password = "Kurniawan";

        $this->userService->login($request);
    }

    public function testLoginWrongPassword()
    {
        $user = new User();
        $user->id = 'Kr';
        $user->password = password_hash('Kurniamega',PASSWORD_BCRYPT);

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->id = "Kr";
        $request->password = "Kurniawan";

        $this->userService->login($request);
    }


    public function testLoginSuccess()
    {
        $user = new User();
        $user->id = 'Kr';
        $user->password = password_hash('Kurniamega',PASSWORD_BCRYPT);

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->id = "Kr";
        $request->password = "Kurniamega";

        $response = $this->userService->login($request);

        self::assertEquals($request->id,$response->user->id );
        self::assertTrue($request->password,$response->user->password);
    }
}
