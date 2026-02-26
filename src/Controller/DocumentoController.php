<?php

namespace App\Controller;

use App\Entity\DocDocumento;
use App\Form\DocDocumentoType;
use App\Repository\DocDocumentoRepository;
use App\Service\CodigoGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/documento')]
class DocumentoController extends AbstractController
{
    #[Route('/', name: 'app_documento_index', methods: ['GET'])]
    public function index(Request $request, DocDocumentoRepository $documentoRepository): Response
    {
        $query = $request->query->get('q', '');
        $documentos = $documentoRepository->search($query);

        return $this->render('documento/index.html.twig', [
            'documentos' => $documentos,
            'query' => $query,
        ]);
    }

    #[Route('/new', name: 'app_documento_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        CodigoGeneratorService $codigoGenerator
    ): Response {
        $documento = new DocDocumento();
        $form = $this->createForm(DocDocumentoType::class, $documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generate unique code based on tipo and proceso
            $codigo = $codigoGenerator->generateCodigo(
                $documento->getTipo(),
                $documento->getProceso()
            );
            $documento->setCodigo($codigo);

            $entityManager->persist($documento);
            $entityManager->flush();

            $this->addFlash('success', 'Documento creado exitosamente con código: ' . $codigo);

            return $this->redirectToRoute('app_documento_index');
        }

        return $this->render('documento/new.html.twig', [
            'documento' => $documento,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_documento_show', methods: ['GET'])]
    public function show(DocDocumento $documento): Response
    {
        return $this->render('documento/show.html.twig', [
            'documento' => $documento,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_documento_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        DocDocumento $documento,
        EntityManagerInterface $entityManager,
        CodigoGeneratorService $codigoGenerator
    ): Response {
        $originalTipo = $documento->getTipo();
        $originalProceso = $documento->getProceso();

        $form = $this->createForm(DocDocumentoType::class, $documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if tipo or proceso changed
            $tipoChanged = $documento->getTipo()->getId() !== $originalTipo->getId();
            $procesoChanged = $documento->getProceso()->getId() !== $originalProceso->getId();

            if ($tipoChanged || $procesoChanged) {
                // Recalculate code
                $newCodigo = $codigoGenerator->generateCodigo(
                    $documento->getTipo(),
                    $documento->getProceso()
                );
                $documento->setCodigo($newCodigo);
                $this->addFlash('info', 'El código ha sido recalculado: ' . $newCodigo);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Documento actualizado exitosamente');

            return $this->redirectToRoute('app_documento_index');
        }

        return $this->render('documento/edit.html.twig', [
            'documento' => $documento,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_documento_delete', methods: ['POST'])]
    public function delete(Request $request, DocDocumento $documento, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$documento->getId(), $request->request->get('_token'))) {
            $entityManager->remove($documento);
            $entityManager->flush();

            $this->addFlash('success', 'Documento eliminado exitosamente');
        }

        return $this->redirectToRoute('app_documento_index');
    }
}

