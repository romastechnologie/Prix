<?php

namespace App\Entity;

use App\Repository\CategClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;

#[ORM\Entity(repositoryClass: CategClientRepository::class)]
/**
*@ORM\HasLifecycleCallbacks
*/
class CategClient
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

    #[ORM\OneToMany(mappedBy: 'cateClient', targetEntity: ConditionnerCateClient::class, orphanRemoval: true)]
    private Collection $conditionnerCateClients;

    #[ORM\OneToMany(mappedBy: 'cateClient', targetEntity: Client::class)]
    private Collection $clients;

    public function __construct()
    {
        $this->conditionnerCateClients = new ArrayCollection();
        $this->clients = new ArrayCollection();
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
            $conditionnerCateClient->setCateClient($this);
        }

        return $this;
    }

    public function removeConditionnerCateClient(ConditionnerCateClient $conditionnerCateClient): self
    {
        if ($this->conditionnerCateClients->removeElement($conditionnerCateClient)) {
            // set the owning side to null (unless already changed)
            if ($conditionnerCateClient->getCateClient() === $this) {
                $conditionnerCateClient->setCateClient(null);
            }
        }

        return $this;
    }

        public function __toString()
    {
        return "[ ".$this->code. "]--". $this->libelle;
    }

        /**
         * @return Collection<int, Client>
         */
        public function getClients(): Collection
        {
            return $this->clients;
        }

        public function addClient(Client $client): self
        {
            if (!$this->clients->contains($client)) {
                $this->clients->add($client);
                $client->setCateClient($this);
            }

            return $this;
        }

        public function removeClient(Client $client): self
        {
            if ($this->clients->removeElement($client)) {
                // set the owning side to null (unless already changed)
                if ($client->getCateClient() === $this) {
                    $client->setCateClient(null);
                }
            }

            return $this;
        }
}
