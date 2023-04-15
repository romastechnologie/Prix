<?php

namespace App\Entity;

use App\Repository\PrixRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;
use DateTime;

#[ORM\Entity(repositoryClass: PrixRepository::class)]
class Prix
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixMin = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixMax = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAttribution = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: Types::BINARY,  nullable: true)]
    private $estActif = null;

    #[ORM\ManyToOne(inversedBy: 'prixes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Conditionnement $conditionnement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixConcurentiel = null;

    #[ORM\ManyToOne(inversedBy: 'prixes')]
    private ?ConditionnerCateClient $conditionnerClient = null;

    #[ORM\ManyToOne(inversedBy: 'prixs')]
    private ?Conditionner $conditionner = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixAchat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixRevient = null;


    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $prixVente = null;

    #[ORM\ManyToOne(inversedBy: 'prixs')]
    private ?Client $client = null;

    public function __construct()
    {
        $this->dateAttribution = new DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixMin(): ?string
    {
        return str_replace(" ","",$this->prixMin);
    }

    public function setPrixMin(string $prixMin): self
    {
        $this->prixMin = str_replace(" ","",$prixMin);

        return $this;
    }

    public function getPrixMax(): ?string
    {
        return $this->prixMax;
    }

    public function setPrixMax(string $prixMax): self
    {
        $this->prixMax = str_replace(" ","",$prixMax);

        return $this;
    }

    public function getDateAttribution(): ?\DateTimeInterface
    {
        return $this->dateAttribution;
    }

    public function setDateAttribution(\DateTimeInterface $dateAttribution): self
    {
        $this->dateAttribution = $dateAttribution;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getEstActif()
    {
        return $this->estActif;
    }

    public function setEstActif($estActif): self
    {
        $this->estActif = $estActif;

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

    public function getPrixConcurentiel(): ?string
    {
        return $this->prixConcurentiel;
    }

    public function setPrixConcurentiel(?string $prixConcurentiel): self
    {
        $this->prixConcurentiel = str_replace(" ","",$prixConcurentiel);

        return $this;
    }

    public function getConditionnerClient(): ?ConditionnerCateClient
    {
        return $this->conditionnerClient;
    }

    public function setConditionnerClient(?ConditionnerCateClient $conditionnerClient): self
    {
        $this->conditionnerClient = $conditionnerClient;

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

    public function getPrixAchat(): ?string
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(?string $prixAchat): self
    {
        $this->prixAchat = str_replace(" ","",$prixAchat);

        return $this;
    }

    public function getPrixRevient(): ?string
    {
        return $this->prixRevient;
    }

    public function setPrixRevient(?string $prixRevient): self
    {
        $this->prixRevient = str_replace(" ","",$prixRevient);

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prixVente;
    }

    public function setPrixVente(?string $prixVente): self
    {
        $this->prixVente = str_replace(" ","",$prixVente);

        return $this;
    }

    

}
