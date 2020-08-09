<?php

namespace App\Test\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    public function testSetEmail()
    {
        $user = new User();
        $user->setEmail('test@test.pl');

        $this->assertSame(
            'test@test.pl',
            $user->getEmail(),
            'Test ustawienia pola `email` w encji `User` zakończył się niepowodzeniem.'
        );
    }

    public function testGetUserName()
    {
        $user = new User();
        $user->setEmail('test@test.pl');

        $this->assertSame(
            'test@test.pl',
            $user->getUserName(),
            'Test pobrania nazwy użytkownika z encji `User` zakończył się niepowodzeniem.'
        );
    }

    public function testSetPassword()
    {
        $user = new User();
        $user->setPassword('123qweasd');

        $this->assertSame(
            '123qweasd',
            $user->getPassword(),
            'Test ustawienia pola `password` encji `User` zakończył się niepowodzeniem.'
        );
    }

    public function testSetRoles()
    {
        $user = new User();

        $this->assertSame(
            ['ROLE_USER'],
            $user->getRoles(),
            'Test pobrania domyślnych ról z encji `User` zakończył się niepowodzeniem.'
        );

        $user->setRoles(['ROLE_USER']);

        $this->assertSame(
            ['ROLE_USER'],
            $user->getRoles(),
            'Test pobrania domyślnych ról z encji `User` zakończył się niepowodzeniem.'
        );

        $user->setRoles(['ROLE_ADMIN']);

        $this->assertSame(
            ['ROLE_ADMIN', 'ROLE_USER'],
            $user->getRoles(),
            'Test ustawienia ról z encji `User` zakończył się niepowodzeniem.'
        );
    }
}
