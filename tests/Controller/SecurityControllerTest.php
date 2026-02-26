<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="_username"]');
        $this->assertSelectorExists('input[name="_password"]');
    }

    public function testLoginPageHasTitle(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('h1, h2')->count());
    }

    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Iniciar Sesión')->form([
            '_username' => 'admin',
            '_password' => 'admin123',
        ]);

        $client->submit($form);

        // Should redirect after successful login
        $this->assertResponseRedirects();
        $client->followRedirect();

        // Should be on a protected page after login
        $this->assertResponseIsSuccessful();
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Iniciar Sesión')->form([
            '_username' => 'admin',
            '_password' => 'wrongpassword',
        ]);

        $client->submit($form);

        // Should redirect back to login
        $this->assertResponseRedirects('/login');
        $client->followRedirect();

        // Should show error message
        $this->assertSelectorExists('.alert, .error');
    }

    public function testLogoutRedirectsToLogin(): void
    {
        $client = static::createClient();

        // First login
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Iniciar Sesión')->form([
            '_username' => 'admin',
            '_password' => 'admin123',
        ]);
        $client->submit($form);
        $client->followRedirect();

        // Then logout
        $client->request('GET', '/logout');

        $this->assertResponseRedirects();
    }

    public function testProtectedPageRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        // Should redirect to login
        $this->assertResponseRedirects();

        $client->followRedirect();
        $this->assertRouteSame('app_login');
    }

    public function testAuthenticatedUserCanAccessProtectedPages(): void
    {
        $client = static::createClient();

        // Login first
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Iniciar Sesión')->form([
            '_username' => 'admin',
            '_password' => 'admin123',
        ]);
        $client->submit($form);
        $client->followRedirect();

        // Now try to access protected page - should redirect to /documento/
        $client->request('GET', '/');
        $this->assertResponseRedirects('/documento/');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testLoginPageIsAccessibleWhenAlreadyLoggedIn(): void
    {
        $client = static::createClient();

        // Login
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Iniciar Sesión')->form([
            '_username' => 'admin',
            '_password' => 'admin123',
        ]);
        $client->submit($form);
        $client->followRedirect();

        // Try to access login page again
        $client->request('GET', '/login');

        // Should redirect to /documento/ when already logged in
        $this->assertResponseRedirects('/documento/');
    }
}


