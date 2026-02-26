<?php

namespace App\Entity;

use App\Repository\ProProcesoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProProcesoRepository::class)]
#[ORM\Table(name: 'PRO_PROCESO')]
class ProProceso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'PRO_ID', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'PRO_NOMBRE', type: 'string', length: 60)]
    private ?string $nombre = null;

    #[ORM\Column(name: 'PRO_PREFIJO', type: 'string', length: 20)]
    private ?string $prefijo = null;

    /**
     * @var Collection<int, DocDocumento>
     */
    #[ORM\OneToMany(targetEntity: DocDocumento::class, mappedBy: 'proceso')]
    private Collection $documentos;

    public function __construct()
    {
        $this->documentos = new ArrayCollection();
    }

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

    public function getPrefijo(): ?string
    {
        return $this->prefijo;
    }

    public function setPrefijo(string $prefijo): static
    {
        $this->prefijo = $prefijo;

        return $this;
    }

    /**
     * @return Collection<int, DocDocumento>
     */
    public function getDocumentos(): Collection
    {
        return $this->documentos;
    }

    public function addDocumento(DocDocumento $documento): static
    {
        if (!$this->documentos->contains($documento)) {
            $this->documentos->add($documento);
            $documento->setProceso($this);
        }

        return $this;
    }

    public function removeDocumento(DocDocumento $documento): static
    {
        if ($this->documentos->removeElement($documento)) {
            // set the owning side to null (unless already changed)
            if ($documento->getProceso() === $this) {
                $documento->setProceso(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre ?? '';
    }
}

