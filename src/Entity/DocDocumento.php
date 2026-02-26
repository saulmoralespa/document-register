<?php

namespace App\Entity;

use App\Repository\DocDocumentoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocDocumentoRepository::class)]
#[ORM\Table(name: 'DOC_DOCUMENTO')]
#[ORM\Index(name: 'DOC_PROCESO_idx', columns: ['DOC_ID_PROCESO'])]
#[ORM\Index(name: 'DOC_TIPO_idx', columns: ['DOC_ID_TIPO'])]
class DocDocumento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'DOC_ID', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'DOC_NOMBRE', type: 'string', length: 60)]
    private ?string $nombre = null;

    #[ORM\Column(name: 'DOC_CODIGO', type: 'string', length: 50)]
    private ?string $codigo = null;

    #[ORM\Column(name: 'DOC_CONTENIDO', type: 'string', length: 4000)]
    private ?string $contenido = null;

    #[ORM\ManyToOne(targetEntity: TipTipoDoc::class, inversedBy: 'documentos')]
    #[ORM\JoinColumn(name: 'DOC_ID_TIPO', referencedColumnName: 'TIP_ID', nullable: false)]
    private ?TipTipoDoc $tipo = null;

    #[ORM\ManyToOne(targetEntity: ProProceso::class, inversedBy: 'documentos')]
    #[ORM\JoinColumn(name: 'DOC_ID_PROCESO', referencedColumnName: 'PRO_ID', nullable: false)]
    private ?ProProceso $proceso = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): static
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): static
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getTipo(): ?TipTipoDoc
    {
        return $this->tipo;
    }

    public function setTipo(?TipTipoDoc $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getProceso(): ?ProProceso
    {
        return $this->proceso;
    }

    public function setProceso(?ProProceso $proceso): static
    {
        $this->proceso = $proceso;

        return $this;
    }
}

