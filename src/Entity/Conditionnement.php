<?php

namespace App\Entity;

use App\Repository\ConditionnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;

#[ORM\Entity(repositoryClass: ConditionnementRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Conditionnement
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'conditionnement', targetEntity: Conditionner::class, orphanRemoval: true)]
    private Collection $conditionners;

    #[ORM\OneToMany(mappedBy: 'conditionnement', targetEntity: Prix::class, orphanRemoval: true)]
    private Collection $prixes;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $qte = null;

    public function __construct()
    {
        $this->conditionners = new ArrayCollection();
        $this->prixes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $conditionner->setConditionnement($this);
        }

        return $this;
    }

    public function removeConditionner(Conditionner $conditionner): self
    {
        if ($this->conditionners->removeElement($conditionner)) {
            // set the owning side to null (unless already changed)
            if ($conditionner->getConditionnement() === $this) {
                $conditionner->setConditionnement(null);
            }
        }

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
            $prix->setConditionnement($this);
        }

        return $this;
    }

    public function removePrix(Prix $prix): self
    {
        if ($this->prixes->removeElement($prix)) {
            // set the owning side to null (unless already changed)
            if ($prix->getConditionnement() === $this) {
                $prix->setConditionnement(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return "[ ".$this->code. "]--". $this->libelle;
    }

    public function getQte(): ?string
    {
        return $this->qte;
    }

    public function setQte(?string $qte): self
    {
        $this->qte = $qte;

        return $this;
    }
}
