<?php

namespace App\Entity;

use App\Repository\ConditionnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;

#[ORM\Entity(repositoryClass: ConditionnerRepository::class)]
class Conditionner
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'conditionners',cascade:["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'conditionners')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conditionnement $conditionnement = null;

    #[ORM\OneToMany(mappedBy: 'conditionner', targetEntity: ConditionnerCateClient::class, orphanRemoval: true, cascade :["persist"])]
    private Collection $conditionnerCateClients;

    #[ORM\OneToMany(mappedBy: 'conditionner', targetEntity: Prix::class)]
    private Collection $prixs;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixMin = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixMax = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixConcurentiel = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixAchat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixRevient = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $qteProduit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixVente = null;

    public function __construct()
    {
        $this->conditionnerCateClients = new ArrayCollection();
        $this->prixs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getConditionnement(): ?Conditionnement
    {
        return $this->conditionnement;
    }

    public function setConditionnement(?Conditionnement $conditionnement): self
    {
        $this->conditionnement = $conditionnement;
        return $this;
    }

    /**
     * @return Collection<int, ConditionnerCateClient>
     */
    public function getConditionnerCateClients(): Collection
    {
        return $this->conditionnerCateClients;
    }

    public function addConditionnerCateClient(ConditionnerCateClient $conditionnerCateClient): self
    {
        if (!$this->conditionnerCateClients->contains($conditionnerCateClient)) {
            $this->conditionnerCateClients->add($conditionnerCateClient);
            $conditionnerCateClient->setConditionner($this);
        }

        return $this;
    }

    public function removeConditionnerCateClient(ConditionnerCateClient $conditionnerCateClient): self
    {
        if ($this->conditionnerCateClients->removeElement($conditionnerCateClient)) {
            // set the owning side to null (unless already changed)
            if ($conditionnerCateClient->getConditionner() === $this) {
                $conditionnerCateClient->setConditionner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prix>
     */
    public function getPrixs(): Collection
    {
        return $this->prixs;
    }

    public function addPrix(Prix $prix): self
    {
        if (!$this->prixs->contains($prix)) {
            $this->prixs->add($prix);
            $prix->setConditionner($this);
        }

        return $this;
    }

    public function removePrix(Prix $prix): self
    {
        if ($this->prixs->removeElement($prix)) {
            // set the owning side to null (unless already changed)
            if ($prix->getConditionner() === $this) {
                $prix->setConditionner(null);
            }
        }

        return $this;
    }

    public function getPrixMin(): ?string
    {
        return $this->prixMin;
    }

    public function setPrixMin(?string $prixMin): self
    {
        $this->prixMin = $prixMin;

        return $this;
    }

    public function getPrixMax(): ?string
    {
        return $this->prixMax;
    }

    public function setPrixMax(?string $prixMax): self
    {
        $this->prixMax = $prixMax;

        return $this;
    }

    public function getPrixConcurentiel(): ?string
    {
        return $this->prixConcurentiel;
    }

    public function setPrixConcurentiel(?string $prixConcurentiel): self
    {
        $this->prixConcurentiel = $prixConcurentiel;

        return $this;
    }

    public function getPrixAchat(): ?string
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(?string $prixAchat): self
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    public function getPrixRevient(): ?string
    {
        return $this->prixRevient;
    }

    public function setPrixRevient(?string $prixRevient): self
    {
        $this->prixRevient = $prixRevient;

        return $this;
    }

    public function __toString()
    {
        return $this->produit->getDesignation() . " -- ". $this->conditionnement ;
    }

    public function getQteProduit(): ?string
    {
        return $this->qteProduit == null ? 0 : $this->qteProduit;
    }

    public function setQteProduit(?string $qteProduit): self
    {
        $this->qteProduit = $qteProduit;

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prixVente == null ? 0 : $this->prixVente;
    }

    public function setPrixVente(?string $prixVente): self
    {
        $this->prixVente = $prixVente;

        return $this;
    }

}
