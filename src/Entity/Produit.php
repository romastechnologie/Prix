<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    use Timestampable;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\Column(length: 255,nullable: true,unique:true)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $refUsine = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?SousCategorie $sousCategorie = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?ModeDef $mode = null;

    #[ORM\Column(type: Types::BINARY)]
    private $aTaxe = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Conditionner::class, orphanRemoval: true, cascade :["persist"])]
    private Collection $conditionners;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Media::class)]
    private Collection $media;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixAchat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixRevient = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $qteUniteDeMesure = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixVente = null;


    public function __construct()
    {
        $this->conditionners = new ArrayCollection();
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = trim( strtoupper( $designation ));

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = trim( strtoupper($code));

        return $this;
    }

    public function getRefUsine(): ?string
    {
        return $this->refUsine;
    }

    public function setRefUsine(?string $refUsine): self
    {
        $this->refUsine = trim( strtoupper($refUsine));

        return $this;
    }

    public function getSousCategorie(): ?SousCategorie
    {
        return $this->sousCategorie;
    }

    public function setSousCategorie(?SousCategorie $sousCategorie): self
    {
        $this->sousCategorie = $sousCategorie;

        return $this;
    }

    public function getMode(): ?ModeDef
    {
        return $this->mode;
    }

    public function setMode(?ModeDef $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getATaxe()
    {
        return $this->aTaxe;
    }

    public function setATaxe($aTaxe): self
    {
        $this->aTaxe = $aTaxe;

        return $this;
    }

    /**
     * @return Collection<int, Conditionner>
     */
    public function getConditionners(): Collection
    {
        return $this->conditionners;
    }

    public function addConditionner(Conditionner $conditionner): self
    {
        if (!$this->conditionners->contains($conditionner)) {
            $this->conditionners->add($conditionner);
            $conditionner->setProduit($this);
        }

        return $this;
    }

    public function removeConditionner(Conditionner $conditionner): self
    {
        if ($this->conditionners->removeElement($conditionner)) {
            // set the owning side to null (unless already changed)
            if ($conditionner->getProduit() === $this) {
                $conditionner->setProduit(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setProduit($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getProduit() === $this) {
                $medium->setProduit(null);
            }
        }

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

    public function getQteUniteDeMesure(): ?string
    {
        return $this->qteUniteDeMesure;
    }

    public function setQteUniteDeMesure(?string $qteUniteDeMesure): self
    {
        $this->qteUniteDeMesure = $qteUniteDeMesure;

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prixVente;
    }

    public function setPrixVente(?string $prixVente): self
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    public function __toString()
    {
        return $this->code ." -- ". $this->designation ;
    }

}
