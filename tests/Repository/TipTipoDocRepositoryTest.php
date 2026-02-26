<?php

namespace App\Tests\Repository;

use App\Entity\TipTipoDoc;
use App\Repository\TipTipoDocRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TipTipoDocRepositoryTest extends KernelTestCase
{
    private TipTipoDocRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = static::getContainer()->get(TipTipoDocRepository::class);
    }

    public function testRepositoryExists(): void
    {
        $this->assertInstanceOf(TipTipoDocRepository::class, $this->repository);
    }

    public function testFindAllOrderedReturnsTipTipoDocs(): void
    {
        $tipos = $this->repository->findAllOrdered();

        $this->assertIsArray($tipos);
        $this->assertNotEmpty($tipos, 'Should have at least one TipTipoDoc');

        foreach ($tipos as $tipo) {
            $this->assertInstanceOf(TipTipoDoc::class, $tipo);
        }
    }

    public function testFindAllOrderedReturnsAtLeastFiveItems(): void
    {
        $tipos = $this->repository->findAllOrdered();

        $this->assertGreaterThanOrEqual(5, count($tipos), 'Should have at least 5 TipTipoDoc records');
    }

    public function testFindAllOrderedReturnsOrderedByNombre(): void
    {
        $tipos = $this->repository->findAllOrdered();

        $nombres = array_map(fn($t) => $t->getNombre(), $tipos);
        $nombresOrdenados = $nombres;
        sort($nombresOrdenados);

        $this->assertEquals($nombresOrdenados, $nombres, 'TipTipoDocs should be ordered by nombre');
    }

    public function testFindAllOrderedContainsExpectedTipos(): void
    {
        $tipos = $this->repository->findAllOrdered();
        $nombres = array_map(fn($t) => $t->getNombre(), $tipos);
        $prefijos = array_map(fn($t) => $t->getPrefijo(), $tipos);

        // Verify required document types exist
        $this->assertContains('Instructivo', $nombres);
        $this->assertContains('Procedimiento', $nombres);
        $this->assertContains('Manual', $nombres);
        $this->assertContains('Formato', $nombres);
        $this->assertContains('Registro', $nombres);

        // Verify required prefixes exist
        $this->assertContains('INS', $prefijos);
        $this->assertContains('PRO', $prefijos);
        $this->assertContains('MAN', $prefijos);
        $this->assertContains('FOR', $prefijos);
        $this->assertContains('REG', $prefijos);
    }

    public function testCanFindTipoById(): void
    {
        $tipos = $this->repository->findAllOrdered();
        $this->assertNotEmpty($tipos);

        $firstTipo = $tipos[0];
        $found = $this->repository->find($firstTipo->getId());

        $this->assertNotNull($found);
        $this->assertEquals($firstTipo->getId(), $found->getId());
        $this->assertEquals($firstTipo->getNombre(), $found->getNombre());
        $this->assertEquals($firstTipo->getPrefijo(), $found->getPrefijo());
    }

    public function testTipoHasCorrectStructure(): void
    {
        $tipos = $this->repository->findAllOrdered();
        $this->assertNotEmpty($tipos);

        $tipo = $tipos[0];

        $this->assertIsInt($tipo->getId());
        $this->assertIsString($tipo->getNombre());
        $this->assertIsString($tipo->getPrefijo());
        $this->assertNotEmpty($tipo->getNombre());
        $this->assertNotEmpty($tipo->getPrefijo());
    }
}

