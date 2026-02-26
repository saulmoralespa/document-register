<?php

namespace App\Entity;

use App\Repository\TipTipoDocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipTipoDocRepository::class)]
#[ORM\Table(name: 'TIP_TIPO_DOC')]
class TipTipoDoc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'TIP_ID', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'TIP_NOMBRE', type: 'string', length: 60)]
    private ?string $nombre = null;

    #[ORM\Column(name: 'TIP_PREFIJO', type: 'string', length: 20)]
    private ?string $prefijo = null;

    /**
     * @var Collection<int, DocDocumento>
     */
    #[ORM\OneToMany(targetEntity: DocDocumento::class, mappedBy: 'tipo')]
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
            $documento->setTipo($this);
        }

        return $this;
    }

    public function removeDocumento(DocDocumento $documento): static
    {
        if ($this->documentos->removeElement($documento)) {
            // set the owning side to null (unless already changed)
            if ($documento->getTipo() === $this) {
                $documento->setTipo(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre ?? '';
    }
}

