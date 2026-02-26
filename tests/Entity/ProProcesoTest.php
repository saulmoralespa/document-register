<?php

namespace App\Tests\Entity;

use App\Entity\ProProceso;
use App\Entity\DocDocumento;
use PHPUnit\Framework\TestCase;

class ProProcesoTest extends TestCase
{
    public function testCanCreateProProceso(): void
    {
        $proceso = new ProProceso();
        $this->assertInstanceOf(ProProceso::class, $proceso);
    }

    public function testCanSetAndGetNombre(): void
    {
        $proceso = new ProProceso();
        $proceso->setNombre('Ingeniería');

        $this->assertEquals('Ingeniería', $proceso->getNombre());
    }

    public function testCanSetAndGetPrefijo(): void
    {
        $proceso = new ProProceso();
        $proceso->setPrefijo('ING');

        $this->assertEquals('ING', $proceso->getPrefijo());
    }

    public function testIdIsNullByDefault(): void
    {
        $proceso = new ProProceso();
        $this->assertNull($proceso->getId());
    }

    public function testToStringReturnsNombre(): void
    {
        $proceso = new ProProceso();
        $proceso->setNombre('Ingeniería');

        $this->assertEquals('Ingeniería', (string) $proceso);
    }

    public function testToStringReturnsEmptyWhenNombreIsNull(): void
    {
        $proceso = new ProProceso();
        $this->assertEquals('', (string) $proceso);
    }

    public function testDocumentosCollectionIsInitialized(): void
    {
        $proceso = new ProProceso();
        $this->assertCount(0, $proceso->getDocumentos());
    }

    public function testCanAddDocumento(): void
    {
        $proceso = new ProProceso();
        $documento = $this->createMock(DocDocumento::class);

        $documento->expects($this->once())
            ->method('setProceso')
            ->with($proceso);

        $proceso->addDocumento($documento);

        $this->assertCount(1, $proceso->getDocumentos());
        $this->assertTrue($proceso->getDocumentos()->contains($documento));
    }

    public function testDoesNotAddDuplicateDocumento(): void
    {
        $proceso = new ProProceso();
        $documento = $this->createMock(DocDocumento::class);

        $documento->expects($this->once())
            ->method('setProceso')
            ->with($proceso);

        $proceso->addDocumento($documento);
        $proceso->addDocumento($documento); // Try to add again

        $this->assertCount(1, $proceso->getDocumentos());
    }

    public function testCanRemoveDocumento(): void
    {
        $proceso = new ProProceso();
        $documento = $this->createMock(DocDocumento::class);

        $documento->method('setProceso');
        $documento->method('getProceso')->willReturn($proceso);

        $proceso->addDocumento($documento);
        $this->assertCount(1, $proceso->getDocumentos());

        $proceso->removeDocumento($documento);
        $this->assertCount(0, $proceso->getDocumentos());
    }

    public function testSetNombreReturnsStatic(): void
    {
        $proceso = new ProProceso();
        $result = $proceso->setNombre('Test');

        $this->assertSame($proceso, $result);
    }

    public function testSetPrefijoReturnsStatic(): void
    {
        $proceso = new ProProceso();
        $result = $proceso->setPrefijo('TST');

        $this->assertSame($proceso, $result);
    }
}

