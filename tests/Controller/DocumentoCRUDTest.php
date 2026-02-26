<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DocumentoCRUDTest extends WebTestCase
{
    private function login($client): void
    {
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Iniciar Sesión')->form([
            '_username' => 'admin',
            '_password' => 'admin123',
        ]);
        $client->submit($form);
        $client->followRedirect();
    }

    public function testDocumentoIndexIsAccessible(): void
    {
        $client = static::createClient();
        $this->login($client);

        $client->request('GET', '/documento/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Registro de Documentos');
    }

    public function testNewDocumentoFormIsAccessible(): void
    {
        $client = static::createClient();
        $this->login($client);

        $crawler = $client->request('GET', '/documento/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Nuevo Documento');

        // Check form fields exist
        $this->assertCount(1, $crawler->filter('input[name="doc_documento[nombre]"]'));
        $this->assertCount(1, $crawler->filter('select[name="doc_documento[tipo]"]'));
        $this->assertCount(1, $crawler->filter('select[name="doc_documento[proceso]"]'));
        $this->assertCount(1, $crawler->filter('textarea[name="doc_documento[contenido]"]'));
    }

    public function testFormHasCorrectLabels(): void
    {
        $client = static::createClient();
        $this->login($client);

        $client->request('GET', '/documento/new');

        $this->assertSelectorExists('label:contains("Nombre del Documento")');
        $this->assertSelectorExists('label:contains("Tipo de Documento")');
        $this->assertSelectorExists('label:contains("Proceso")');
        $this->assertSelectorExists('label:contains("Contenido del Documento")');
    }

    public function testSearchFormExists(): void
    {
        $client = static::createClient();
        $this->login($client);

        $crawler = $client->request('GET', '/documento/');

        $this->assertCount(1, $crawler->filter('input[name="q"]'));
        $this->assertCount(1, $crawler->filter('button[type="submit"]:contains("Buscar")'));
    }

    public function testNewDocumentoButtonExists(): void
    {
        $client = static::createClient();
        $this->login($client);

        $crawler = $client->request('GET', '/documento/');

        // Check that the "Nuevo Documento" link exists
        $link = $crawler->filter('a:contains("Nuevo Documento")');
        $this->assertGreaterThan(0, $link->count(), 'Link "Nuevo Documento" should exist');
    }
}




