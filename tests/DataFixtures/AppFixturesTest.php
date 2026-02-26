<?php

namespace App\Tests\DataFixtures;

use App\DataFixtures\AppFixtures;
use App\Entity\ProProceso;
use App\Entity\TipTipoDoc;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class AppFixturesTest extends TestCase
{
    public function testFixtureExists(): void
    {
        $fixture = new AppFixtures();
        $this->assertInstanceOf(AppFixtures::class, $fixture);
    }

    public function testLoadMethodExists(): void
    {
        $fixture = new AppFixtures();
        $this->assertTrue(method_exists($fixture, 'load'));
    }

    public function testLoadCreatesProProcesos(): void
    {
        $fixture = new AppFixtures();
        $manager = $this->createMock(ObjectManager::class);

        // Should persist at least 5 ProProceso entities
        $persistedProcesos = [];

        $manager->expects($this->atLeastOnce())
            ->method('persist')
            ->willReturnCallback(function ($entity) use (&$persistedProcesos) {
                if ($entity instanceof ProProceso) {
                    $persistedProcesos[] = $entity;
                }
            });

        $manager->expects($this->once())
            ->method('flush');

        $fixture->load($manager);

        $procesoCount = count(array_filter($persistedProcesos, fn($e) => $e instanceof ProProceso));
        $this->assertGreaterThanOrEqual(5, $procesoCount, 'Should create at least 5 ProProceso entities');
    }

    public function testLoadCreatesTipTipoDocs(): void
    {
        $fixture = new AppFixtures();
        $manager = $this->createMock(ObjectManager::class);

        // Should persist at least 5 TipTipoDoc entities
        $persistedTipos = [];

        $manager->expects($this->atLeastOnce())
            ->method('persist')
            ->willReturnCallback(function ($entity) use (&$persistedTipos) {
                if ($entity instanceof TipTipoDoc) {
                    $persistedTipos[] = $entity;
                }
            });

        $manager->expects($this->once())
            ->method('flush');

        $fixture->load($manager);

        $tipoCount = count(array_filter($persistedTipos, fn($e) => $e instanceof TipTipoDoc));
        $this->assertGreaterThanOrEqual(5, $tipoCount, 'Should create at least 5 TipTipoDoc entities');
    }

    public function testLoadCreatesSpecificProcesos(): void
    {
        $fixture = new AppFixtures();
        $manager = $this->createMock(ObjectManager::class);

        $persistedProcesos = [];

        $manager->method('persist')
            ->willReturnCallback(function ($entity) use (&$persistedProcesos) {
                if ($entity instanceof ProProceso) {
                    $persistedProcesos[] = $entity;
                }
            });

        $manager->method('flush');

        $fixture->load($manager);

        // Check for specific required processes
        $nombres = array_map(fn($p) => $p->getNombre(), $persistedProcesos);
        $prefijos = array_map(fn($p) => $p->getPrefijo(), $persistedProcesos);

        $this->assertContains('Ingeniería', $nombres);
        $this->assertContains('ING', $prefijos);
    }

    public function testLoadCreatesSpecificTipos(): void
    {
        $fixture = new AppFixtures();
        $manager = $this->createMock(ObjectManager::class);

        $persistedTipos = [];

        $manager->method('persist')
            ->willReturnCallback(function ($entity) use (&$persistedTipos) {
                if ($entity instanceof TipTipoDoc) {
                    $persistedTipos[] = $entity;
                }
            });

        $manager->method('flush');

        $fixture->load($manager);

        // Check for specific required document types
        $nombres = array_map(fn($t) => $t->getNombre(), $persistedTipos);
        $prefijos = array_map(fn($t) => $t->getPrefijo(), $persistedTipos);

        $this->assertContains('Instructivo', $nombres);
        $this->assertContains('INS', $prefijos);
    }
}

