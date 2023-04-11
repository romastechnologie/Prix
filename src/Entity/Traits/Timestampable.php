<?php

namespace App\Entity\Traits;

trait Timestampable
{

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default" : "CURRENT_TIMESTAMP"})
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true )
     */
    private $updated;

     /**
      * @ORM\Column(type="datetime", nullable=true )
      */
     private $deleted;
    
    
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

     public function getDeleted(): ?\DateTimeInterface
     {
         return $this->deleted;
     }

     public function setDeleted(?\DateTimeInterface $deleted): self
     {
         $this->deleted = $deleted;

         return $this;
     }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        if( $this->getCreated() === null){
            $this->setCreated(new \DateTimeImmutable);
        }else{
            $this->setUpdated(new \DateTimeImmutable);
        }
        
    }
}

?>