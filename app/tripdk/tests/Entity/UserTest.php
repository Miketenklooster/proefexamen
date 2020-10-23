<?php
namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function testUserCreate(){
        print("\nTesting UserCreateAction() \n");
        $user = new User(); // Create User object.
        $user->setEmail("test@gmail.com");
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setPassword('test');

        print(" Expecting 3 out of 3 succeeded");
        $this->assertEquals("test@gmail.com", $user->getUserName());
        $this->assertEquals(['ROLE_USER', 'ROLE_ADMIN'], $user->getRoles());
        $this->assertEquals('test', $user->getPassword());
    }
}
