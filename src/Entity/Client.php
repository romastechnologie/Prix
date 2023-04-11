<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $raisonSociale = null;

    #[ORM\Column(length: 13, nullable: true)]
    private ?string $ifu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rccm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sigle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $denomination = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNais = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?CategClient $cateClient = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Prix::class,cascade:["persist"])]
    private Collection $prixs;

    public function __construct()
    {
        $this->prixs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone1(): ?string
    {
        return $this->telephone1;
    }

    public function setTelephone1(?string $telephone1): self
    {
        $this->telephone1 = $telephone1;

        return $this;
    }

    public function getTelephone2(): ?string
    {
        return $this->telephone2;
    }

    public function setTelephone2(?string $telephone2): self
    {
        $this->telephone2 = $telephone2;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(?string $raisonSociale): self
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getIfu(): ?string
    {
        return $this->ifu;
    }

    public function setIfu(?string $ifu): self
    {
        $this->ifu = $ifu;

        return $this;
    }

    public function getRccm(): ?string
    {
        return $this->rccm;
    }

    public function setRccm(?string $rccm): self
    {
        $this->rccm = $rccm;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getDenomination(): ?string
    {
        return $this->denomination;
    }

    public function setDenomination(?string $denomination): self
    {
        $this->denomination = $denomination;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateNais(): ?\DateTimeInterface
    {
        return $this->dateNais;
    }

    public function setDateNais(?\DateTimeInterface $dateNais): self
    {
        $this->dateNais = $dateNais;

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
            $prix->setClient($this);
        }

        return $this;
    }

    public function removePrix(Prix $prix): self
    {
        if ($this->prixs->removeElement($prix)) {
            // set the owning side to null (unless already changed)
            if ($prix->getClient() === $this) {
                $prix->setClient(null);
            }
        }

        return $this;
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

    public function __toString()
    {
        return $this->statut == "Physique" ? $this->nom. " ". $this->prenom . "[ P ]" : $this->sigle."--". $this->denomination ;
    }
}
