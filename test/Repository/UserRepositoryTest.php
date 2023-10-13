<?php

namespace dfdiag\Belajar\PHP\MVC\Repository;

use dfdiag\Belajar\PHP\MVC\Config\Database;
use dfdiag\Belajar\PHP\MVC\Domain\User;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    protected function setUp():void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository ->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->id = "Kr";
        $user->nama = "Kurniawan";
        $user->password = "acikiwir";

        $this->userRepository->save($user);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->nama, $result->nama);
        self::assertEquals($user->password, $result->password);
    }

    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findById("notfound");
        self::assertNull($user);
    }

}
