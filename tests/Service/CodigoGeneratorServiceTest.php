<?php

namespace App\Tests\Service;

use App\Entity\ProProceso;
use App\Entity\TipTipoDoc;
use App\Entity\DocDocumento;
use App\Repository\DocDocumentoRepository;
use App\Service\CodigoGeneratorService;
use PHPUnit\Framework\TestCase;

class CodigoGeneratorServiceTest extends TestCase
{
    public function testServiceExists(): void
    {
        $repository = $this->createMock(DocDocumentoRepository::class);
        $service = new CodigoGeneratorService($repository);

        $this->assertInstanceOf(CodigoGeneratorService::class, $service);
    }

    public function testGenerateCodigoWithNoExistingDocuments(): void
    {
        $repository = $this->createMock(DocDocumentoRepository::class);
        $repository->method('findMaxConsecutive')->willReturn(0);

        $service = new CodigoGeneratorService($repository);

        $tipo = new TipTipoDoc();
        $tipo->setPrefijo('INS');

        $proceso = new ProProceso();
        $proceso->setPrefijo('ING');

        $codigo = $service->generateCodigo($tipo, $proceso);

        $this->assertEquals('INS-ING-1', $codigo);
    }

    public function testGenerateCodigoWithExistingDocuments(): void
    {
        $repository = $this->createMock(DocDocumentoRepository::class);
        $repository->method('findMaxConsecutive')->willReturn(5);

        $service = new CodigoGeneratorService($repository);

        $tipo = new TipTipoDoc();
        $tipo->setPrefijo('PRO');

        $proceso = new ProProceso();
        $proceso->setPrefijo('CAL');

        $codigo = $service->generateCodigo($tipo, $proceso);

        $this->assertEquals('PRO-CAL-6', $codigo);
    }

    public function testGenerateCodigoFormat(): void
    {
        $repository = $this->createMock(DocDocumentoRepository::class);
        $repository->method('findMaxConsecutive')->willReturn(99);

        $service = new CodigoGeneratorService($repository);

        $tipo = new TipTipoDoc();
        $tipo->setPrefijo('MAN');

        $proceso = new ProProceso();
        $proceso->setPrefijo('FIN');

        $codigo = $service->generateCodigo($tipo, $proceso);

        // Verify format: TIP-PRO-NUM
        $this->assertMatchesRegularExpression('/^[A-Z]+-[A-Z]+-\d+$/', $codigo);
        $this->assertEquals('MAN-FIN-100', $codigo);
    }

    public function testGenerateCodigoWithDifferentCombinations(): void
    {
        $repository = $this->createMock(DocDocumentoRepository::class);

        // Different combinations should have independent consecutive numbers
        $repository->method('findMaxConsecutive')->willReturnCallback(
            function ($tipo, $proceso) {
                $key = $tipo->getPrefijo() . '-' . $proceso->getPrefijo();
                $counters = [
                    'INS-ING' => 3,
                    'INS-CAL' => 0,
                    'PRO-ING' => 1,
                ];
                return $counters[$key] ?? 0;
            }
        );

        $service = new CodigoGeneratorService($repository);

        // Test different combinations
        $tipo1 = new TipTipoDoc();
        $tipo1->setPrefijo('INS');
        $proceso1 = new ProProceso();
        $proceso1->setPrefijo('ING');
        $this->assertEquals('INS-ING-4', $service->generateCodigo($tipo1, $proceso1));

        $tipo2 = new TipTipoDoc();
        $tipo2->setPrefijo('INS');
        $proceso2 = new ProProceso();
        $proceso2->setPrefijo('CAL');
        $this->assertEquals('INS-CAL-1', $service->generateCodigo($tipo2, $proceso2));

        $tipo3 = new TipTipoDoc();
        $tipo3->setPrefijo('PRO');
        $proceso3 = new ProProceso();
        $proceso3->setPrefijo('ING');
        $this->assertEquals('PRO-ING-2', $service->generateCodigo($tipo3, $proceso3));
    }

    public function testGenerateCodigoIncrementsByOne(): void
    {
        $repository = $this->createMock(DocDocumentoRepository::class);
        $repository->method('findMaxConsecutive')->willReturn(42);

        $service = new CodigoGeneratorService($repository);

        $tipo = new TipTipoDoc();
        $tipo->setPrefijo('FOR');

        $proceso = new ProProceso();
        $proceso->setPrefijo('RH');

        $codigo = $service->generateCodigo($tipo, $proceso);

        $this->assertEquals('FOR-RH-43', $codigo);
    }
}

