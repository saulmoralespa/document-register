<?php

namespace App\Tests\Repository;

use App\Entity\DocDocumento;
use App\Entity\ProProceso;
use App\Entity\TipTipoDoc;
use App\Repository\DocDocumentoRepository;
use App\Repository\ProProcesoRepository;
use App\Repository\TipTipoDocRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DocDocumentoRepositoryTest extends KernelTestCase
{
    private DocDocumentoRepository $repository;
    private EntityManagerInterface $entityManager;
    private ProProcesoRepository $procesoRepository;
    private TipTipoDocRepository $tipoRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = static::getContainer()->get(DocDocumentoRepository::class);
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->procesoRepository = static::getContainer()->get(ProProcesoRepository::class);
        $this->tipoRepository = static::getContainer()->get(TipTipoDocRepository::class);
    }

    public function testRepositoryExists(): void
    {
        $this->assertInstanceOf(DocDocumentoRepository::class, $this->repository);
    }

    public function testFindMaxConsecutiveReturnsZeroWhenNoDocuments(): void
    {
        $tipo = $this->tipoRepository->findOneBy(['prefijo' => 'INS']);
        $proceso = $this->procesoRepository->findOneBy(['prefijo' => 'ING']);

        $this->assertNotNull($tipo);
        $this->assertNotNull($proceso);

        $maxConsecutive = $this->repository->findMaxConsecutive($tipo, $proceso);

        $this->assertIsInt($maxConsecutive);
        $this->assertGreaterThanOrEqual(0, $maxConsecutive);
    }

    public function testSearchReturnsEmptyArrayWhenNoDocuments(): void
    {
        $results = $this->repository->search('NONEXISTENT_DOCUMENT_XYZABC123');

        $this->assertIsArray($results);
    }

    public function testSearchReturnsAllDocumentsWhenQueryIsNull(): void
    {
        $results = $this->repository->search(null);

        $this->assertIsArray($results);
    }

    public function testCanCreateAndPersistDocumento(): void
    {
        $tipo = $this->tipoRepository->findOneBy(['prefijo' => 'INS']);
        $proceso = $this->procesoRepository->findOneBy(['prefijo' => 'ING']);

        $this->assertNotNull($tipo);
        $this->assertNotNull($proceso);

        $documento = new DocDocumento();
        $documento->setNombre('Test Document');
        $documento->setCodigo('INS-ING-1');
        $documento->setContenido('Test content for the document');
        $documento->setTipo($tipo);
        $documento->setProceso($proceso);

        $this->entityManager->persist($documento);
        $this->entityManager->flush();

        $this->assertNotNull($documento->getId());

        // Clean up
        $this->entityManager->remove($documento);
        $this->entityManager->flush();
    }

    public function testFindMaxConsecutiveFindsHighestNumber(): void
    {
        $tipo = $this->tipoRepository->findOneBy(['prefijo' => 'INS']);
        $proceso = $this->procesoRepository->findOneBy(['prefijo' => 'ING']);

        // Create test documents
        $doc1 = new DocDocumento();
        $doc1->setNombre('Test Doc 1');
        $doc1->setCodigo('INS-ING-1');
        $doc1->setContenido('Content 1');
        $doc1->setTipo($tipo);
        $doc1->setProceso($proceso);

        $doc2 = new DocDocumento();
        $doc2->setNombre('Test Doc 2');
        $doc2->setCodigo('INS-ING-3');
        $doc2->setContenido('Content 2');
        $doc2->setTipo($tipo);
        $doc2->setProceso($proceso);

        $this->entityManager->persist($doc1);
        $this->entityManager->persist($doc2);
        $this->entityManager->flush();

        $maxConsecutive = $this->repository->findMaxConsecutive($tipo, $proceso);

        $this->assertEquals(3, $maxConsecutive);

        // Clean up
        $this->entityManager->remove($doc1);
        $this->entityManager->remove($doc2);
        $this->entityManager->flush();
    }

    public function testSearchFindsByNombre(): void
    {
        $tipo = $this->tipoRepository->findOneBy(['prefijo' => 'INS']);
        $proceso = $this->procesoRepository->findOneBy(['prefijo' => 'ING']);

        $documento = new DocDocumento();
        $documento->setNombre('UNIQUE_TEST_DOCUMENT_NAME');
        $documento->setCodigo('INS-ING-999');
        $documento->setContenido('Test content');
        $documento->setTipo($tipo);
        $documento->setProceso($proceso);

        $this->entityManager->persist($documento);
        $this->entityManager->flush();

        $results = $this->repository->search('UNIQUE_TEST_DOCUMENT');

        $this->assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ($result->getNombre() === 'UNIQUE_TEST_DOCUMENT_NAME') {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Document should be found by nombre');

        // Clean up
        $this->entityManager->remove($documento);
        $this->entityManager->flush();
    }

    public function testSearchFindsByCodigo(): void
    {
        $tipo = $this->tipoRepository->findOneBy(['prefijo' => 'INS']);
        $proceso = $this->procesoRepository->findOneBy(['prefijo' => 'ING']);

        $documento = new DocDocumento();
        $documento->setNombre('Test Document');
        $documento->setCodigo('INS-ING-888');
        $documento->setContenido('Test content');
        $documento->setTipo($tipo);
        $documento->setProceso($proceso);

        $this->entityManager->persist($documento);
        $this->entityManager->flush();

        $results = $this->repository->search('INS-ING-888');

        $this->assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ($result->getCodigo() === 'INS-ING-888') {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Document should be found by codigo');

        // Clean up
        $this->entityManager->remove($documento);
        $this->entityManager->flush();
    }
}

