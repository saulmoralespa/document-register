<?php

namespace App\Tests\Entity;

use App\Entity\DocDocumento;
use App\Entity\ProProceso;
use App\Entity\TipTipoDoc;
use PHPUnit\Framework\TestCase;

class DocDocumentoTest extends TestCase
{
    public function testCanCreateDocDocumento(): void
    {
        $documento = new DocDocumento();
        $this->assertInstanceOf(DocDocumento::class, $documento);
    }

    public function testCanSetAndGetNombre(): void
    {
        $documento = new DocDocumento();
        $documento->setNombre('INSTRUCTIVO DE DESARROLLO');

        $this->assertEquals('INSTRUCTIVO DE DESARROLLO', $documento->getNombre());
    }

    public function testCanSetAndGetCodigo(): void
    {
        $documento = new DocDocumento();
        $documento->setCodigo('INS-ING-1');

        $this->assertEquals('INS-ING-1', $documento->getCodigo());
    }

    public function testCanSetAndGetContenido(): void
    {
        $documento = new DocDocumento();
        $contenido = 'Este es el contenido del documento de prueba';
        $documento->setContenido($contenido);

        $this->assertEquals($contenido, $documento->getContenido());
    }

    public function testCanSetAndGetTipo(): void
    {
        $documento = new DocDocumento();
        $tipo = new TipTipoDoc();
        $tipo->setNombre('Instructivo');
        $tipo->setPrefijo('INS');

        $documento->setTipo($tipo);

        $this->assertSame($tipo, $documento->getTipo());
    }

    public function testCanSetAndGetProceso(): void
    {
        $documento = new DocDocumento();
        $proceso = new ProProceso();
        $proceso->setNombre('Ingeniería');
        $proceso->setPrefijo('ING');

        $documento->setProceso($proceso);

        $this->assertSame($proceso, $documento->getProceso());
    }

    public function testIdIsNullByDefault(): void
    {
        $documento = new DocDocumento();
        $this->assertNull($documento->getId());
    }

    public function testSetNombreReturnsStatic(): void
    {
        $documento = new DocDocumento();
        $result = $documento->setNombre('Test');

        $this->assertSame($documento, $result);
    }

    public function testSetCodigoReturnsStatic(): void
    {
        $documento = new DocDocumento();
        $result = $documento->setCodigo('TST-TST-1');

        $this->assertSame($documento, $result);
    }

    public function testSetContenidoReturnsStatic(): void
    {
        $documento = new DocDocumento();
        $result = $documento->setContenido('Test content');

        $this->assertSame($documento, $result);
    }

    public function testSetTipoReturnsStatic(): void
    {
        $documento = new DocDocumento();
        $tipo = new TipTipoDoc();
        $result = $documento->setTipo($tipo);

        $this->assertSame($documento, $result);
    }

    public function testSetProcesoReturnsStatic(): void
    {
        $documento = new DocDocumento();
        $proceso = new ProProceso();
        $result = $documento->setProceso($proceso);

        $this->assertSame($documento, $result);
    }

    public function testCanSetNullTipo(): void
    {
        $documento = new DocDocumento();
        $tipo = new TipTipoDoc();

        $documento->setTipo($tipo);
        $this->assertNotNull($documento->getTipo());

        $documento->setTipo(null);
        $this->assertNull($documento->getTipo());
    }

    public function testCanSetNullProceso(): void
    {
        $documento = new DocDocumento();
        $proceso = new ProProceso();

        $documento->setProceso($proceso);
        $this->assertNotNull($documento->getProceso());

        $documento->setProceso(null);
        $this->assertNull($documento->getProceso());
    }
}

