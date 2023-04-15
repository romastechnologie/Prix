<?php

namespace App\Entity;

use App\Repository\ConditionnerCateClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;

#[ORM\Entity(repositoryClass: ConditionnerCateClientRepository::class)]
class ConditionnerCateClient
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'conditionnerCateClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategClient $cateClient = null;

    #[ORM\ManyToOne(inversedBy: 'conditionnerCateClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conditionner $conditionner = null;

    #[ORM\OneToMany(mappedBy: 'conditionnerClient', targetEntity: Prix::class)]
    private Collection $prixes;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixMin = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixMax = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixVente = null;

    public function __construct()
    {
        $this->prixes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCateClient(): ?CategClient
    {
        return $this->cateClient;
    }

    public function setCateClient(?CategClient $cateClient): self
    {
        $this->cateClient = $cateClient;

        return $this;
    }

    public function getConditionner(): ?Conditionner
    {
        return $this->conditionner;
    }

    public function setConditionner(?Conditionner $conditionner): self
    {
        $this->conditionner = $conditionner;

        return $this;
    }

    /**
     * @return Collection<int, Prix>
     */
    public function getPrixes(): Collection
    {
        return $this->prixes;
    }

    public function addPrix(Prix $prix): self
    {
        if (!$this->prixes->contains($prix)) {
            $this->prixes->add($prix);
            $prix->setConditionnerClient($this);
        }
        return $this;
    }

    public function removePrix(Prix $prix): self
    {
        if ($this->prixes->removeElement($prix)) {
            // set the owning side to null (unless already changed)
            if ($prix->getConditionnerClient() === $this) {
                $prix->setConditionnerClient(null);
            }
        }

        return $this;
    }

    public function getPrixMin(): ?string
    {
        return $this->prixMin == 0 ? NULL : $this->prixMin ;
    }

    public function setPrixMin(?string $prixMin): self
    {
        $this->prixMin = str_replace(" ","",$prixMin);

        return $this;
    }

    public function getPrixMax(): ?string
    {
        return $this->prixMax == 0 ? NULL : $this->prixMax;
    }

    public function setPrixMax(?string $prixMax): self
    {
        $this->prixMax = str_replace(" ","",$prixMax);

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prixVente == 0 ? NULL : $this->prixVente;
    }

    public function setPrixVente(?string $prixVente): self
    {
        $this->prixVente = str_replace(" ","",$prixVente);

        return $this;
    }
}
