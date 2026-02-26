<?php

namespace App\Tests\Entity;

use App\Entity\TipTipoDoc;
use App\Entity\DocDocumento;
use PHPUnit\Framework\TestCase;

class TipTipoDocTest extends TestCase
{
    public function testCanCreateTipTipoDoc(): void
    {
        $tipo = new TipTipoDoc();
        $this->assertInstanceOf(TipTipoDoc::class, $tipo);
    }

    public function testCanSetAndGetNombre(): void
    {
        $tipo = new TipTipoDoc();
        $tipo->setNombre('Instructivo');

        $this->assertEquals('Instructivo', $tipo->getNombre());
    }

    public function testCanSetAndGetPrefijo(): void
    {
        $tipo = new TipTipoDoc();
        $tipo->setPrefijo('INS');

        $this->assertEquals('INS', $tipo->getPrefijo());
    }

    public function testIdIsNullByDefault(): void
    {
        $tipo = new TipTipoDoc();
        $this->assertNull($tipo->getId());
    }

    public function testToStringReturnsNombre(): void
    {
        $tipo = new TipTipoDoc();
        $tipo->setNombre('Instructivo');

        $this->assertEquals('Instructivo', (string) $tipo);
    }

    public function testToStringReturnsEmptyWhenNombreIsNull(): void
    {
        $tipo = new TipTipoDoc();
        $this->assertEquals('', (string) $tipo);
    }

    public function testDocumentosCollectionIsInitialized(): void
    {
        $tipo = new TipTipoDoc();
        $this->assertCount(0, $tipo->getDocumentos());
    }

    public function testCanAddDocumento(): void
    {
        $tipo = new TipTipoDoc();
        $documento = $this->createMock(DocDocumento::class);

        $documento->expects($this->once())
            ->method('setTipo')
            ->with($tipo);

        $tipo->addDocumento($documento);

        $this->assertCount(1, $tipo->getDocumentos());
        $this->assertTrue($tipo->getDocumentos()->contains($documento));
    }

    public function testDoesNotAddDuplicateDocumento(): void
    {
        $tipo = new TipTipoDoc();
        $documento = $this->createMock(DocDocumento::class);

        $documento->expects($this->once())
            ->method('setTipo')
            ->with($tipo);

        $tipo->addDocumento($documento);
        $tipo->addDocumento($documento); // Try to add again

        $this->assertCount(1, $tipo->getDocumentos());
    }

    public function testCanRemoveDocumento(): void
    {
        $tipo = new TipTipoDoc();
        $documento = $this->createMock(DocDocumento::class);

        $documento->method('setTipo');
        $documento->method('getTipo')->willReturn($tipo);

        $tipo->addDocumento($documento);
        $this->assertCount(1, $tipo->getDocumentos());

        $tipo->removeDocumento($documento);
        $this->assertCount(0, $tipo->getDocumentos());
    }

    public function testSetNombreReturnsStatic(): void
    {
        $tipo = new TipTipoDoc();
        $result = $tipo->setNombre('Test');

        $this->assertSame($tipo, $result);
    }

    public function testSetPrefijoReturnsStatic(): void
    {
        $tipo = new TipTipoDoc();
        $result = $tipo->setPrefijo('TST');

        $this->assertSame($tipo, $result);
    }
}

