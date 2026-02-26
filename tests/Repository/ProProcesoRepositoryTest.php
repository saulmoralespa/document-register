<?php

namespace App\Tests\Repository;

use App\Entity\ProProceso;
use App\Repository\ProProcesoRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProProcesoRepositoryTest extends KernelTestCase
{
    private ProProcesoRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = static::getContainer()->get(ProProcesoRepository::class);
    }

    public function testRepositoryExists(): void
    {
        $this->assertInstanceOf(ProProcesoRepository::class, $this->repository);
    }

    public function testFindAllOrderedReturnsProProcesos(): void
    {
        $procesos = $this->repository->findAllOrdered();

        $this->assertIsArray($procesos);
        $this->assertNotEmpty($procesos, 'Should have at least one ProProceso');

        foreach ($procesos as $proceso) {
            $this->assertInstanceOf(ProProceso::class, $proceso);
        }
    }

    public function testFindAllOrderedReturnsAtLeastFiveItems(): void
    {
        $procesos = $this->repository->findAllOrdered();

        $this->assertGreaterThanOrEqual(5, count($procesos), 'Should have at least 5 ProProceso records');
    }

    public function testFindAllOrderedReturnsOrderedByNombre(): void
    {
        $procesos = $this->repository->findAllOrdered();

        $nombres = array_map(fn($p) => $p->getNombre(), $procesos);
        $nombresOrdenados = $nombres;
        sort($nombresOrdenados);

        $this->assertEquals($nombresOrdenados, $nombres, 'ProProcesos should be ordered by nombre');
    }

    public function testFindAllOrderedContainsExpectedProcesos(): void
    {
        $procesos = $this->repository->findAllOrdered();
        $nombres = array_map(fn($p) => $p->getNombre(), $procesos);
        $prefijos = array_map(fn($p) => $p->getPrefijo(), $procesos);

        // Verify required processes exist
        $this->assertContains('Ingeniería', $nombres);
        $this->assertContains('Recursos Humanos', $nombres);
        $this->assertContains('Finanzas', $nombres);
        $this->assertContains('Operaciones', $nombres);
        $this->assertContains('Calidad', $nombres);

        // Verify required prefixes exist
        $this->assertContains('ING', $prefijos);
        $this->assertContains('RH', $prefijos);
        $this->assertContains('FIN', $prefijos);
        $this->assertContains('OPE', $prefijos);
        $this->assertContains('CAL', $prefijos);
    }

    public function testCanFindProcesoById(): void
    {
        $procesos = $this->repository->findAllOrdered();
        $this->assertNotEmpty($procesos);

        $firstProceso = $procesos[0];
        $found = $this->repository->find($firstProceso->getId());

        $this->assertNotNull($found);
        $this->assertEquals($firstProceso->getId(), $found->getId());
        $this->assertEquals($firstProceso->getNombre(), $found->getNombre());
        $this->assertEquals($firstProceso->getPrefijo(), $found->getPrefijo());
    }

    public function testProcesoHasCorrectStructure(): void
    {
        $procesos = $this->repository->findAllOrdered();
        $this->assertNotEmpty($procesos);

        $proceso = $procesos[0];

        $this->assertIsInt($proceso->getId());
        $this->assertIsString($proceso->getNombre());
        $this->assertIsString($proceso->getPrefijo());
        $this->assertNotEmpty($proceso->getNombre());
        $this->assertNotEmpty($proceso->getPrefijo());
    }
}

