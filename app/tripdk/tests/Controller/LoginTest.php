<?php
namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    /**
     * @test
     * Filling in the login form and then redirecting to the admin page.
     */
    public function testLogin()
    {
        print("\nTesting loginFormAction() \n");
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();
        // set the values
        $form['email'] = 'test@gmail.com';
        $form['password'] = 'test';
        $values = $form->getPhpValues();

        print(" Set Form values.\n");
        print("  Email: " . $values["email"] . "\n");
        print("  Password: " . $values["password"] . "\n");

        // submit the form
        $client->submit($form);

        $client->followRedirect();

        print(" Expecting 200. Got: ". $client->getResponse()->getStatusCode());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     * Visiting the admin page after you've logged in.
     */
    public function testVisitingWhileLoggedIn()
    {
        print("\n\nTesting LoggedInAction() \n");
        $client = static::createClient();

        // get or create the user somehow (e.g. creating some users only
        // for tests while loading the test fixtures)
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');

        $client->loginUser($testUser);

        // user is now logged in, so test the protected resources
        $client->request('GET', '/admin');
        print(" Expecting 200. Got: ". $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }
}
