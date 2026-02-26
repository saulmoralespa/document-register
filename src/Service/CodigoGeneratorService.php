<?php

namespace App\Service;

use App\Entity\ProProceso;
use App\Entity\TipTipoDoc;
use App\Repository\DocDocumentoRepository;

class CodigoGeneratorService
{
    public function __construct(
        private readonly DocDocumentoRepository $documentoRepository
    ) {
    }

    /**
     * Generate a unique code for a document based on its tipo and proceso
     * Format: TIP_PREFIJO-PRO_PREFIJO-<consecutive>
     * Example: INS-ING-1
     */
    public function generateCodigo(TipTipoDoc $tipo, ProProceso $proceso): string
    {
        $maxConsecutive = $this->documentoRepository->findMaxConsecutive($tipo, $proceso);
        $nextConsecutive = $maxConsecutive + 1;

        return sprintf(
            '%s-%s-%d',
            $tipo->getPrefijo(),
            $proceso->getPrefijo(),
            $nextConsecutive
        );
    }
}

